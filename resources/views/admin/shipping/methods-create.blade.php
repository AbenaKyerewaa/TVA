<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.shipping-methods.index') }}" class="text-xs uppercase tracking-wider text-stone-500 hover:text-amber-700 font-semibold mb-1 inline-block">
                    &larr; Back To Courier Tiers
                </a>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">Add Courier Service Tier</h2>
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

                <form method="POST" action="{{ route('admin.shipping-methods.store') }}" class="space-y-6 text-xs">
                    @csrf

                    <div>
                        <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Service Tier Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ferrari Security Armored Express" required class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Fee (USD)</label>
                            <input type="number" step="0.01" min="0" name="fee" value="{{ old('fee', '0.00') }}" required class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                            <span class="text-[11px] text-stone-400">Set 0.00 for complimentary luxury delivery</span>
                        </div>
                        <div>
                            <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Estimated Delivery</label>
                            <input type="text" name="estimated_days" value="{{ old('estimated_days', '1-2 Business Days') }}" required class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0">
                        </div>
                    </div>

                    <div>
                        <label class="block uppercase tracking-wider font-semibold text-stone-700 mb-1">Description & Security Details</label>
                        <textarea name="description" rows="3" class="w-full text-sm border-stone-200 rounded focus:border-amber-500 focus:ring-0" placeholder="e.g. Armored vehicle transit with biometric sign-off and armed guard protocol.">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-stone-300 text-amber-600 focus:ring-0">
                        <label for="is_active" class="font-medium text-stone-700">Active and available for checkout selection</label>
                    </div>

                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.shipping-methods.index') }}" class="px-4 py-2 border border-stone-200 text-stone-600 rounded uppercase tracking-wider font-medium hover:border-stone-400">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded uppercase tracking-wider font-semibold shadow-sm">
                            Create Courier Tier
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

