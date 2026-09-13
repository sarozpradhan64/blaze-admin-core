<x-layouts.admin title="Company Info">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Company Info</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Company Information</h2>
            <p class="text-sm text-muted-foreground mt-1">Manage your contact details and social media links.</p>
        </div>
    </div>

    <x-ui.tabs :value="request('tab', 'contact')" class="w-full">
        <x-ui.tabs-list>
            <x-ui.tabs-trigger value="contact" href="{{ route('admin.company-info.index') }}?tab=contact">
                <x-lucide-map-pin class="size-4 mr-1.5" />
                Contact Information
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="social-links" href="{{ route('admin.company-info.index') }}?tab=social-links">
                <x-lucide-share-2 class="size-4 mr-1.5" />
                Social Links
            </x-ui.tabs-trigger>
        </x-ui.tabs-list>

        {{-- ─────────────────────────────────────────── --}}
        {{-- TAB 1: CONTACT INFORMATION                  --}}
        {{-- ─────────────────────────────────────────── --}}
        <x-ui.tabs-content value="contact" class="mt-4">
            @if (session('success') && request('tab', 'contact') === 'contact')
                <div
                    class="flex items-center gap-2 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-600 dark:text-green-400 mb-4">
                    <x-lucide-check-circle class="size-4 shrink-0" />
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.company-info.contact.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Contact Details</x-ui.card-title>
                        <x-ui.card-description>This information is used on the website contact page and
                            footer.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4 pt-2">

                        {{-- Company Name --}}
                        <x-ui.field>
                            <x-ui.field-label for="company_name">Company Name</x-ui.field-label>
                            <x-ui.input id="company_name" name="company_name"
                                value="{{ old('company_name', $contact->company_name ?? '') }}" />
                            @error('company_name')
                                <x-ui.field-error>{{ $message }}</x-ui.field-error>
                            @enderror
                        </x-ui.field>

                        {{-- Phone + Secondary Phone --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <x-ui.field>
                                <x-ui.field-label for="phone">Phone</x-ui.field-label>
                                <x-ui.input id="phone" name="phone"
                                    value="{{ old('phone', $contact->phone ?? '') }}">
                                    <x-slot:leading><x-lucide-phone class="size-4" /></x-slot:leading>
                                </x-ui.input>
                                @error('phone')
                                    <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                @enderror
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label for="secondary_phone">Secondary Phone <span
                                        class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                                <x-ui.input id="secondary_phone" name="secondary_phone"
                                    value="{{ old('secondary_phone', $contact->secondary_phone ?? '') }}">
                                    <x-slot:leading><x-lucide-phone class="size-4" /></x-slot:leading>
                                </x-ui.input>
                                @error('secondary_phone')
                                    <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                @enderror
                            </x-ui.field>
                        </div>

                        {{-- Email + Secondary Email --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <x-ui.field>
                                <x-ui.field-label for="email">Email</x-ui.field-label>
                                <x-ui.input type="email" id="email" name="email"
                                    value="{{ old('email', $contact->email ?? '') }}">
                                    <x-slot:leading><x-lucide-mail class="size-4" /></x-slot:leading>
                                </x-ui.input>
                                @error('email')
                                    <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                @enderror
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label for="secondary_email">Secondary Email <span
                                        class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                                <x-ui.input type="email" id="secondary_email" name="secondary_email"
                                    value="{{ old('secondary_email', $contact->secondary_email ?? '') }}">
                                    <x-slot:leading><x-lucide-mail class="size-4" /></x-slot:leading>
                                </x-ui.input>
                                @error('secondary_email')
                                    <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                @enderror
                            </x-ui.field>
                        </div>

                        {{-- WhatsApp --}}
                        <x-ui.field>
                            <x-ui.field-label for="whatsapp">WhatsApp <span
                                    class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                            <x-ui.input id="whatsapp" name="whatsapp" placeholder="+1 234 567 8900"
                                value="{{ old('whatsapp', $contact->whatsapp ?? '') }}">
                                <x-slot:leading><x-lucide-message-circle class="size-4" /></x-slot:leading>
                            </x-ui.input>
                            @error('whatsapp')
                                <x-ui.field-error>{{ $message }}</x-ui.field-error>
                            @enderror
                        </x-ui.field>

                        {{-- Address --}}
                        <x-ui.field>
                            <x-ui.field-label for="address">Address</x-ui.field-label>
                            <x-ui.textarea id="address" name="address"
                                rows="3">{{ old('address', $contact->address ?? '') }}</x-ui.textarea>
                            @error('address')
                                <x-ui.field-error>{{ $message }}</x-ui.field-error>
                            @enderror
                        </x-ui.field>

                        {{-- Working Hours --}}
                        <x-ui.field>
                            <x-ui.field-label for="working_hours">Working Hours <span
                                    class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                            <x-ui.textarea id="working_hours" name="working_hours" rows="2"
                                placeholder="Mon–Fri: 8:00am – 6:00pm&#10;Sat: 9:00am – 1:00pm">{{ old('working_hours', $contact->working_hours ?? '') }}</x-ui.textarea>
                            @error('working_hours')
                                <x-ui.field-error>{{ $message }}</x-ui.field-error>
                            @enderror
                        </x-ui.field>

                        {{-- Latitude + Longitude --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <x-ui.field>
                                <x-ui.field-label for="latitude">Latitude <span
                                        class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                                <x-ui.input type="number" step="any" id="latitude" name="latitude"
                                    placeholder="-90 to 90" value="{{ old('latitude', $contact->latitude ?? '') }}" />
                                @error('latitude')
                                    <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                @enderror
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label for="longitude">Longitude <span
                                        class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                                <x-ui.input type="number" step="any" id="longitude" name="longitude"
                                    placeholder="-180 to 180"
                                    value="{{ old('longitude', $contact->longitude ?? '') }}" />
                                @error('longitude')
                                    <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                @enderror
                            </x-ui.field>
                        </div>

                        {{-- Google Maps URL --}}
                        <x-ui.field>
                            <x-ui.field-label for="google_maps_url">Google Maps URL <span
                                    class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                            <x-ui.input type="url" id="google_maps_url" name="google_maps_url"
                                placeholder="https://maps.google.com/..."
                                value="{{ old('google_maps_url', $contact->google_maps_url ?? '') }}">
                                <x-slot:leading><x-lucide-map class="size-4" /></x-slot:leading>
                            </x-ui.input>
                            @error('google_maps_url')
                                <x-ui.field-error>{{ $message }}</x-ui.field-error>
                            @enderror
                        </x-ui.field>

                        <div class="border-t pt-6">
                            <div class="mb-4">
                                <h3 class="text-base font-semibold">Branding</h3>
                                <p class="text-sm text-muted-foreground">Upload the logo and browser icon used across
                                    the public website.</p>
                            </div>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <x-ui.field>
                                    <x-ui.field-label for="logo">Company Logo</x-ui.field-label>
                                    <x-ui.input type="file" id="logo" name="logo"
                                        accept=".jpg,.jpeg,.png,.webp,.svg" />
                                    @if (!empty($branding['logo']))
                                        <div class="mt-3 flex h-20 items-center rounded-md border bg-muted/30 p-3">
                                            <img src="{{ Storage::url($branding['logo']) }}"
                                                alt="Current company logo"
                                                class="max-h-full max-w-full object-contain">
                                        </div>
                                    @endif
                                    <p class="text-xs text-muted-foreground">PNG, JPG, WEBP, or SVG. Max 4 MB.</p>
                                    @error('logo')
                                        <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                    @enderror
                                </x-ui.field>

                                <x-ui.field>
                                    <x-ui.field-label for="favicon">Favicon</x-ui.field-label>
                                    <x-ui.input type="file" id="favicon" name="favicon"
                                        accept=".ico,.png,.jpg,.jpeg,.webp,.svg" />
                                    @if (!empty($branding['favicon']))
                                        <div class="mt-3 flex h-20 items-center rounded-md border bg-muted/30 p-3">
                                            <img src="{{ Storage::url($branding['favicon']) }}" alt="Current favicon"
                                                class="size-12 object-contain">
                                        </div>
                                    @endif
                                    <p class="text-xs text-muted-foreground">ICO, PNG, WEBP, or SVG. Max 2 MB.</p>
                                    @error('favicon')
                                        <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                    @enderror
                                </x-ui.field>
                            </div>
                        </div>

                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t p-4 flex justify-end">
                        <x-ui.button type="submit">
                            <x-slot:before><x-lucide-save class="size-4" /></x-slot:before>
                            Save Contact Info
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-ui.tabs-content>

        {{-- ─────────────────────────────────────────── --}}
        {{-- TAB 2: SOCIAL LINKS                         --}}
        {{-- ─────────────────────────────────────────── --}}
        <x-ui.tabs-content value="social-links" class="mt-4">
            @if (session('success') && request('tab') === 'social-links')
                <div
                    class="flex items-center gap-2 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-600 dark:text-green-400 mb-4">
                    <x-lucide-check-circle class="size-4 shrink-0" />
                    {{ session('success') }}
                </div>
            @endif

            <div x-data="{ showForm: false }">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">Social Links</h3>
                        <p class="text-sm text-muted-foreground">Drag rows to reorder.</p>
                    </div>
                    <x-ui.button size="sm" type="button" @click="showForm = !showForm">
                        <x-slot:before>
                            <x-lucide-plus class="size-4" x-show="!showForm" />
                            <x-lucide-x class="size-4" x-show="showForm" />
                        </x-slot:before>
                        <span x-text="showForm ? 'Cancel' : 'Add Social Link'"></span>
                    </x-ui.button>
                </div>

                {{-- Add Form --}}
                <div x-show="showForm" x-collapse class="mb-4">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>New Social Link</x-ui.card-title>
                        </x-ui.card-header>
                        <form action="{{ route('admin.company-info.social-links.store') }}" method="POST">
                            @csrf
                            <x-ui.card-content class="space-y-4 pt-2">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <x-ui.field>
                                        <x-ui.field-label for="add_platform">Platform</x-ui.field-label>
                                        <x-ui.input id="add_platform" name="platform"
                                            placeholder="e.g. Facebook, Instagram" value="{{ old('platform') }}" />
                                        @error('platform')
                                            <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                        @enderror
                                    </x-ui.field>
                                    <x-ui.field>
                                        <x-ui.field-label for="add_icon">Icon <span
                                                class="text-muted-foreground font-normal">(optional)</span></x-ui.field-label>
                                        <x-ui.icon-picker name="icon" :value="old('icon')" />
                                        @error('icon')
                                            <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                        @enderror
                                    </x-ui.field>
                                </div>
                                <x-ui.field>
                                    <x-ui.field-label for="add_url">URL</x-ui.field-label>
                                    <x-ui.input type="url" id="add_url" name="url"
                                        placeholder="https://facebook.com/yourpage" value="{{ old('url') }}">
                                        <x-slot:leading><x-lucide-link class="size-4" /></x-slot:leading>
                                    </x-ui.input>
                                    @error('url')
                                        <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                    @enderror
                                </x-ui.field>
                                <x-ui.field>
                                    <div class="flex items-center justify-between">
                                        <x-ui.label for="add_status" class="flex flex-col space-y-1">
                                            <span>Active</span>
                                            <span class="font-normal text-xs text-muted-foreground">Show on
                                                website</span>
                                        </x-ui.label>
                                        <x-ui.switch id="add_status" name="status" value="1"
                                            :checked="true" />
                                    </div>
                                </x-ui.field>
                            </x-ui.card-content>
                            <x-ui.card-footer class="border-t p-4 flex justify-end gap-2">
                                <x-ui.button variant="outline" type="button"
                                    @click="showForm = false">Cancel</x-ui.button>
                                <x-ui.button type="submit">
                                    <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
                                    Add Link
                                </x-ui.button>
                            </x-ui.card-footer>
                        </form>
                    </x-ui.card>
                </div>

                {{-- Draggable list --}}
                <x-ui.card>
                    @if ($socialLinks->isEmpty())
                        <x-ui.card-content class="py-10 text-center text-muted-foreground">
                            <div class="flex flex-col items-center gap-2">
                                <x-lucide-share-2 class="size-8 opacity-30" />
                                <p class="text-sm">No social links added yet.</p>
                            </div>
                        </x-ui.card-content>
                    @else
                        <div x-data="sortableList({
                            reorderUrl: '{{ route('admin.company-info.social-links.reorder') }}',
                            csrfToken: '{{ csrf_token() }}'
                        })" class="divide-y divide-border">
                            @foreach ($socialLinks as $link)
                                <div data-id="{{ $link->id }}" class="flex items-center gap-3 px-4 py-3">
                                    {{-- Drag handle --}}
                                    <button type="button" data-drag-handle
                                        class="cursor-grab text-muted-foreground hover:text-foreground shrink-0">
                                        <x-lucide-grip-vertical class="size-4" />
                                    </button>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-sm">{{ $link->platform }}</span>
                                            <x-ui.badge variant="{{ $link->status ? 'default' : 'secondary' }}"
                                                class="text-xs">
                                                {{ $link->status ? 'Active' : 'Hidden' }}
                                            </x-ui.badge>
                                        </div>
                                        <a href="{{ $link->url }}" target="_blank"
                                            class="text-xs text-muted-foreground hover:text-primary truncate block">
                                            {{ $link->url }}
                                        </a>
                                    </div>

                                    {{-- Icon preview --}}
                                    @if ($link->icon)
                                        @php
                                            $svgPath = base_path(
                                                'vendor/mallardduck/blade-lucide-icons/resources/svg/icons/' .
                                                    $link->icon .
                                                    '.svg',
                                            );
                                        @endphp
                                        @if (file_exists($svgPath))
                                            <span
                                                class="shrink-0 [&_svg]:size-4 [&_svg]:text-muted-foreground hidden sm:flex items-center">
                                                {!! file_get_contents($svgPath) !!}
                                            </span>
                                        @endif
                                    @endif

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1 shrink-0">
                                        <x-ui.dialog>
                                            <x-ui.dialog-trigger>
                                                <x-ui.button variant="ghost" size="icon" type="button">
                                                    <x-lucide-edit class="size-4" />
                                                </x-ui.button>
                                            </x-ui.dialog-trigger>
                                            <x-ui.dialog-content class="max-w-lg">
                                                <x-ui.dialog-header>
                                                    <x-ui.dialog-title>Edit Social Link</x-ui.dialog-title>
                                                    <x-ui.dialog-description>Update the details for
                                                        {{ $link->platform }}.</x-ui.dialog-description>
                                                </x-ui.dialog-header>
                                                <form
                                                    action="{{ route('admin.company-info.social-links.update', $link) }}"
                                                    method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="space-y-4 py-2">
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <x-ui.field>
                                                                <x-ui.field-label>Platform</x-ui.field-label>
                                                                <x-ui.input name="platform"
                                                                    value="{{ $link->platform }}" />
                                                            </x-ui.field>
                                                            <x-ui.field>
                                                                <x-ui.field-label>Icon</x-ui.field-label>
                                                                <x-ui.icon-picker name="icon" :value="$link->icon" />
                                                            </x-ui.field>
                                                        </div>
                                                        <x-ui.field>
                                                            <x-ui.field-label>URL</x-ui.field-label>
                                                            <x-ui.input type="url" name="url"
                                                                value="{{ $link->url }}" />
                                                        </x-ui.field>
                                                        <div class="flex items-center justify-between">
                                                            <x-ui.label for="edit_status_{{ $link->id }}"
                                                                class="flex flex-col space-y-1">
                                                                <span>Active</span>
                                                                <span
                                                                    class="font-normal text-xs text-muted-foreground">Show
                                                                    on website</span>
                                                            </x-ui.label>
                                                            <x-ui.switch id="edit_status_{{ $link->id }}"
                                                                name="status" value="1" :checked="$link->status" />
                                                        </div>
                                                    </div>
                                                    <x-ui.dialog-footer class="mt-4">
                                                        <x-ui.button variant="outline" type="button"
                                                            data-dialog-close>Cancel</x-ui.button>
                                                        <x-ui.button type="submit">Save Changes</x-ui.button>
                                                    </x-ui.dialog-footer>
                                                </form>
                                            </x-ui.dialog-content>
                                        </x-ui.dialog>

                                        <form action="{{ route('admin.company-info.social-links.destroy', $link) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete {{ $link->platform }}?')">
                                            @csrf @method('DELETE')
                                            <x-ui.button type="submit" variant="ghost" size="icon"
                                                class="text-destructive hover:text-destructive hover:bg-destructive/10">
                                                <x-lucide-trash-2 class="size-4" />
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-ui.card>
            </div>
        </x-ui.tabs-content>
    </x-ui.tabs>
</x-layouts.admin>
