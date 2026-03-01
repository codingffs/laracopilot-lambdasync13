@extends('layouts.admin')
@section('page-title', 'Edit Deal')
@section('page-subtitle', 'Update deal information')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.deals.update', $deal->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deal Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $deal->title) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
                    <select name="customer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $deal->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                    <select name="assigned_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('assigned_to', $deal->assigned_to) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stage <span class="text-red-500">*</span></label>
                    <select name="stage" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        @foreach(['Prospecting','Qualification','Proposal','Negotiation','Won','Lost'] as $s)
                            <option value="{{ $s }}" {{ old('stage', $deal->stage) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deal Value ($)</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $deal->value) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Probability (%)</label>
                    <input type="number" name="probability" value="{{ old('probability', $deal->probability) }}" min="0" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Close Date</label>
                    <input type="date" name="expected_close_date" value="{{ old('expected_close_date', $deal->expected_close_date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description', $deal->description) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                <a href="{{ route('admin.deals.show', $deal->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Update Deal</button>
            </div>
        </form>
    </div>
</div>
@endsection
