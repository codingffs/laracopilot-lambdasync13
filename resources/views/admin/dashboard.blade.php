@extends('layouts.admin')
@section('title', 'Dashboard — CRM Pro')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of your sales pipeline and performance')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }}, {{ session('crm_user_name') }}! 👋</h2>
            <p class="text-blue-200 text-sm mt-1">Here's what's happening in your CRM today.</p>
        </div>
        <div class="text-right">
            <p class="text-blue-200 text-xs">Today's Date</p>
            <p class="text-white font-semibold">{{ now()->format('l, F j Y') }}</p>
        </div>
    </div>
</div>

<!-- KPI Row 1: Leads -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Total Leads</span>
            <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-funnel-dollar text-blue-600 text-sm"></i></div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($totalLeads) }}</p>
        <p class="text-xs text-blue-600 mt-1"><i class="fas fa-arrow-up"></i> {{ $newLeads }} new this period</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Qualified Leads</span>
            <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center"><i class="fas fa-star text-indigo-600 text-sm"></i></div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($qualifiedLeads) }}</p>
        <p class="text-xs text-indigo-600 mt-1">Ready for conversion</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Converted</span>
            <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-check-circle text-green-600 text-sm"></i></div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($convertedLeads) }}</p>
        <p class="text-xs text-green-600 mt-1">{{ $conversionRate }}% conversion rate</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Customers</span>
            <div class="w-9 h-9 bg-teal-100 rounded-lg flex items-center justify-center"><i class="fas fa-users text-teal-600 text-sm"></i></div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($totalCustomers) }}</p>
        <p class="text-xs text-teal-600 mt-1">Active accounts</p>
    </div>
</div>

<!-- KPI Row 2: Revenue -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Total Revenue</span>
            <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center"><i class="fas fa-dollar-sign text-emerald-600 text-sm"></i></div>
        </div>
        <p class="text-2xl font-bold text-gray-800">${{ number_format($totalRevenue) }}</p>
        <p class="text-xs text-emerald-600 mt-1">From won deals</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Pipeline Value</span>
            <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center"><i class="fas fa-chart-line text-purple-600 text-sm"></i></div>
        </div>
        <p class="text-2xl font-bold text-gray-800">${{ number_format($pipelineValue) }}</p>
        <p class="text-xs text-purple-600 mt-1">Active pipeline</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Won Deals</span>
            <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center"><i class="fas fa-trophy text-yellow-600 text-sm"></i></div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($wonDeals) }}</p>
        <p class="text-xs text-yellow-600 mt-1">of {{ $totalDeals }} total deals</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm">Tasks Due Today</span>
            <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center"><i class="fas fa-clock text-red-600 text-sm"></i></div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $todayTasks }}</p>
        <p class="text-xs text-red-600 mt-1">{{ $overdueTasks }} overdue tasks</p>
    </div>
</div>

<!-- Bottom Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Lead Status Distribution -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Lead Distribution</h3>
        @foreach(['New' => 'blue', 'Contacted' => 'yellow', 'Qualified' => 'indigo', 'Converted' => 'green', 'Lost' => 'red'] as $status => $color)
            @php $count = $leadsByStatus[$status] ?? 0; $pct = $totalLeads > 0 ? round(($count / $totalLeads) * 100) : 0; @endphp
            <div class="mb-3">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-600">{{ $status }}</span>
                    <span class="font-semibold">{{ $count }} ({{ $pct }}%)</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full">
                    <div class="h-2 bg-{{ $color }}-500 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Recent Activity</h3>
        <div class="space-y-3">
            @forelse($recentActivity as $log)
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs flex-shrink-0
                        {{ $log->action === 'created' ? 'bg-green-100 text-green-600' : ($log->action === 'deleted' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }}">
                        <i class="fas {{ $log->action === 'created' ? 'fa-plus' : ($log->action === 'deleted' ? 'fa-trash' : 'fa-edit') }}"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-gray-700 truncate">{{ $log->description }}</p>
                        <p class="text-xs text-gray-400">{{ $log->user?->name }} · {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-4">No activity yet</p>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-700">Upcoming Tasks</h3>
            <a href="{{ route('admin.tasks.create') }}" class="text-xs text-blue-600 hover:underline">+ New Task</a>
        </div>
        <div class="space-y-3">
            @forelse($upcomingTasks as $task)
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                    <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $task->priority === 'Urgent' ? 'bg-red-500' : ($task->priority === 'High' ? 'bg-orange-500' : 'bg-blue-500') }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-700 truncate">{{ $task->title }}</p>
                        <p class="text-xs text-gray-400">Due {{ $task->due_date->format('M d') }} · {{ $task->assignedTo?->name }}</p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-4">No upcoming tasks</p>
            @endforelse
        </div>
        <a href="{{ route('admin.tasks.index') }}" class="block text-center text-xs text-blue-600 hover:underline mt-4">View all tasks →</a>
    </div>
</div>
@endsection
