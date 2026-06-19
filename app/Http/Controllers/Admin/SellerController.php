<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\UserWelcomeMail;
use App\Mail\EmailVerifiedMail;
use App\Mail\UserPasswordResetMail;
use App\Mail\GeneralUserMail;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query = Seller::query();

        // View Filter (Normal vs Trash)
        if ($request->get('view') === 'trash') {
            $query->onlyTrashed();
        }

        // Exclude Super Admin from list
        $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'super-admin');
        });

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'suspended') {
                $query->where('is_active', false);
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $sellers = $query->with('roles')->paginate(10)->withQueryString();

        // Stats
        $stats = [
            'total' => Seller::whereDoesntHave('roles', fn($q) => $q->where('name', 'super-admin'))->count(),
            'active' => Seller::where('is_active', true)->whereDoesntHave('roles', fn($q) => $q->where('name', 'super-admin'))->count(),
            'suspended' => Seller::where('is_active', false)->whereDoesntHave('roles', fn($q) => $q->where('name', 'super-admin'))->count(),
            'trashed' => Seller::onlyTrashed()->count(),
        ];

        return view('admin.sellers.index', compact('sellers', 'stats'));
    }

    public function show(Seller $seller)
    {
        return view('admin.sellers.show', compact('seller'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'super-admin')->get();
        return view('admin.sellers.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'passport' => 'nullable|image|max:2048',
            'password' => 'required|string|min:8',
            'roles' => 'required|array'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'require_password_change' => true,
        ];

        if ($request->hasFile('passport')) {
            $path = $request->file('passport')->store('passports', 'public');
            $data['passport'] = asset('storage/' . $path);
        }

        $seller = Seller::create($data);

        $seller->assignRole($request->roles);

        // Generate Verification Link (Expires in 48 hours)
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(48),
            ['id' => $seller->id, 'hash' => sha1($seller->getEmailForVerification())]
        );

        // Send Welcome Email
        try {
            \App\Models\Setting::configureMailer();
            Mail::to($seller->email)->send(new UserWelcomeMail($user, $request->password, $verificationUrl));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.sellers.index')->with('success', 'Seller created successfully and notification sent.');
    }

    /**
     * Show verification notice page
     */
    public function showVerificationNotice(Request $request)
    {
        return view('auth.verify-notice', ['email' => $request->email]);
    }

    /**
     * Resend verification link (Public)
     */
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $seller = Seller::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'No account found with this email address.');
        }

        if ($seller->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email already verified. Please login.');
        }

        // Check if user is allowed to request verification
        $canRequest = \App\Models\Setting::get('user_can_request_new_email_verification', '1') == '1';
        if (! $canRequest) {
            return back()->with('error', 'Self-service verification requests are currently disabled. Please contact support.');
        }

        $this->sendVerificationLink($user);

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }

    /**
     * Resend verification link (Admin)
     */
    public function resendVerificationAdmin($id)
    {
        $seller = Seller::findOrFail($id);

        if ($seller->hasVerifiedEmail()) {
            return back()->with('error', 'User is already verified.');
        }

        $this->sendVerificationLink($user);

        return back()->with('success', "New verification link sent to {$seller->name}.");
    }

    /**
     * Reset 2FA for user
     */
    public function resetTwoFactor($id)
    {
        $seller = Seller::findOrFail($id);

        $seller->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        \App\Models\AuthenticationLog::log($user, '2fa_reset', [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', "Two-factor authentication for {$seller->name} has been reset.");
    }

    /**
     * Helper to generate and send link
     */
    private function sendVerificationLink($user)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(48),
            ['id' => $seller->id, 'hash' => sha1($seller->getEmailForVerification())]
        );

        try {
            \App\Models\Setting::configureMailer();
            Mail::to($seller->email)->send(new \App\Mail\ResendVerificationMail($user, $verificationUrl));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Resend Mail failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle email verification from link
     */
    public function verifyEmailLink(Request $request, $id, $hash)
    {
        if (! $request->hasValidSignature()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'The verification link has expired or is invalid.'], 403);
            }
            abort(403, 'The verification link has expired or is invalid.');
        }

        $seller = Seller::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($seller->getEmailForVerification()))) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Invalid verification hash.'], 403);
            }
            abort(403, 'Invalid verification hash.');
        }

        // Handle GET Request: Show Animated Processing Page
        if ($request->isMethod('GET')) {
            return view('auth.verify-processing', [
                'verifyUrl' => URL::full()
            ]);
        }

        // Handle POST Request: Perform Actual Verification
        if ($seller->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        if ($seller->markEmailAsVerified()) {
            \App\Models\AuthenticationLog::log($user, 'email_verified', ['via' => 'signed_link']);

            // Send Confirmation Email
            try {
                \App\Models\Setting::configureMailer();
                Mail::to($seller->email)->send(new EmailVerifiedMail($user));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Confirmation Mail failed: ' . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Email verified successfully!']);
    }

    public function edit(Seller $seller)
    {
        $roles = Role::where('name', '!=', 'super-admin')->get();
        return view('admin.sellers.edit', compact('seller', 'roles'));
    }

    public function update(Request $request, Seller $seller)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $seller->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'passport' => 'nullable|image|max:2048',
            'roles' => 'required|array'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->hasFile('passport')) {
            if ($seller->passport) {
                $oldPath = str_replace(asset('storage/'), '', $seller->passport);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('passport')->store('passports', 'public');
            $data['passport'] = asset('storage/' . $path);
        }

        $seller->update($data);

        if ($request->password) {
            $seller->update(['password' => Hash::make($request->password)]);
        }

        $seller->syncRoles($request->roles);

        return redirect()->route('admin.sellers.index')->with('success', 'Seller profile updated successfully.');
    }

    public function destroy(Seller $seller)
    {
        if (auth()->id() === $seller->id) {
            return redirect()->route('admin.sellers.index')->with('error', 'You cannot delete your own account.');
        }

        $seller->delete();
        return redirect()->route('admin.sellers.index')->with('success', 'Seller moved to trash.');
    }

    public function toggleStatus($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->update(['is_active' => !$seller->is_active]);

        $status = $seller->is_active ? 'activated' : 'suspended';
        return redirect()->back()->with('success', "Account for {$seller->name} has been {$status} successfully.");
    }

    public function resendWelcome($id)
    {
        $seller = Seller::findOrFail($id);
        $password = \Illuminate\Support\Str::random(10);
        $seller->update(['password' => Hash::make($password), 'require_password_change' => true]);

        try {
            \App\Models\Setting::configureMailer();
            Mail::to($seller->email)->send(new UserWelcomeMail($user, $password));
            return redirect()->back()->with('success', "New login credentials sent to {$seller->name} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function verifyEmail($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->update(['email_verified_at' => now()]);
        return redirect()->back()->with('success', "Email for {$seller->name} verified successfully.");
    }

    public function resetPassword($id)
    {
        $seller = Seller::findOrFail($id);
        $password = \Illuminate\Support\Str::random(10);
        $seller->update(['password' => Hash::make($password), 'require_password_change' => true]);

        try {
            \App\Models\Setting::configureMailer();
            Mail::to($seller->email)->send(new UserPasswordResetMail($user, $password));
            return redirect()->back()->with('success', "Password for {$seller->name} reset successfully. New credentials sent to user email.");
        } catch (\Exception $e) {
            return redirect()->back()->with('success', "Password for {$seller->name} reset to: {$password} (Email failed)");
        }
    }

    public function sendCustomEmail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $seller = Seller::findOrFail($id);

        try {
            \App\Models\Setting::configureMailer();
            Mail::to($seller->email)->send(new GeneralUserMail($user, $request->subject, $request->body));
            return redirect()->back()->with('success', "Email sent to {$seller->name} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->ids);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No users selected.');
        }

        $sellers = Seller::whereIn('id', $ids)->get();
        $count = $sellers->count();

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $users) {
            foreach ($sellers as $user) {
                switch ($request->action) {
                    case 'activate':
                        $seller->update(['is_active' => true]);
                        break;
                    case 'suspend':
                        $seller->update(['is_active' => false]);
                        break;
                    case 'require_password':
                        $seller->update(['require_password_change' => true]);
                        break;
                    case 'remove_password_req':
                        $seller->update(['require_password_change' => false]);
                        break;
                    case 'delete':
                        $seller->delete();
                        break;
                }
            }
        });

        $actionName = str_replace('_', ' ', $request->action);
        return redirect()->back()->with('success', "Bulk action '{$actionName}' completed for {$count} users.");
    }

    public function unlock($id)
    {
        try {
            $seller = Seller::findOrFail($id);
            $seller->update(['locked_until' => null]);
            
            \App\Models\AuthenticationLog::log($user, 'unlock', [
                'unlocked_by' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'User account has been unlocked.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to unlock user.');
        }
    }

    public function authLogs($id)
    {
        $seller = Seller::findOrFail($id);
        $logs = \App\Models\AuthenticationLog::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.sellers.auth-logs', compact('seller', 'logs'));
    }

    public function allAuthLogs()
    {
        $logs = \App\Models\AuthenticationLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.sellers.all-auth-logs', compact('logs'));
    }

    public function restore($id)
    {
        $seller = Seller::withTrashed()->findOrFail($id);
        $seller->restore();
        return redirect()->route('admin.sellers.index')->with('success', "Account for {$seller->name} has been restored successfully.");
    }

    public function forceDelete($id)
    {
        $seller = Seller::withTrashed()->findOrFail($id);
        $name = $seller->name;
        $seller->forceDelete();
        return redirect()->route('admin.sellers.index', ['view' => 'trash'])->with('success', "Account for {$name} has been permanently deleted.");
    }
}

