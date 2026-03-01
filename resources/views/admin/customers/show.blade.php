@extends('layouts.admin')
@section('page-title', $customer->name)
@section('page-subtitle', 'Customer profile and deal history')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-3xl font-bold mx-auto mb-3">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                <h2 class="text-lg font-bold text-gray-800">{{ $customer->name }}</h2>
                <p class="text-sm text-gray-500">{{ $customer->company }}</p>
                <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $customer->status === 'Active' ? 'bg-green-100 text-green-700' : ($customer->status === 'Churned' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $customer->status }}</span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Email</span><span class="font-medium truncate ml-2">{{ $customer->email ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Phone</span><span class="font-medium">{{ $customer->phone ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Industry</span><span class="font-medium">{{ $customer->industry ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Website</span><a href="{{ $customer->website }}" target="_blank" class="text-blue-600 text-xs truncate">{{ $customer->website ?? '—' }}</a></div>
                <div class="flex justify-between"><span class="text-gray-500">Total Revenue</span><span class="font-bold text-green-700">${{ number_format($totalRevenue) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Total Deals</span><span class="font-medium">{{ $customer->deals->count() }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Customer Since</span><span class="font-medium">{{ $customer->created_at->format('M Y') }}</span></div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="block text-center bg-blue-600 text-white py-2 rounded-lg text-sm hover:bg-blue-700">Edit Customer</a>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <!-- Deals -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">Deals ({{ $customer->deals->count() }})</h3>
                <a href="{{ route('admin.deals.create') }}" class="text-xs text-blue-600 hover:underline">+ New Deal</a>
            </div>
            @forelse($customer->deals as $deal)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <a href="{{ route('admin.deals.show', $deal->id) }}" class="text-sm font-semibold text-blue-600 hover:underline">{{ $deal->title }}</a>
                        <p class="text-xs text-gray-400">{{ $deal->stage }} · Expected {{ $deal->expected_close_date?->format('M d, Y') ?? '—' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800">${{ number_format($deal->value) }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $deal->stage === 'Won' ? 'bg-green-100 text-green-700' : ($deal->stage === 'Lost' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">{{ $deal->stage }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">No deals yet.</p>
            @endforelse
        </div>

        <!-- Notes -->
        @if($customer->notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-2">Notes</h3>
            <p class="text-sm text-gray-600">{{ $customer->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
