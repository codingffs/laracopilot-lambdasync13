@extends('layouts.admin')
@section('page-title', 'Deals')
@section('page-subtitle', 'Track opportunities and revenue')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="{{ route('admin.deals.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> New Deal
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search deals..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="stage" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Stages</option>
            @foreach($stages as $stage)
                <option value="{{ $stage }}" {{ request('stage') === $stage ? 'selected' : '' }}>{{ $stage }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.deals.index') }}" class="text-gray-500 px-3 py-2 text-sm">Clear</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deal</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stage</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Value</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Probability</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Close Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($deals as $deal)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <a href="{{ route('admin.deals.show', $deal->id) }}" class="text-sm font-semibold text-blue-600 hover:underline">{{ $deal->title }}</a>
                </td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $deal->customer?->name ?? '—' }}</td>
                <td class="px-5 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $deal->stage === 'Won' ? 'bg-green-100 text-green-700' : ($deal->stage === 'Lost' ? 'bg-red-100 text-red-700' : ($deal->stage === 'Negotiation' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ $deal->stage }}
                    </span>
                </td>
                <td class="px-5 py-4 text-sm font-semibold text-gray-800">${{ number_format($deal->value) }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="h-1.5 bg-gray-100 rounded-full w-16">
                            <div class="h-1.5 bg-blue-500 rounded-full" style="width: {{ $deal->probability }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600">{{ $deal->probability }}%</span>
                    </div>
                </td>
                <td class="px-5 py-4 text-xs {{ $deal->expected_close_date && $deal->expected_close_date->isPast() && $deal->stage !== 'Won' ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                    {{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : '—' }}
                </td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $deal->assignedTo?->name ?? '—' }}</td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('admin.deals.show', $deal->id) }}" class="text-gray-500 hover:text-gray-700 mr-2 text-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('admin.deals.edit', $deal->id) }}" class="text-blue-600 hover:text-blue-800 mr-2 text-sm"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.deals.destroy', $deal->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400"><i class="fas fa-handshake text-4xl mb-3 block"></i>No deals found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($deals->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $deals->links() }}</div>
    @endif
</div>
@endsection
