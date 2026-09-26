<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\ContactInformation;
use Blaze\AdminCore\Models\SocialLink;
use Blaze\AdminCore\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyInfoController extends Controller
{
    public function index()
    {
        $contact = ContactInformation::first();
        $socialLinks = SocialLink::orderBy('sort_order')->get();
        $branding = WebsiteSetting::whereIn('key', ['logo', 'favicon'])->pluck('value', 'key')->toArray();

        return view('admin-core::company_info.index', compact('contact', 'socialLinks', 'branding'));
    }

    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'secondary_phone' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'secondary_email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url|max:1000',
            'google_maps_iframe' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:4096',
            'favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg,webp,svg|max:2048',
        ]);

        foreach (['logo', 'favicon'] as $key) {
            if ($request->hasFile($key)) {
                $old = WebsiteSetting::where('key', $key)->first();
                if ($old?->value) {
                    Storage::disk('public')->delete($old->value);
                }

                $path = $request->file($key)->store('branding', 'public');
                WebsiteSetting::updateOrCreate(
                    ['key' => $key],
                    ['group' => 'branding', 'value' => $path, 'type' => 'string']
                );
            }
        }

        unset($validated['logo'], $validated['favicon']);

        ContactInformation::updateOrCreate(
            ['id' => optional(ContactInformation::first())->id],
            $validated
        );

        return redirect(route('admin.company-info.index').'?tab=contact')
            ->with('success', 'Contact information updated successfully.');
    }

    public function storeSocialLink(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $validated['sort_order'] = SocialLink::max('sort_order') + 1;
        $validated['status'] = $request->boolean('status');

        SocialLink::create($validated);

        return redirect(route('admin.company-info.index').'?tab=social-links')
            ->with('success', 'Social link added successfully.');
    }

    public function updateSocialLink(Request $request, SocialLink $socialLink)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        $socialLink->update($validated);

        return redirect(route('admin.company-info.index').'?tab=social-links')
            ->with('success', 'Social link updated successfully.');
    }

    public function destroySocialLink(SocialLink $socialLink)
    {
        $socialLink->delete();

        return redirect(route('admin.company-info.index').'?tab=social-links')
            ->with('success', 'Social link deleted.');
    }

    public function reorderSocialLinks(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        foreach ($request->ids as $order => $id) {
            SocialLink::where('id', $id)->update(['sort_order' => $order]);
        }

        return response()->json(['ok' => true]);
    }
}
