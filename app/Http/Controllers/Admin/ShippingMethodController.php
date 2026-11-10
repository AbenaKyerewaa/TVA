<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShippingMethodController extends Controller
{
    public function index(): View
    {
        $methods = ShippingMethod::withCount('orders')->orderBy('fee', 'asc')->get();

        return view('admin.shipping.methods-index', compact('methods'));
    }

    public function create(): View
    {
        return view('admin.shipping.methods-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:shipping_methods,code'],
            'fee' => ['required', 'numeric', 'min:0'],
            'estimated_days' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $code = ! empty($validated['code'])
            ? strtoupper(Str::slug($validated['code'], '_'))
            : strtoupper(Str::slug($validated['name'], '_'));

        ShippingMethod::create([
            'name' => $validated['name'],
            'code' => $code,
            'fee' => $validated['fee'],
            'estimated_days' => $validated['estimated_days'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.shipping-methods.index')->with('status', 'Courier tier added successfully.');
    }

    public function edit(ShippingMethod $shippingMethod): View
    {
        return view('admin.shipping.methods-edit', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:shipping_methods,code,'.$shippingMethod->id],
            'fee' => ['required', 'numeric', 'min:0'],
            'estimated_days' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $shippingMethod->update([
            'name' => $validated['name'],
            'code' => strtoupper(Str::slug($validated['code'], '_')),
            'fee' => $validated['fee'],
            'estimated_days' => $validated['estimated_days'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.shipping-methods.index')->with('status', 'Courier service tier updated.');
    }

    public function destroy(ShippingMethod $shippingMethod): RedirectResponse
    {
        if ($shippingMethod->orders()->exists()) {
            return back()->withErrors(['shipping' => 'Cannot delete a shipping tier with historical customer acquisitions. Deactivate it instead.']);
        }

        $shippingMethod->delete();

        return redirect()->route('admin.shipping-methods.index')->with('status', 'Shipping tier removed.');
    }

    public function toggle(ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->update([
            'is_active' => ! $shippingMethod->is_active,
        ]);

        return back()->with('status', 'Shipping tier status toggled to '.($shippingMethod->is_active ? 'Active' : 'Inactive').'.');
    }
}
