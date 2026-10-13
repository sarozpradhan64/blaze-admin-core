<x-layouts.admin title="{{ isset($affiliation) ? 'Edit' : 'Add' }} Affiliation">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.affiliations.index') }}">Affiliations</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($affiliation) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">{{ isset($affiliation) ? 'Edit' : 'Add' }} Affiliation</h2>
                <p class="text-muted-foreground mt-1 text-sm">Upload organization or association logo to display in the website affiliations section.</p>
            </div>
        </div>

        <form
            action="{{ isset($affiliation) ? route('admin.affiliations.update', $affiliation) : route('admin.affiliations.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($affiliation)) @method('PUT') @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Affiliation Details</x-ui.card-title>
                            <x-ui.card-description>Basic information about this affiliation or accreditation.</x-ui.card-description>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label for="name" required>Organization / Name</x-ui.field-label>
                                <x-ui.input
                                    id="name"
                                    name="name"
                                    placeholder="e.g. TAAN (Trekking Agencies' Association of Nepal)"
                                    value="{{ old('name', $affiliation->name ?? '') }}"
                                />
                                <x-ui.field-description>Used as title and image alt text for accessibility.</x-ui.field-description>
                                <x-ui.field-error name="name" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="link">Website Link (optional)</x-ui.field-label>
                                <x-ui.input
                                    id="link"
                                    name="link"
                                    type="url"
                                    placeholder="https://example.com"
                                    value="{{ old('link', $affiliation->link ?? '') }}"
                                />
                                <x-ui.field-description>Optional external URL if visitors can click the logo.</x-ui.field-description>
                                <x-ui.field-error name="link" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Logo Image</x-ui.card-title>
                            <x-ui.card-description>Affiliations display only logos.</x-ui.card-description>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <x-ui.field>
                                <x-ui.field-label for="logo" :required="!isset($affiliation)">Logo File</x-ui.field-label>
                                <x-ui.file-upload
                                    name="logo"
                                    accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                                    :current="old('logo', $affiliation->logo ?? null)"
                                />
                                <x-ui.field-description>Recommended: PNG or SVG with transparent background.</x-ui.field-description>
                                <x-ui.field-error name="logo" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Visibility</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-ui.label for="status" class="flex flex-col space-y-1">
                                    <span>Active (Visible)</span>
                                    <span class="text-muted-foreground text-xs font-normal">Show in the website footer. Uncheck to hide.</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="status"
                                    name="status"
                                    value="1"
                                    :checked="old('status', $affiliation->status ?? true)"
                                />
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.affiliations.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">{{ isset($affiliation) ? 'Update Affiliation' : 'Save Affiliation' }}</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
