@extends('layouts.admin')
@section('page-title', $user->name)
@section('page-subtitle', 'User profile and activity')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-3xl font-bold mx-auto mb-3">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold
                    {{ $user->role === 'Super Admin' ? 'bg-red-100 text-red-700' : ($user->role === 'Admin' ? 'bg-orange-100 text-orange-700' : ($user->role === 'Manager' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700')) }}">{{ $user->role }}</span>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Phone</span><span class="font-medium">{{ $user->phone ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Department</span><span class="font-medium">{{ $user->department ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Status</span><span class="font-medium {{ $user->status === 'active' ? 'text-green-600' : 'text-gray-400' }}">{{ ucfirst($user->status) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Last Login</span><span class="font-medium">{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Leads Assigned</span><span class="font-medium">{{ $user->assigned_leads_count }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Tasks Assigned</span><span class="font-medium">{{ $user->assigned_tasks_count }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Joined</span><span class="font-medium">{{ $user->created_at->format('M d, Y') }}</span></div>
            </div>
            <div class="mt-6 flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium">Edit</a>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Activity Log</h3>
            <div class="space-y-3">
                @forelse($logs as $log)
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs flex-shrink-0
                            {{ $log->action === 'created' ? 'bg-green-100 text-green-600' : ($log->action === 'deleted' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }}">
                            <i class="fas {{ $log->action === 'created' ? 'fa-plus' : ($log->action === 'deleted' ? 'fa-trash' : 'fa-edit') }}"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-700">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400"><span class="font-medium">{{ ucfirst($log->action) }}</span> in {{ $log->module }} · {{ $log->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-8">No activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
