<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmUser;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function authCheck()
    {
        if (!session('crm_logged_in')) return redirect()->route('admin.login');
        if (!in_array(session('crm_user_role'), ['Super Admin', 'Admin'])) return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        return null;
    }

    public function index(Request $request)
    {
        if ($r = $this->authCheck()) return $r;

        $query = CrmUser::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('status', $request->status);

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if ($r = $this->authCheck()) return $r;
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if ($r = $this->authCheck()) return $r;

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:crm_users,email',
            'phone'      => 'nullable|string|max:20',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:Super Admin,Admin,Manager,Executive',
            'department' => 'nullable|string|max:100',
            'status'     => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = CrmUser::create($validated);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'created',
            'module'      => 'User',
            'description' => 'Created user: ' . $user->name,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        if ($r = $this->authCheck()) return $r;
        $user = CrmUser::withCount(['assignedLeads', 'assignedTasks'])->findOrFail($id);
        $logs = ActivityLog::where('user_id', $id)->orderBy('created_at', 'desc')->limit(20)->get();
        return view('admin.users.show', compact('user', 'logs'));
    }

    public function edit($id)
    {
        if ($r = $this->authCheck()) return $r;
        $user = CrmUser::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if ($r = $this->authCheck()) return $r;

        $user = CrmUser::findOrFail($id);
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:crm_users,email,' . $id,
            'phone'      => 'nullable|string|max:20',
            'role'       => 'required|in:Super Admin,Admin,Manager,Executive',
            'department' => 'nullable|string|max:100',
            'status'     => 'required|in:active,inactive',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'updated',
            'module'      => 'User',
            'description' => 'Updated user: ' . $user->name,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        if ($r = $this->authCheck()) return $r;
        $user = CrmUser::findOrFail($id);
        $name = $user->name;
        $user->delete();

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'deleted',
            'module'      => 'User',
            'description' => 'Deleted user: ' . $name,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}