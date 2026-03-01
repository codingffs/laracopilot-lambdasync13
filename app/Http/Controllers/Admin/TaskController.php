<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmTask;
use App\Models\CrmUser;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private function auth()
    {
        if (!session('crm_logged_in')) return redirect()->route('admin.login');
        return null;
    }

    public function index(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $query = CrmTask::with(['assignedTo', 'createdBy']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('assigned_to')) $query->where('assigned_to', $request->assigned_to);

        if (session('crm_user_role') === 'Executive') {
            $query->where('assigned_to', session('crm_user_id'));
        }

        $tasks = $query->orderBy('due_date')->paginate(20);
        $users = CrmUser::where('status', 'active')->get();
        return view('admin.tasks.index', compact('tasks', 'users'));
    }

    public function create()
    {
        if ($r = $this->auth()) return $r;
        $users = CrmUser::where('status', 'active')->get();
        $leads = Lead::whereNotIn('status', ['Lost', 'Converted'])->get();
        $customers = Customer::where('status', 'Active')->get();
        $deals = Deal::whereNotIn('stage', ['Won', 'Lost'])->get();
        return view('admin.tasks.create', compact('users', 'leads', 'customers', 'deals'));
    }

    public function store(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'assigned_to'      => 'required|exists:crm_users,id',
            'priority'         => 'required|in:Low,Medium,High,Urgent',
            'status'           => 'required|in:Pending,In Progress,Completed,Cancelled',
            'due_date'         => 'required|date',
            'related_type'     => 'nullable|in:Lead,Customer,Deal',
            'related_id'       => 'nullable|integer',
        ]);

        $validated['created_by'] = session('crm_user_id');
        $task = CrmTask::create($validated);

        ActivityLog::create([
            'user_id'     => session('crm_user_id'),
            'action'      => 'created',
            'module'      => 'Task',
            'description' => 'Created task: ' . $task->title,
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created.');
    }

    public function show($id)
    {
        if ($r = $this->auth()) return $r;
        $task = CrmTask::with(['assignedTo', 'createdBy'])->findOrFail($id);
        return view('admin.tasks.show', compact('task'));
    }

    public function edit($id)
    {
        if ($r = $this->auth()) return $r;
        $task = CrmTask::findOrFail($id);
        $users = CrmUser::where('status', 'active')->get();
        $leads = Lead::whereNotIn('status', ['Lost', 'Converted'])->get();
        $customers = Customer::where('status', 'Active')->get();
        $deals = Deal::whereNotIn('stage', ['Won', 'Lost'])->get();
        return view('admin.tasks.edit', compact('task', 'users', 'leads', 'customers', 'deals'));
    }

    public function update(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;

        $task = CrmTask::findOrFail($id);
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'assigned_to'  => 'required|exists:crm_users,id',
            'priority'     => 'required|in:Low,Medium,High,Urgent',
            'status'       => 'required|in:Pending,In Progress,Completed,Cancelled',
            'due_date'     => 'required|date',
            'related_type' => 'nullable|in:Lead,Customer,Deal',
            'related_id'   => 'nullable|integer',
        ]);

        $task->update($validated);
        return redirect()->route('admin.tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;
        CrmTask::findOrFail($id)->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted.');
    }

    public function complete(Request $request, $id)
    {
        if ($r = $this->auth()) return $r;
        $task = CrmTask::findOrFail($id);
        $task->update(['status' => 'Completed', 'completed_at' => now()]);
        return back()->with('success', 'Task marked as completed.');
    }
}