<x-layouts.admin title="SEO Settings">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>SEO Settings</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <h2 class="mb-6 text-2xl font-bold tracking-tight">Default SEO Settings</h2>

        <form action="{{ route('admin.seo-settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <x-ui.card>
                <x-ui.card-content class="space-y-4 pt-6">
                    <x-ui.field>
                        <x-ui.field-label>Default Meta Title</x-ui.field-label>
                        <x-ui.input
                            name="seo_default_title"
                            value="{{ old('seo_default_title', $settings['seo_default_title'] ?? '') }}"
                            placeholder="E.g., Blaze AdminOpen source admin panel"
                        />
                        <p class="text-muted-foreground mt-1 text-xs">
                            This title will be used if a specific page does not have its own SEO title.
                        </p>
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label>Default Meta Description</x-ui.field-label>
                        <x-ui.textarea
                            name="seo_default_description"
                            rows="3"
                            placeholder="Enter default description..."
                        >
                            {{ old('seo_default_description', $settings['seo_default_description'] ?? '') }}</x-ui.textarea>
                        <p class="text-muted-foreground mt-1 text-xs">Recommended length: 150-160 characters.</p>
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label>Default Meta Keywords</x-ui.field-label>
                        <x-ui.input
                            name="seo_default_keywords"
                            value="{{ old('seo_default_keywords', $settings['seo_default_keywords'] ?? '') }}"
                            placeholder="construction, building, etc."
                        />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label>Default OG Image</x-ui.field-label>
                        @if (isset($settings['seo_default_image']) && $settings['seo_default_image'])
                            <div class="mb-2">
                                <img
                                    src="{{ Storage::url($settings['seo_default_image']) }}"
                                    alt="Default OG Image"
                                    class="h-32 rounded border object-cover"
                                />
                            </div>
                        @endif
                        <input
                            type="file"
                            name="seo_default_image"
                            class="file:bg-primary file:text-primary-foreground hover:file:bg-primary/90 block w-full text-sm text-slate-500 file:mr-4 file:rounded-md file:border-0 file:px-4 file:py-2 file:text-sm file:font-semibold"
                        />
                        <p class="text-muted-foreground mt-1 text-xs">Recommended size: 1200x630 pixels.</p>
                    </x-ui.field>
                </x-ui.card-content>

                <x-ui.card-footer class="flex justify-end border-t p-4">
                    <x-ui.button type="submit">Save SEO Settings</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-layouts.admin>
