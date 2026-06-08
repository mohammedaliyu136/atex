<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AtexAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('field-officer')) {
            abort(403);
        }

        $logs = AtexAuditLog::with('actor')->latest()->take(50)->get();

        return view('admin.audit.index', compact('logs'));
    }
}

