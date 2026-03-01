@extends('layouts.admin')
@section('page-title', 'User Management')
@section('page-subtitle', 'Manage team members and roles')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Add User
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Roles</option>
            @foreach(['Super Admin','Admin','Manager','Executive'] as $role)
                <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ $role }}</option>
            @endforeach
        </select>
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 px-3 py-2 text-sm hover:text-gray-700">Clear</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Department</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Last Login</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-sm font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $user->role === 'Super Admin' ? 'bg-red-100 text-red-700' : ($user->role === 'Admin' ? 'bg-orange-100 text-orange-700' : ($user->role === 'Manager' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700')) }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $user->department ?? '—' }}</td>
                <td class="px-5 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </td>
                <td class="px-5 py-4 text-xs text-gray-500">{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-gray-500 hover:text-gray-700 mr-3 text-sm" title="View"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 mr-3 text-sm" title="Edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm" title="Delete" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400"><i class="fas fa-users text-4xl mb-3 block"></i>No users found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection
