<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs uppercase tracking-prestige text-amber-700 font-semibold">Client Portfolio</span>
                <h2 class="font-serif text-2xl text-stone-900 leading-tight">My Atelier Preferences</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs uppercase tracking-wider text-stone-600 hover:text-stone-900 underline">
                &larr; Back to Client Portal
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-stone-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white p-8 border border-stone-200 rounded-sm shadow-sm">
                <h3 class="font-serif text-xl text-stone-900 mb-1">Personal Jewelry Specifications</h3>
                <p class="text-xs text-stone-500 mb-6">These details allow our concierge to tailor bespoke recommendations, anniversary reminders, and ring sizing.</p>

                <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="phone" value="Concierge Telephone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="+1 (555) 000-0000" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div>
                            <x-input-label for="date_of_birth" value="Date of Birth" />
                            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', $user->customerProfile->date_of_birth?->format('Y-m-d'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                        </div>

                        <div>
                            <x-input-label for="anniversary_date" value="Anniversary Date" />
                            <x-text-input id="anniversary_date" name="anniversary_date" type="date" class="mt-1 block w-full" :value="old('anniversary_date', $user->customerProfile->anniversary_date?->format('Y-m-d'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('anniversary_date')" />
                        </div>

                        <div>
                            <x-input-label for="gender" value="Title / Salutation" />
                            <select id="gender" name="gender" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm">
                                <option value="">Select salutation...</option>
                                <option value="Ms" {{ old('gender', $user->customerProfile?->gender) === 'Ms' ? 'selected' : '' }}>Ms.</option>
                                <option value="Mrs" {{ old('gender', $user->customerProfile?->gender) === 'Mrs' ? 'selected' : '' }}>Mrs.</option>
                                <option value="Mr" {{ old('gender', $user->customerProfile?->gender) === 'Mr' ? 'selected' : '' }}>Mr.</option>
                                <option value="Dr" {{ old('gender', $user->customerProfile?->gender) === 'Dr' ? 'selected' : '' }}>Dr.</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                        </div>

                        <div>
                            <x-input-label for="preferred_metal" value="Preferred Precious Metal" />
                            <select id="preferred_metal" name="preferred_metal" class="mt-1 block w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-xs text-sm">
                                <option value="">Select metal preference...</option>
                                <option value="18K Yellow Gold" {{ old('preferred_metal', $user->customerProfile?->preferences['preferred_metal'] ?? '') === '18K Yellow Gold' ? 'selected' : '' }}>18K Solid Yellow Gold</option>
                                <option value="18K White Gold" {{ old('preferred_metal', $user->customerProfile?->preferences['preferred_metal'] ?? '') === '18K White Gold' ? 'selected' : '' }}>18K Solid White Gold</option>
                                <option value="18K Rose Gold" {{ old('preferred_metal', $user->customerProfile?->preferences['preferred_metal'] ?? '') === '18K Rose Gold' ? 'selected' : '' }}>18K Solid Rose Gold</option>
                                <option value="950 Platinum" {{ old('preferred_metal', $user->customerProfile?->preferences['preferred_metal'] ?? '') === '950 Platinum' ? 'selected' : '' }}>950 Pure Platinum</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="ring_size" value="Primary Ring Size (US)" />
                            <x-text-input id="ring_size" name="ring_size" type="text" class="mt-1 block w-full" :value="old('ring_size', $user->customerProfile?->preferences['ring_size'] ?? '')" placeholder="e.g. 6.5" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-stone-100">
                        <x-primary-button>
                            Save Client Preferences
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

