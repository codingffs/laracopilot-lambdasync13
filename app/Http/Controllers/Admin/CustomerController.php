<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private function auth()
    {
        if (!session('crm_logged_in')) return redirect()->route('admin.login');
        return null;
    }

    public function index(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $query = Customer::withCount('deals');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        if ($r = $this->auth()) return $r;
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|unique:customers,email',
            'phone'    => 'nullable|string|max:20',
            'company'  => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'website'  => 'nullable|url|max:255',
            'address'  => 'nullable|string',
            'status'   => 'required|in:Active,Inactive,Churned',
            'notes'    => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'created',
            'module'      => 'Customer',
            'description' => 'Created customer: ' . $customer->name,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.customers.show', $customer->id)->with('success', 'Customer created.');
    }

    public function show($id)
    {
        if ($r = $this->auth()) return $r;
        $customer = Customer::with(['deals', 'lead', 'tasks'])->findOrFail($id);
        $totalRevenue = $customer->deals->where('stage', 'Won')->sum('value');
        return view('admin.customers.show', compact('customer', 'totalRevenue'));
    }

    public function edit($id)
    {
        if ($r = $this->auth()) return $r;
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;

        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|unique:customers,email,' . $id,
            'phone'    => 'nullable|string|max:20',
            'company'  => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:100',
            'website'  => 'nullable|url|max:255',
            'address'  => 'nullable|string',
            'status'   => 'required|in:Active,Inactive,Churned',
            'notes'    => 'nullable|string',
        ]);

        $customer->update($validated);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'updated',
            'module'      => 'Customer',
            'description' => 'Updated customer: ' . $customer->name,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.customers.show', $customer->id)->with('success', 'Customer updated.');
    }

    public function destroy(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted.');
    }
}