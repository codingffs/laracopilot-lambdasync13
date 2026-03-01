@extends('layouts.admin')
@section('page-title', $lead->name)
@section('page-subtitle', 'Lead #' . $lead->lead_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Lead Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="font-bold text-xl text-gray-800">{{ $lead->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $lead->lead_number }} · {{ $lead->company }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.leads.edit', $lead->id) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-700"><i class="fas fa-edit mr-1"></i>Edit</a>
                    @if($lead->status !== 'Converted')
                    <form action="{{ route('admin.leads.convert', $lead->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-green-700"><i class="fas fa-user-check mr-1"></i>Convert</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $lead->email ?? '—' }}</span></div>
                <div><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $lead->phone ?? '—' }}</span></div>
                <div><span class="text-gray-500">Source:</span> <span class="font-medium">{{ $lead->source ?? '—' }}</span></div>
                <div><span class="text-gray-500">Campaign:</span> <span class="font-medium">{{ $lead->campaign ?? '—' }}</span></div>
                <div><span class="text-gray-500">Est. Value:</span> <span class="font-semibold text-green-700">{{ $lead->estimated_value ? '$' . number_format($lead->estimated_value) : '—' }}</span></div>
                <div><span class="text-gray-500">Follow-up:</span> <span class="font-medium {{ $lead->follow_up_date && $lead->follow_up_date->isPast() ? 'text-red-600' : '' }}">{{ $lead->follow_up_date ? $lead->follow_up_date->format('M d, Y') : '—' }}</span></div>
                <div class="col-span-2"><span class="text-gray-500">Tags:</span> @if($lead->tags) @foreach(explode(',', $lead->tags) as $tag)<span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full mr-1">{{ trim($tag) }}</span>@endforeach @else — @endif</div>
                <div class="col-span-2"><span class="text-gray-500">Notes:</span> <p class="mt-1 text-gray-700">{{ $lead->notes ?? '—' }}</p></div>
            </div>
        </div>

        <!-- Lead History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Lead History</h3>
            <div class="space-y-3">
                @forelse($lead->history as $h)
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-700">{{ $h->description }}</p>
                            <p class="text-xs text-gray-400">{{ $h->user?->name }} · {{ $h->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No history yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status & Priority -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="font-semibold text-gray-700 mb-3">Lead Status</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $lead->status === 'New' ? 'bg-blue-100 text-blue-700' : ($lead->status === 'Converted' ? 'bg-green-100 text-green-700' : ($lead->status === 'Lost' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">{{ $lead->status }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Priority</span>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $lead->priority === 'Urgent' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">{{ $lead->priority }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Created</span>
                    <span class="text-sm font-medium">{{ $lead->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Assign -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="font-semibold text-gray-700 mb-3">Assigned To</h4>
            <p class="text-sm mb-3 text-gray-600">{{ $lead->assignedTo?->name ?? 'Unassigned' }}</p>
            <form action="{{ route('admin.leads.assign', $lead->id) }}" method="POST">
                @csrf
                <select name="assigned_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $lead->assigned_to == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded-lg text-sm hover:bg-gray-700">Reassign</button>
            </form>
        </div>

        <!-- Related Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-700">Related Tasks</h4>
                <a href="{{ route('admin.tasks.create') }}" class="text-xs text-blue-600 hover:underline">+ Add</a>
            </div>
            @forelse($lead->tasks as $task)
                <div class="flex items-center gap-2 py-2 border-b border-gray-50 last:border-0">
                    <div class="w-2 h-2 rounded-full {{ $task->status === 'Completed' ? 'bg-green-500' : ($task->isOverdue() ? 'bg-red-500' : 'bg-blue-500') }}"></div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-700">{{ $task->title }}</p>
                        <p class="text-xs text-gray-400">{{ $task->due_date->format('M d') }} · {{ $task->status }}</p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400">No tasks linked.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
