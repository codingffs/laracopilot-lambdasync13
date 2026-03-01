<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CrmUser;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if (!session('crm_logged_in')) return redirect()->route('admin.login');
        if (!in_array(session('crm_user_role'), ['Super Admin', 'Admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }

        $query = ActivityLog::with('user');
        if ($request->filled('module')) $query->where('module', $request->module);
        if ($request->filled('action')) $query->where('action', $request->action);
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);

        $logs  = $query->orderBy('created_at', 'desc')->paginate(30);
        $users = CrmUser::all();
        return view('admin.activity.index', compact('logs', 'users'));
    }
}