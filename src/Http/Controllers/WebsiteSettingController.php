<?php

namespace Blaze\AdminCore\Http\Controllers;

use Blaze\AdminCore\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller
{
    public function index()
    {
        $keys = [
            'hero_title',
            'hero_text',
            'hero_stats',
            'hero_image',
            'who_are_we_title',
            'who_are_we_subtitle',
            'who_are_we_text',
            'who_are_we_bullets',
            'who_are_we_image',
            'about_header_title',
            'about_header_subtitle',
            'about_header_text',
            'about_header_image',
            'about_overview_subtitle',
            'about_overview_title',
            'about_overview_text',
            'about_overview_paragraphs',
            'about_overview_image_1',
            'about_overview_image_2',
            'about_values_title',
            'about_values_text',
            'about_values_items',
            'about_team_subtitle',
            'about_team_title',
            'about_team_text',
            'seo_default_title',
            'seo_default_description',
            'seo_default_keywords',
            'seo_default_image',
            'google_analytics',
            'admin_portal_title',
        ];

        $settings = WebsiteSetting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        return view('admin-core::website_settings.index', compact('settings'));
    }

    public function updateHomepage(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_text' => 'nullable|string',
            'hero_highlighted_text' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $title = $request->input('hero_title') ?? '';
                    $words = array_filter(array_map('trim', explode(',', $value)));
                    foreach ($words as $word) {
                        if (stripos($title, $word) === false) {
                            $fail("The highlighted word \"{$word}\" was not found in the hero title.");
                        }
                    }
                }
            ],
            'hero_image' => 'nullable|image|max:4096',
            'stats' => 'nullable|array|max:4',
            'stats.*.value' => 'nullable|string|max:50',
            'stats.*.label' => 'nullable|string|max:100',
            'who_are_we_title' => 'nullable|string|max:255',
            'who_are_we_subtitle' => 'nullable|string|max:255',
            'who_are_we_text' => 'nullable|string',
            'who_are_we_bullets' => 'nullable|array',
            'who_are_we_bullets.*' => 'nullable|string|max:255',
            'who_are_we_image' => 'nullable|image|max:2048',
        ]);

        WebsiteSetting::updateOrCreate(['key' => 'hero_title'], ['group' => 'homepage', 'value' => $validated['hero_title'] ?? null, 'type' => 'string']);
        if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->featureEnabled('hero_highlighted_text')) {
            WebsiteSetting::updateOrCreate(['key' => 'hero_highlighted_text'], ['group' => 'homepage', 'value' => $validated['hero_highlighted_text'] ?? null, 'type' => 'string']);
        }
        WebsiteSetting::updateOrCreate(['key' => 'hero_text'], ['group' => 'homepage', 'value' => $validated['hero_text'] ?? null,  'type' => 'text']);

        if ($request->hasFile('hero_image')) {
            $old = WebsiteSetting::where('key', 'hero_image')->first();
            if ($old?->value) {
                Storage::disk('public')->delete($old->value);
            }
            $path = $request->file('hero_image')->store('homepage', 'public');
            WebsiteSetting::updateOrCreate(['key' => 'hero_image'], ['group' => 'homepage', 'value' => $path, 'type' => 'string']);
        }

        $stats = collect($validated['stats'] ?? [])
            ->filter(fn($s) => filled($s['value'] ?? null) || filled($s['label'] ?? null))
            ->values()
            ->toArray();

        WebsiteSetting::updateOrCreate(['key' => 'hero_stats'], ['group' => 'homepage', 'value' => json_encode($stats), 'type' => 'json']);

        foreach (['who_are_we_title', 'who_are_we_subtitle', 'who_are_we_text'] as $key) {
            WebsiteSetting::updateOrCreate(['key' => $key], ['group' => 'homepage', 'value' => $validated[$key] ?? null, 'type' => 'string']);
        }

        $bullets = collect($validated['who_are_we_bullets'] ?? [])
            ->filter(fn($b) => filled($b))
            ->values()
            ->toArray();
        WebsiteSetting::updateOrCreate(['key' => 'who_are_we_bullets'], ['group' => 'homepage', 'value' => json_encode($bullets), 'type' => 'json']);

        if ($request->hasFile('who_are_we_image')) {
            $old = WebsiteSetting::where('key', 'who_are_we_image')->first();
            if ($old?->value) {
                Storage::disk('public')->delete($old->value);
            }
            $path = $request->file('who_are_we_image')->store('homepage', 'public');
            WebsiteSetting::updateOrCreate(['key' => 'who_are_we_image'], ['group' => 'homepage', 'value' => $path, 'type' => 'string']);
        }

        return back()->with('success', 'Homepage settings updated.')->with('active_tab', 'homepage');
    }

    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'about_header_title' => 'nullable|string|max:255',
            'about_header_subtitle' => 'nullable|string|max:255',
            'about_header_text' => 'nullable|string',
            'about_header_image' => 'nullable|image|max:4096',
            'about_overview_subtitle' => 'nullable|string|max:255',
            'about_overview_title' => 'nullable|string|max:255',
            'about_overview_text' => 'nullable|string',
            'about_overview_paragraphs' => 'nullable|array',
            'about_overview_paragraphs.*' => 'nullable|string',
            'about_overview_image_1' => 'nullable|image|max:4096',
            'about_overview_image_2' => 'nullable|image|max:4096',
            'about_values_title' => 'nullable|string|max:255',
            'about_values_text' => 'nullable|string',
            'about_values_items' => 'nullable|array',
            'about_values_items.*.icon' => 'nullable|string|max:100',
            'about_values_items.*.title' => 'nullable|string|max:255',
            'about_values_items.*.text' => 'nullable|string',
            'about_team_subtitle' => 'nullable|string|max:255',
            'about_team_title' => 'nullable|string|max:255',
            'about_team_text' => 'nullable|string',
        ]);

        $strings = [
            'about_header_title',
            'about_header_subtitle',
            'about_header_text',
            'about_overview_subtitle',
            'about_overview_title',
            'about_overview_text',
            'about_values_title',
            'about_values_text',
            'about_team_subtitle',
            'about_team_title',
            'about_team_text',
        ];
        foreach ($strings as $key) {
            WebsiteSetting::updateOrCreate(['key' => $key], ['group' => 'about', 'value' => $validated[$key] ?? null, 'type' => 'string']);
        }

        $paragraphs = collect($validated['about_overview_paragraphs'] ?? [])->filter(fn($p) => filled($p))->values()->toArray();
        WebsiteSetting::updateOrCreate(['key' => 'about_overview_paragraphs'], ['group' => 'about', 'value' => json_encode($paragraphs), 'type' => 'json']);

        $valuesItems = collect($validated['about_values_items'] ?? [])
            ->filter(fn($v) => filled($v['title'] ?? null))
            ->values()->toArray();
        WebsiteSetting::updateOrCreate(['key' => 'about_values_items'], ['group' => 'about', 'value' => json_encode($valuesItems), 'type' => 'json']);

        foreach (['about_header_image', 'about_overview_image_1', 'about_overview_image_2'] as $key) {
            if ($request->hasFile($key)) {
                $old = WebsiteSetting::where('key', $key)->first();
                if ($old?->value) {
                    Storage::disk('public')->delete($old->value);
                }
                $path = $request->file($key)->store('about', 'public');
                WebsiteSetting::updateOrCreate(['key' => $key], ['group' => 'about', 'value' => $path, 'type' => 'string']);
            }
        }

        return back()->with('success', 'About page settings updated.')->with('active_tab', 'about');
    }

    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'admin_portal_title' => 'nullable|string|max:255',
        ]);

        WebsiteSetting::updateOrCreate(
            ['key' => 'admin_portal_title'],
            ['group' => 'system', 'value' => $validated['admin_portal_title'] ?? null, 'type' => 'string']
        );

        return back()->with('success', 'System settings updated.')->with('active_tab', 'system');
    }

    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'seo_default_title' => 'nullable|string|max:255',
            'seo_default_description' => 'nullable|string',
            'seo_default_keywords' => 'nullable|string',
            'seo_default_image' => 'nullable|image|max:2048',
            'google_analytics' => 'nullable|string|max:255',
        ]);

        foreach (['seo_default_title', 'seo_default_description', 'seo_default_keywords', 'google_analytics'] as $key) {
            WebsiteSetting::updateOrCreate(
                ['key' => $key],
                ['group' => 'seo', 'value' => $validated[$key] ?? null, 'type' => 'string']
            );
        }

        if ($request->hasFile('seo_default_image')) {
            $old = WebsiteSetting::where('key', 'seo_default_image')->first();
            if ($old?->value) {
                Storage::disk('public')->delete($old->value);
            }
            $path = $request->file('seo_default_image')->store('seo', 'public');
            WebsiteSetting::updateOrCreate(
                ['key' => 'seo_default_image'],
                ['group' => 'seo', 'value' => $path, 'type' => 'string']
            );
        }

        return back()->with('success', 'SEO settings updated.')->with('active_tab', 'seo');
    }
}
