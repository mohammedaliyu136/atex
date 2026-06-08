<?php

namespace App\Http\Controllers\Atex;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AtexAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('field-officer')) {
            abort(403);
        }

        $accounts = User::with(['exporterProfile', 'buyerProfile', 'logisticsProfile'])->latest()->get();

        return view('atex.users.index', compact('accounts'));
    }

    public function status(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('field-officer')) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:active,suspended,pending',
        ]);

        if ((int) $request->user_id === (int) $user->id) {
            return redirect()->back()->with('error', 'You cannot change your own status.');
        }

        $targetUser = User::findOrFail($request->user_id);
        $oldStatus = $targetUser->status;
        $targetUser->update([
            'status' => $request->status,
            'is_active' => $request->status === 'active',
        ]);

        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'updated_user_status',
            'auditable_type' => 'user',
            'auditable_id' => $targetUser->id,
            'old_values' => json_encode(['status' => $oldStatus]),
            'new_values' => json_encode(['status' => $request->status]),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
    }
}
