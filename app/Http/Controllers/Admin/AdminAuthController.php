<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('crm_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = CrmUser::where('email', $request->email)
            ->where('status', 'active')
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $user->update(['last_login' => now()]);

            session([
                'crm_logged_in' => true,
                'crm_user_id'   => $user->id,
                'crm_user_name' => $user->name,
                'crm_user_email'=> $user->email,
                'crm_user_role' => $user->role,
                'crm_user_dept' => $user->department,
            ]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or account inactive.']);
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('admin.login');
    }
}