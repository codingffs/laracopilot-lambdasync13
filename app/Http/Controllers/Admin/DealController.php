<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Customer;
use App\Models\CrmUser;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DealController extends Controller
{
    private function auth()
    {
        if (!session('crm_logged_in')) return redirect()->route('admin.login');
        return null;
    }

    public function index(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $query = Deal::with(['customer', 'assignedTo']);
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('stage')) $query->where('stage', $request->stage);
        if ($request->filled('assigned_to')) $query->where('assigned_to', $request->assigned_to);

        if (session('crm_user_role') === 'Executive') {
            $query->where('assigned_to', session('crm_user_id'));
        }

        $deals = $query->orderBy('created_at', 'desc')->paginate(20);
        $customers = Customer::where('status', 'Active')->get();
        $users = CrmUser::where('status', 'active')->get();
        $stages = ['Prospecting', 'Qualification', 'Proposal', 'Negotiation', 'Won', 'Lost'];
        return view('admin.deals.index', compact('deals', 'customers', 'users', 'stages'));
    }

    public function create()
    {
        if ($r = $this->auth()) return $r;
        $customers = Customer::where('status', 'Active')->get();
        $users = CrmUser::where('status', 'active')->get();
        return view('admin.deals.create', compact('customers', 'users'));
    }

    public function store(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'customer_id'         => 'required|exists:customers,id',
            'assigned_to'         => 'nullable|exists:crm_users,id',
            'stage'               => 'required|in:Prospecting,Qualification,Proposal,Negotiation,Won,Lost',
            'value'               => 'required|numeric|min:0',
            'probability'         => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'description'         => 'nullable|string',
        ]);

        $deal = Deal::create($validated);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'created',
            'module'      => 'Deal',
            'description' => 'Created deal: ' . $deal->title,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.deals.show', $deal->id)->with('success', 'Deal created.');
    }

    public function show($id)
    {
        if ($r = $this->auth()) return $r;
        $deal = Deal::with(['customer', 'assignedTo', 'tasks'])->findOrFail($id);
        return view('admin.deals.show', compact('deal'));
    }

    public function edit($id)
    {
        if ($r = $this->auth()) return $r;
        $deal = Deal::findOrFail($id);
        $customers = Customer::where('status', 'Active')->get();
        $users = CrmUser::where('status', 'active')->get();
        return view('admin.deals.edit', compact('deal', 'customers', 'users'));
    }

    public function update(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;

        $deal = Deal::findOrFail($id);
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'customer_id'         => 'required|exists:customers,id',
            'assigned_to'         => 'nullable|exists:crm_users,id',
            'stage'               => 'required|in:Prospecting,Qualification,Proposal,Negotiation,Won,Lost',
            'value'               => 'required|numeric|min:0',
            'probability'         => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'description'         => 'nullable|string',
        ]);

        $deal->update($validated);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'updated',
            'module'      => 'Deal',
            'description' => 'Updated deal: ' . $deal->title,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.deals.show', $deal->id)->with('success', 'Deal updated.');
    }

    public function destroy(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;
        $deal = Deal::findOrFail($id);
        $deal->delete();
        return redirect()->route('admin.deals.index')->with('success', 'Deal deleted.');
    }
}