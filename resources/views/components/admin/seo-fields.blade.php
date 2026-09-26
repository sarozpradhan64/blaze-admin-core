@props(['model' => null, 'defaults' => null])

@php
    $seo = $model?->seo;
    $defaultTitle = $defaults['seo_default_title'] ?? '';
    $defaultDesc = $defaults['seo_default_description'] ?? '';
    $hasDefaults = $defaults && ($defaultTitle || $defaultDesc);
@endphp

<div
    x-data="{
    metaTitle: '{{ old('seo.meta_title', $seo?->meta_title ?? '') }}',
    metaDesc: '{{ old('seo.meta_description', $seo?->meta_description ?? '') }}',
    fillDefaults() {
        this.metaTitle = @js($defaultTitle);
        this.metaDesc = @js($defaultDesc);
    }
}"
>
    <x-ui.card>
        <x-ui.card-header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-lucide-search class="text-muted-foreground size-4" />
                    <x-ui.card-title>SEO & Metadata</x-ui.card-title>
                </div>
                @if ($hasDefaults)
                    <x-ui.button type="button" variant="outline" size="sm" @click="fillDefaults()">
                        <x-lucide-wand class="mr-1.5 size-3.5" />
                        Auto-fill from defaults
                    </x-ui.button>
                @endif
            </div>
            <x-ui.card-description>
                Manage how this page appears in search engines and social media previews.</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content class="space-y-6">
            <div class="space-y-4">
                <h4 class="text-muted-foreground text-sm font-semibold tracking-wide uppercase">Search Engine</h4>

                <x-ui.field>
                    <x-ui.field-label for="seo_meta_title">Meta Title</x-ui.field-label>
                    <x-ui.input
                        id="seo_meta_title"
                        name="seo[meta_title]"
                        placeholder="Defaults to page title if empty"
                        maxlength="70"
                        x-model="metaTitle"
                    />
                    <x-ui.field-description>
                        <span x-text="metaTitle.length"></span>/70 characters recommended.
                    </x-ui.field-description>
                </x-ui.field>

                <x-ui.field>
                    <x-ui.field-label for="seo_meta_description">Meta Description</x-ui.field-label>
                    <x-ui.textarea
                        id="seo_meta_description"
                        name="seo[meta_description]"
                        rows="3"
                        placeholder="Defaults to excerpt if empty"
                        maxlength="160"
                        x-model="metaDesc"
                    ></x-ui.textarea>
                    <x-ui.field-description>
                        <span x-text="metaDesc.length"></span>/160 characters recommended.
                    </x-ui.field-description>
                </x-ui.field>
            </div>

            <hr class="border-border" />

            <div class="space-y-4">
                <h4 class="text-muted-foreground text-sm font-semibold tracking-wide uppercase">
                    Open Graph (Facebook / LinkedIn)
                </h4>

                <x-ui.field>
                    <x-ui.field-label for="seo_og_title">OG Title</x-ui.field-label>
                    <x-ui.input
                        id="seo_og_title"
                        name="seo[og_title]"
                        value="{{ old('seo.og_title', $seo?->og_title ?? '') }}"
                        placeholder="Defaults to Meta Title"
                    />
                </x-ui.field>

                <x-ui.field>
                    <x-ui.field-label for="seo_og_description">OG Description</x-ui.field-label>
                    <x-ui.textarea
                        id="seo_og_description"
                        name="seo[og_description]"
                        rows="2"
                        placeholder="Defaults to Meta Description"
                    >
                        {{ old('seo.og_description', $seo?->og_description ?? '') }}</x-ui.textarea>
                </x-ui.field>

                <x-ui.field>
                    <x-ui.field-label for="seo_og_image">OG Image URL</x-ui.field-label>
                    <x-ui.input
                        id="seo_og_image"
                        name="seo[og_image]"
                        type="url"
                        value="{{ old('seo.og_image', $seo?->og_image ?? '') }}"
                        placeholder="https://... (recommended: 1200×630px)"
                    />
                    <x-ui.field-description>Defaults to the record's featured image.</x-ui.field-description>
                </x-ui.field>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
