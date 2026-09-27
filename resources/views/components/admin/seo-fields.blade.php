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

                @php
                    $currentOgImage = old('seo.og_image', $seo?->og_image ?? '');
                    $isUrl = Str::startsWith($currentOgImage, 'http');
                    $initialMode = ($currentOgImage && $isUrl) ? 'url' : 'upload';
                @endphp
                <div x-data="{ imageMode: '{{ $initialMode }}' }" class="space-y-4 rounded-lg border border-border p-4 bg-muted/20">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex flex-col gap-2">
                            <x-ui.field-label>OG Image</x-ui.field-label>
                            <x-ui.field-description>
                                Provide a direct URL to an image or upload one. Defaults to the record's featured image. Recommended: 1200×630px.
                            </x-ui.field-description>
                        </div>
                        <div class="flex shrink-0 items-center rounded-md border border-input p-1 bg-background">
                            <button type="button" @click="imageMode = 'upload'" :class="imageMode === 'upload' ? 'bg-muted shadow-sm' : 'hover:bg-muted/50'" class="px-3 py-1 text-xs font-medium rounded-sm transition-all">Upload</button>
                            <button type="button" @click="imageMode = 'url'" :class="imageMode === 'url' ? 'bg-muted shadow-sm' : 'hover:bg-muted/50'" class="px-3 py-1 text-xs font-medium rounded-sm transition-all">URL</button>
                        </div>
                    </div>

                    <div>
                        <div x-show="imageMode === 'url'" style="display: none;" x-transition>
                            <x-ui.input
                                id="seo_og_image"
                                name="seo[og_image]"
                                type="text"
                                value="{{ $currentOgImage }}"
                                placeholder="https://..."
                            />
                        </div>

                        <div x-show="imageMode === 'upload'" style="display: none;" x-transition>
                            <x-ui.file-upload
                                id="seo_og_image_file"
                                name="seo_og_image_file"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                :current="!$isUrl ? $currentOgImage : null"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
