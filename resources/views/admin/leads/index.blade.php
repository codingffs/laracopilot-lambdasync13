@extends('layouts.admin')
@section('page-title', 'Lead Management')
@section('page-subtitle', 'Track and manage your sales pipeline')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex gap-2">
        <a href="{{ route('admin.leads.pipeline') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-columns"></i> Pipeline View
        </a>
        <a href="{{ route('admin.leads.export') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>
    <a href="{{ route('admin.leads.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> New Lead
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, company..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['New','Contacted','Qualified','Lost','Converted'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <select name="priority" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Priority</option>
            @foreach(['Low','Medium','High','Urgent'] as $p)
                <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        <select name="assigned_to" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Assignees</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('assigned_to') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.leads.index') }}" class="text-gray-500 px-3 py-2 text-sm hover:text-gray-700">Clear</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Lead</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Company</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Value</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned To</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Follow-up</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($leads as $lead)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <p class="text-sm font-semibold text-gray-800">{{ $lead->name }}</p>
                    <p class="text-xs text-gray-400">{{ $lead->lead_number }} · {{ $lead->email }}</p>
                </td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $lead->company ?? '—' }}</td>
                <td class="px-5 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $lead->status === 'New' ? 'bg-blue-100 text-blue-700' : ($lead->status === 'Contacted' ? 'bg-yellow-100 text-yellow-700' : ($lead->status === 'Qualified' ? 'bg-indigo-100 text-indigo-700' : ($lead->status === 'Lost' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'))) }}">
                        {{ $lead->status }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $lead->priority === 'Urgent' ? 'bg-red-100 text-red-700' : ($lead->priority === 'High' ? 'bg-orange-100 text-orange-700' : ($lead->priority === 'Medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600')) }}">
                        {{ $lead->priority }}
                    </span>
                </td>
                <td class="px-5 py-4 text-sm text-gray-700">{{ $lead->estimated_value ? '$' . number_format($lead->estimated_value) : '—' }}</td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $lead->assignedTo?->name ?? 'Unassigned' }}</td>
                <td class="px-5 py-4 text-xs {{ $lead->follow_up_date && $lead->follow_up_date->isPast() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                    {{ $lead->follow_up_date ? $lead->follow_up_date->format('M d, Y') : '—' }}
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.leads.show', $lead->id) }}" class="text-gray-500 hover:text-gray-700 mr-2 text-sm" title="View"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.leads.edit', $lead->id) }}" class="text-blue-600 hover:text-blue-800 mr-2 text-sm" title="Edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Delete this lead?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400"><i class="fas fa-funnel-dollar text-4xl mb-3 block"></i>No leads found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($leads->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $leads->links() }}</div>
    @endif
</div>
@endsection
