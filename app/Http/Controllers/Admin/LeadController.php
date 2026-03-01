<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\CrmUser;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    private function auth()
    {
        if (!session('crm_logged_in')) return redirect()->route('admin.login');
        return null;
    }

    public function index(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $query = Lead::with(['assignedTo']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%')
                  ->orWhere('lead_number', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('source')) $query->where('source', $request->source);
        if ($request->filled('assigned_to')) $query->where('assigned_to', $request->assigned_to);

        // Role-based filter: Executive sees only their leads
        if (session('crm_user_role') === 'Executive') {
            $query->where('assigned_to', session('crm_user_id'));
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = CrmUser::where('status', 'active')->get();
        return view('admin.leads.index', compact('leads', 'users'));
    }

    public function pipeline()
    {
        if ($r = $this->auth()) return $r;

        $statuses = ['New', 'Contacted', 'Qualified', 'Lost', 'Converted'];
        $pipeline = [];
        foreach ($statuses as $status) {
            $query = Lead::with('assignedTo')->where('status', $status);
            if (session('crm_user_role') === 'Executive') {
                $query->where('assigned_to', session('crm_user_id'));
            }
            $pipeline[$status] = $query->orderBy('priority')->get();
        }
        return view('admin.leads.pipeline', compact('pipeline', 'statuses'));
    }

    public function create()
    {
        if ($r = $this->auth()) return $r;
        $users = CrmUser::where('status', 'active')->get();
        return view('admin.leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:20',
            'company'         => 'nullable|string|max:255',
            'source'          => 'nullable|string|max:100',
            'campaign'        => 'nullable|string|max:100',
            'status'          => 'required|in:New,Contacted,Qualified,Lost,Converted',
            'priority'        => 'required|in:Low,Medium,High,Urgent',
            'assigned_to'     => 'nullable|exists:crm_users,id',
            'estimated_value' => 'nullable|numeric|min:0',
            'follow_up_date'  => 'nullable|date',
            'notes'           => 'nullable|string',
            'tags'            => 'nullable|string',
        ]);

        // Duplicate detection
        if (!empty($validated['email'])) {
            $duplicate = Lead::where('email', $validated['email'])->first();
            if ($duplicate) {
                return back()->withInput()->withErrors(['email' => 'A lead with this email already exists: ' . $duplicate->lead_number]);
            }
        }

        $validated['lead_number'] = 'LD-' . strtoupper(Str::random(6));
        $validated['created_by'] = session('crm_user_id');

        $lead = Lead::create($validated);

        LeadHistory::create([
            'lead_id'     => $lead->id,
            'user_id'     => session('crm_user_id'),
            'action'      => 'created',
            'description' => 'Lead created with status: ' . $lead->status,
        ]);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'created',
            'module'      => 'Lead',
            'description' => 'Created lead: ' . $lead->lead_number . ' - ' . $lead->name,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.leads.show', $lead->id)->with('success', 'Lead created successfully.');
    }

    public function show($id)
    {
        if ($r = $this->auth()) return $r;
        $lead    = Lead::with(['assignedTo', 'createdBy', 'history.user', 'tasks'])->findOrFail($id);
        $users   = CrmUser::where('status', 'active')->get();
        return view('admin.leads.show', compact('lead', 'users'));
    }

    public function edit($id)
    {
        if ($r = $this->auth()) return $r;
        $lead  = Lead::findOrFail($id);
        $users = CrmUser::where('status', 'active')->get();
        return view('admin.leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;

        $lead = Lead::findOrFail($id);
        $oldStatus = $lead->status;

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:20',
            'company'         => 'nullable|string|max:255',
            'source'          => 'nullable|string|max:100',
            'campaign'        => 'nullable|string|max:100',
            'status'          => 'required|in:New,Contacted,Qualified,Lost,Converted',
            'priority'        => 'required|in:Low,Medium,High,Urgent',
            'assigned_to'     => 'nullable|exists:crm_users,id',
            'estimated_value' => 'nullable|numeric|min:0',
            'follow_up_date'  => 'nullable|date',
            'notes'           => 'nullable|string',
            'tags'            => 'nullable|string',
        ]);

        $lead->update($validated);

        if ($oldStatus !== $validated['status']) {
            LeadHistory::create([
                'lead_id'     => $lead->id,
                'user_id'     => session('crm_user_id'),
                'action'      => 'status_changed',
                'description' => 'Status changed from ' . $oldStatus . ' to ' . $validated['status'],
            ]);
        }

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'updated',
            'module'      => 'Lead',
            'description' => 'Updated lead: ' . $lead->lead_number,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.leads.show', $lead->id)->with('success', 'Lead updated.');
    }

    public function destroy(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;
        $lead = Lead::findOrFail($id);
        $lead->delete();

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'deleted',
            'module'      => 'Lead',
            'description' => 'Deleted lead: ' . $lead->lead_number,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }

    public function assign(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;
        $lead = Lead::findOrFail($id);
        $request->validate(['assigned_to' => 'required|exists:crm_users,id']);
        $old = $lead->assigned_to;
        $lead->update(['assigned_to' => $request->assigned_to]);

        LeadHistory::create([
            'lead_id'     => $lead->id,
            'user_id'     => session('crm_user_id'),
            'action'      => 'reassigned',
            'description' => 'Lead reassigned from user #' . $old . ' to user #' . $request->assigned_to,
        ]);

        return back()->with('success', 'Lead assigned successfully.');
    }

    public function convert(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;
        $lead = Lead::findOrFail($id);

        $customer = Customer::create([
            'name'      => $lead->name,
            'email'     => $lead->email,
            'phone'     => $lead->phone,
            'company'   => $lead->company,
            'source'    => $lead->source,
            'lead_id'   => $lead->id,
            'status'    => 'Active',
        ]);

        $lead->update(['status' => 'Converted']);

        LeadHistory::create([
            'lead_id'     => $lead->id,
            'user_id'     => session('crm_user_id'),
            'action'      => 'converted',
            'description' => 'Lead converted to Customer #' . $customer->id,
        ]);

        return redirect()->route('admin.customers.show', $customer->id)->with('success', 'Lead converted to customer.');
    }

    public function export()
    {
        if ($r = $this->auth()) return $r;

        $leads = Lead::with('assignedTo')->get();
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads_' . date('Y_m_d') . '.csv"',
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Lead #', 'Name', 'Email', 'Phone', 'Company', 'Source', 'Status', 'Priority', 'Assigned To', 'Estimated Value', 'Follow Up Date', 'Created At']);
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->lead_number, $lead->name, $lead->email, $lead->phone,
                    $lead->company, $lead->source, $lead->status, $lead->priority,
                    $lead->assignedTo?->name, $lead->estimated_value, $lead->follow_up_date, $lead->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}