<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.coupons.index') }}" class="text-xs uppercase tracking-wider text-stone-500 hover:text-amber-700 font-semibold mb-1 inline-block">
                    &larr; Back To Privilege Codes
                </a>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Register Privilege Code</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-stone-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 border border-stone-200 rounded-lg shadow-sm">
                
                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.coupons.store') }}" class="space-y-6 text-xs">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Code</label>
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. VIP2026" required class="w-full text-sm font-mono uppercase border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Discount Type</label>
                            <select name="type" required class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage Discount (%)</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Discount Value</label>
                            <input type="number" step="0.01" min="0" name="value" value="{{ old('value', '10.00') }}" required class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Minimum Order ($)</label>
                            <input type="number" step="0.01" min="0" name="min_spend" value="{{ old('min_spend', '500.00') }}" class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Max Discount ($)</label>
                            <input type="number" step="0.01" min="0" name="max_discount" value="{{ old('max_discount') }}" placeholder="Optional cap" class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Max Total Claims</label>
                            <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Unlimited if blank" class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Expiry Date</label>
                            <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-0">
                        <label for="is_active" class="font-medium text-stone-700">Active and redeemable by patrons</label>
                    </div>

                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 border border-stone-200 text-stone-600 rounded uppercase tracking-wider font-medium hover:border-stone-400">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded uppercase tracking-wider font-semibold shadow-sm">
                            Register Privilege Code
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

