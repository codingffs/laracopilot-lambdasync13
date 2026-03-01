@extends('layouts.admin')
@section('page-title', 'Customers')
@section('page-subtitle', 'Manage your customer accounts')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="{{ route('admin.customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> New Customer
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, company..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            @foreach(['Active','Inactive','Churned'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.customers.index') }}" class="text-gray-500 px-3 py-2 text-sm">Clear</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Company</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Industry</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deals</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Since</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-sm font-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $customer->name }}</p>
                            <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $customer->company ?? '—' }}</td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $customer->industry ?? '—' }}</td>
                <td class="px-5 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $customer->status === 'Active' ? 'bg-green-100 text-green-700' : ($customer->status === 'Churned' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $customer->status }}</span>
                </td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $customer->deals_count }}</td>
                <td class="px-5 py-4 text-xs text-gray-500">{{ $customer->created_at->format('M Y') }}</td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-gray-500 hover:text-gray-700 mr-2 text-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-blue-600 hover:text-blue-800 mr-2 text-sm"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400"><i class="fas fa-users text-4xl mb-3 block"></i>No customers found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($customers->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
