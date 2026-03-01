@extends('layouts.admin')
@section('page-title', $deal->title)
@section('page-subtitle', 'Deal details and tasks')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="font-bold text-xl text-gray-800">{{ $deal->title }}</h3>
                    <p class="text-gray-500 text-sm">{{ $deal->customer?->name }}</p>
                </div>
                <a href="{{ route('admin.deals.edit', $deal->id) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-700"><i class="fas fa-edit mr-1"></i>Edit</a>
            </div>
            <!-- Stage Progress -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    @foreach(['Prospecting','Qualification','Proposal','Negotiation','Won'] as $i => $stage)
                        <div class="text-center flex-1">
                            <div class="w-6 h-6 rounded-full mx-auto flex items-center justify-center text-xs font-bold mb-1
                                {{ in_array($deal->stage, array_slice(['Prospecting','Qualification','Proposal','Negotiation','Won'], 0, $i + 1)) || $deal->stage === $stage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                                {{ $i + 1 }}
                            </div>
                            <p class="text-xs text-gray-500">{{ $stage }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Value:</span> <span class="font-bold text-2xl text-green-700">${{ number_format($deal->value) }}</span></div>
                <div><span class="text-gray-500">Weighted:</span> <span class="font-semibold text-gray-700">${{ number_format($deal->weighted_value) }}</span></div>
                <div><span class="text-gray-500">Probability:</span> <span class="font-medium">{{ $deal->probability }}%</span></div>
                <div><span class="text-gray-500">Close Date:</span> <span class="font-medium">{{ $deal->expected_close_date?->format('M d, Y') ?? '—' }}</span></div>
                <div><span class="text-gray-500">Assigned:</span> <span class="font-medium">{{ $deal->assignedTo?->name ?? '—' }}</span></div>
                <div><span class="text-gray-500">Created:</span> <span class="font-medium">{{ $deal->created_at->format('M d, Y') }}</span></div>
                @if($deal->description)
                <div class="col-span-2"><span class="text-gray-500">Description:</span><p class="mt-1 text-gray-700">{{ $deal->description }}</p></div>
                @endif
            </div>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="text-center">
                <p class="text-gray-500 text-sm">Deal Stage</p>
                <span class="inline-block mt-2 px-4 py-2 rounded-full font-semibold text-sm {{ $deal->stage === 'Won' ? 'bg-green-100 text-green-700' : ($deal->stage === 'Lost' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">{{ $deal->stage }}</span>
                <div class="mt-4">
                    <div class="h-3 bg-gray-100 rounded-full">
                        <div class="h-3 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all" style="width: {{ $deal->probability }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $deal->probability }}% probability</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-gray-700">Tasks</h4>
                <a href="{{ route('admin.tasks.create') }}" class="text-xs text-blue-600 hover:underline">+ Add</a>
            </div>
            @forelse($deal->tasks as $task)
                <div class="py-2 border-b border-gray-50 last:border-0">
                    <p class="text-xs font-medium text-gray-700">{{ $task->title }}</p>
                    <p class="text-xs text-gray-400">{{ $task->due_date->format('M d') }} · {{ $task->status }}</p>
                </div>
            @empty
                <p class="text-xs text-gray-400">No tasks linked.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
