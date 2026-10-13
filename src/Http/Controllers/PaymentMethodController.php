<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $paymentMethods = PaymentMethod::query()
            ->search($request->get('search'), ['name', 'details'])
            ->filterStatus($request->get('status'))
            ->sort($request->get('sort_by'), $request->get('sort_dir'))
            ->paginate($request->get('per_page', 20))
            ->withQueryString();

        return view('admin-core::payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin-core::payment_methods.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'details' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('payment-method', 'public');
        }

        $validated['status'] = $request->boolean('status');

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin-core::payment_methods.form', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'details' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            if ($paymentMethod->logo && Storage::disk('public')->exists($paymentMethod->logo)) {
                Storage::disk('public')->delete($paymentMethod->logo);
            }
            $validated['logo'] = $request->file('logo')->store('payment-method', 'public');
        }

        $validated['status'] = $request->boolean('status');

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->logo && Storage::disk('public')->exists($paymentMethod->logo)) {
            Storage::disk('public')->delete($paymentMethod->logo);
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }

    public function toggle(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'status' => ! $paymentMethod->status,
        ]);

        $statusText = $paymentMethod->status ? 'shown' : 'hidden';

        return back()->with('success', "Payment method is now {$statusText}.");
    }
}
