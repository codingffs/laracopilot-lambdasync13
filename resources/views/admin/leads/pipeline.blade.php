@extends('layouts.admin')
@section('page-title', 'Sales Pipeline')
@section('page-subtitle', 'Visual kanban view of lead progression')

@section('content')
<div class="flex gap-4 overflow-x-auto pb-4">
    @foreach($statuses as $status)
        @php
            $colors = ['New' => 'blue', 'Contacted' => 'yellow', 'Qualified' => 'indigo', 'Lost' => 'red', 'Converted' => 'green'];
            $color  = $colors[$status] ?? 'gray';
            $leads  = $pipeline[$status];
            $total  = $leads->sum('estimated_value');
        @endphp
        <div class="flex-shrink-0 w-72">
            <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-xl p-3 mb-3">
                <div class="flex justify-between items-center">
                    <span class="text-{{ $color }}-700 font-semibold text-sm">{{ $status }}</span>
                    <span class="bg-{{ $color }}-200 text-{{ $color }}-800 text-xs px-2 py-0.5 rounded-full font-bold">{{ $leads->count() }}</span>
                </div>
                @if($total > 0)
                    <p class="text-{{ $color }}-600 text-xs mt-1">${{ number_format($total) }} total value</p>
                @endif
            </div>
            <div class="space-y-3">
                @forelse($leads as $lead)
                    <a href="{{ route('admin.leads.show', $lead->id) }}" class="block bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-semibold text-sm text-gray-800">{{ $lead->name }}</p>
                            <span class="text-xs px-1.5 py-0.5 rounded font-semibold
                                {{ $lead->priority === 'Urgent' ? 'bg-red-100 text-red-700' : ($lead->priority === 'High' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $lead->priority }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">{{ $lead->company ?? 'No company' }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">{{ $lead->assignedTo?->name ?? 'Unassigned' }}</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $lead->estimated_value ? '$' . number_format($lead->estimated_value) : '' }}</span>
                        </div>
                        @if($lead->follow_up_date)
                            <p class="text-xs mt-2 {{ $lead->follow_up_date->isPast() ? 'text-red-500' : 'text-gray-400' }}">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $lead->follow_up_date->format('M d') }}
                            </p>
                        @endif
                    </a>
                @empty
                    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-6 text-center">
                        <p class="text-xs text-gray-400">No leads in this stage</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
