<?php

namespace Blaze\AdminCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\AdminCore\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeoSettingController extends Controller
{
    public function index()
    {
        $keys = ['seo_default_title', 'seo_default_description', 'seo_default_image', 'seo_default_keywords'];
        $settings = WebsiteSetting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        
        return view('admin-core::seo_settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'seo_default_title' => 'nullable|string|max:255',
            'seo_default_description' => 'nullable|string',
            'seo_default_keywords' => 'nullable|string',
            'seo_default_image' => 'nullable|image|max:2048',
        ]);

        foreach (['seo_default_title', 'seo_default_description', 'seo_default_keywords'] as $key) {
            if (isset($validated[$key])) {
                WebsiteSetting::updateOrCreate(
                    ['key' => $key],
                    ['group' => 'seo', 'value' => $validated[$key], 'type' => 'string']
                );
            }
        }

        if ($request->hasFile('seo_default_image')) {
            $path = $request->file('seo_default_image')->store('seo', 'public');
            
            // Delete old if exists
            $oldImage = WebsiteSetting::where('key', 'seo_default_image')->first();
            if ($oldImage && $oldImage->value) {
                Storage::disk('public')->delete($oldImage->value);
            }

            WebsiteSetting::updateOrCreate(
                ['key' => 'seo_default_image'],
                ['group' => 'seo', 'value' => $path, 'type' => 'string', 'is_public' => true]
            );
        }

        return back()->with('success', 'SEO settings updated successfully.');
    }
}






