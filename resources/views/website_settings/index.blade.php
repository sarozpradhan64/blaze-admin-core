<x-layouts.admin title="Settings">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Settings</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
        <p class="text-muted-foreground mt-1 text-sm">Manage your website configuration.</p>
    </div>

    <x-ui.tabs value="{{ session('active_tab', 'homepage') }}" class="w-full">
        <x-ui.tabs-list>
            <x-ui.tabs-trigger value="homepage">
                <x-lucide-layout class="mr-2 size-4" />
                Homepage
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="about">
                <x-lucide-building-2 class="mr-2 size-4" />
                About Us
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="system">
                <x-lucide-settings class="mr-2 size-4" />
                System
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="seo">
                <x-lucide-search class="mr-2 size-4" />
                SEO
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="subpages">
                <x-lucide-layout-template class="mr-2 size-4" />
                Subpages
            </x-ui.tabs-trigger>
        </x-ui.tabs-list>

        {{-- ── Homepage Tab ── --}}
        <x-ui.tabs-content value="homepage">
            <div class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start w-full">
                {{-- Left Side: Hero and Who We Are Settings --}}
                <div class="lg:col-span-5 space-y-6">
                    <form
                        action="{{ route('admin.settings.homepage.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6"
                    >
                        @csrf
                        @method('PUT')

                {{-- Hero Section --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-monitor class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Hero Section</x-ui.card-title>
                                <x-ui.card-description>
                                    Main banner title and description shown at the top of the
                                    homepage.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <x-ui.field>
                            <x-ui.field-label>Hero Title</x-ui.field-label>
                            <x-ui.input
                                name="hero_title"
                                value="{{ old('hero_title', $settings['hero_title'] ?? '') }}"
                                placeholder="E.g., Building Excellence, Delivering Perfection"
                            />
                        </x-ui.field>
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->featureEnabled('hero_highlighted_text'))
                            <x-ui.field>
                                <x-ui.field-label>Hero Highlighted Text</x-ui.field-label>
                                <x-ui.input
                                    name="hero_highlighted_text"
                                    value="{{ old('hero_highlighted_text', $settings['hero_highlighted_text'] ?? '') }}"
                                    placeholder="E.g., Excellence, Perfection (Must be words from Hero Title)"
                                />
                                @error('hero_highlighted_text')
                                    <x-ui.field-error>{{ $message }}</x-ui.field-error>
                                @enderror
                                <x-ui.field-description>
                                    Specify comma-separated words from the title to be
                                    highlighted.</x-ui.field-description>
                            </x-ui.field>
                        @endif
                        <x-ui.field>
                            <x-ui.field-label>Hero Text</x-ui.field-label>
                            <x-ui.textarea
                                name="hero_text"
                                rows="3"
                                placeholder="Short description shown below the hero title..."
                            >
                                {{ old('hero_text', $settings['hero_text'] ?? '') }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.field-label>Background Image</x-ui.field-label>
                            @if (! empty($settings['hero_image']))
                                <img
                                    src="{{ Storage::url($settings['hero_image']) }}"
                                    alt="Hero Background"
                                    class="border-border mb-2 h-40 w-full rounded-md border object-cover"
                                />
                            @endif
                            <x-ui.file-upload name="hero_image" accept="image/*" />
                            <x-ui.field-description>
                                Recommended size: 2000×1200 px. Replaces the default background
                                photo.</x-ui.field-description>
                        </x-ui.field>
                    </x-ui.card-content>
                </x-ui.card>

                       {{-- Hero Stats (dynamic) --}}
                @php
                    $heroStats = json_decode($settings['hero_stats'] ?? '[]', true) ?: [];
                    if (empty($heroStats)) {
                        $heroStats = [
                            ['value' => '500+', 'label' => 'Happy Clients'],
                            ['value' => '100%', 'label' => 'Success Rate'],
                            ['value' => '', 'label' => 'Eco-Friendly'],
                            ['value' => '24/7', 'label' => 'Rescue']
                        ];
                    }
                    $statIcons = ['star', 'shield-check', 'leaf', 'siren'];
                    foreach ($heroStats as $index => &$stat) {
                        if (empty($stat['icon'])) {
                            $stat['icon'] = $statIcons[$index % count($statIcons)];
                        }
                    }
                    $statsJson = json_encode(old('stats', $heroStats));
                @endphp

                <div x-data="heroStats({{ $statsJson }})">
                    <x-ui.card>
                        <x-ui.card-header>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <x-lucide-bar-chart-2 class="text-muted-foreground size-5" />
                                    <div>
                                        <x-ui.card-title>Hero Section Stats</x-ui.card-title>
                                        <x-ui.card-description>
                                            Dynamic statistics displayed in the hero section.</x-ui.card-description>
                                    </div>
                                </div>
                                <x-ui.button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    @click="addStat"
                                    x-bind:disabled="stats.length >= 6"
                                >
                                    <x-slot:before>
                                        <x-lucide-plus class="size-4" />
                                    </x-slot:before>
                                    Add Stat
                                </x-ui.button>
                            </div>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="space-y-3" x-data="{ dragging: null, dragOver: null }">
                                <template x-if="stats.length === 0">
                                    <p class="text-muted-foreground py-4 text-center text-sm">
                                        No stats yet. Click "Add Stat" to add one.
                                    </p>
                                </template>

                                <template x-for="(stat, i) in stats" :key="i">
                                    <div 
                                        class="border-border bg-muted/30 flex items-center gap-3 rounded-lg border px-4 py-3 transition-colors relative"
                                        :class="{'opacity-40': dragging === i, 'border-primary': dragOver === i}"
                                        draggable="true"
                                        @dragstart="dragging = i; $event.dataTransfer.effectAllowed='move'"
                                        @dragover.prevent="dragOver = i"
                                        @dragleave.prevent="dragOver = null"
                                        @drop.prevent="
                                            if (dragging !== null && dragging !== i) {
                                                let item = stats.splice(dragging, 1)[0];
                                                stats.splice(i, 0, item);
                                            }
                                            dragging = null;
                                            dragOver = null;
                                        "
                                        @dragend="dragging = null; dragOver = null"
                                    >
                                        <div class="cursor-grab text-muted-foreground hover:text-foreground active:cursor-grabbing">
                                            <x-lucide-grip-vertical class="size-5" />
                                        </div>
                                        <div
                                            class="bg-primary/10 text-primary flex size-7 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                                            x-text="i + 1"
                                        ></div>
                                        <div class="grid flex-1 grid-cols-3 gap-3">
                                            <div>
                                                <label class="text-muted-foreground mb-1 block text-xs">Icon</label>
                                                <x-ui.icon-picker x-model="stat.icon" class="h-9" />
                                                <input type="hidden" :name="`stats[${i}][icon]`" :value="stat.icon" />
                                            </div>
                                            <div>
                                                <label class="text-muted-foreground mb-1 block text-xs">Value</label>
                                                <input
                                                    type="text"
                                                    :name="`stats[${i}][value]`"
                                                    x-model="stat.value"
                                                    placeholder="E.g., 15+"
                                                    class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:outline-none"
                                                />
                                            </div>
                                            <div>
                                                <label class="text-muted-foreground mb-1 block text-xs">Label</label>
                                                <input
                                                    type="text"
                                                    :name="`stats[${i}][label]`"
                                                    x-model="stat.label"
                                                    placeholder="E.g., Years Experience"
                                                    class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:outline-none"
                                                />
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            @click="removeStat(i)"
                                            class="text-destructive hover:bg-destructive/10 inline-flex size-8 shrink-0 items-center justify-center rounded-md transition-colors"
                                        >
                                            <x-lucide-trash-2 class="size-4" />
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </x-ui.card-content>
                        <x-ui.card-footer class="flex justify-end border-t pt-4">
                            <x-ui.button type="submit">
                                <x-slot:before>
                                    <x-lucide-save class="size-4" />
                                </x-slot:before>
                                Save Homepage Settings
                            </x-ui.button>
                        </x-ui.card-footer>
                    </x-ui.card>
                </div>

                {{-- Who Are We Section --}}
                @php
                    $whoAreWeBullets = json_decode($settings['who_are_we_bullets'] ?? '[]', true) ?: [];
                    $bulletsJson = json_encode(old('who_are_we_bullets', $whoAreWeBullets));
                @endphp

                <div x-data="whoAreWe({{ $bulletsJson }})">
                    <x-ui.card>
                        <x-ui.card-header>
                            <div class="flex items-center gap-2">
                                <x-lucide-users class="text-muted-foreground size-5" />
                                <div>
                                    <x-ui.card-title>Who Are We Section</x-ui.card-title>
                                    <x-ui.card-description>
                                        The "About" preview section on the homepage.</x-ui.card-description>
                                </div>
                            </div>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label>Subtitle (e.g. "Who We Are")</x-ui.field-label>
                                    <x-ui.input
                                        name="who_are_we_subtitle"
                                        value="{{ old('who_are_we_subtitle', $settings['who_are_we_subtitle'] ?? '') }}"
                                        placeholder="Who We Are"
                                    />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label>Title</x-ui.field-label>
                                    <x-ui.input
                                        name="who_are_we_title"
                                        value="{{ old('who_are_we_title', $settings['who_are_we_title'] ?? '') }}"
                                        placeholder="Setting the Standard in Construction Excellence"
                                    />
                                </x-ui.field>
                            </div>
                            <x-ui.field>
                                <x-ui.field-label>Body Text</x-ui.field-label>
                                <x-ui.textarea
                                    name="who_are_we_text"
                                    rows="3"
                                >
                                    {{ old('who_are_we_text', $settings['who_are_we_text'] ?? '') }}</x-ui.textarea>
                            </x-ui.field>

                            {{-- Bullet points --}}
                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label class="text-sm font-medium">Bullet Points</label>
                                    <x-ui.button type="button" variant="outline" size="sm" @click="addBullet">
                                        <x-slot:before>
                                            <x-lucide-plus class="size-4" />
                                        </x-slot:before>
                                        Add Bullet
                                    </x-ui.button>
                                </div>
                                <div class="space-y-2">
                                    <template x-if="bullets.length === 0">
                                        <p class="text-muted-foreground py-3 text-center text-sm">No bullets yet.</p>
                                    </template>
                                    <template x-for="(bullet, i) in bullets" :key="i">
                                        <div class="flex items-center gap-2">
                                            <input
                                                type="text"
                                                :name="`who_are_we_bullets[${i}]`"
                                                x-model="bullets[i]"
                                                placeholder="E.g., Uncompromising safety standards on every site."
                                                class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:outline-none"
                                            />
                                            <button
                                                type="button"
                                                @click="removeBullet(i)"
                                                class="text-destructive hover:bg-destructive/10 inline-flex size-8 shrink-0 items-center justify-center rounded-md transition-colors"
                                            >
                                                <x-lucide-trash-2 class="size-4" />
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Image --}}
                            <x-ui.field>
                                <x-ui.field-label>Section Image</x-ui.field-label>
                                @if (! empty($settings['who_are_we_image']))
                                    <img
                                        src="{{ Storage::url($settings['who_are_we_image']) }}"
                                        alt="Who Are We"
                                        class="border-border mb-2 h-40 rounded-md border object-cover"
                                    />
                                @endif
                                <x-ui.file-upload name="who_are_we_image" accept="image/*" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </form>
        </div>

        {{-- Right Side: Homepage Sections Management --}}
        @php
            $homepageSectionDefinitions = $homepageSectionDefinitions ?? app(\Blaze\AdminCore\AdminCoreConfiguration::class)->homepageSectionDefinitions();
        @endphp
        <div class="lg:col-span-7 space-y-6" x-data="homepageSectionsManager(@js($homepageSections), @js($availableServices), @js($homepageSectionDefinitions))">
            <x-ui.card class="border shadow-sm">
                <x-ui.card-header class="pb-3 border-b bg-muted/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-layers class="text-primary size-5" />
                            <div>
                                <x-ui.card-title class="text-base font-semibold">Homepage Management</x-ui.card-title>
                                <x-ui.card-description class="text-xs">
                                    Reorder sections, toggle visibility, and manage curated trips.
                                </x-ui.card-description>
                            </div>
                        </div>
                        <x-ui.button
                            type="button"
                            size="sm"
                            @click="openAddModal"
                            class="shadow-sm"
                        >
                            <x-slot:before>
                                <x-lucide-plus class="size-4" />
                            </x-slot:before>
                            Add Section
                        </x-ui.button>
                    </div>
                </x-ui.card-header>

                <x-ui.card-content class="pt-4 px-4 pb-4">
                    {{-- Missing Sections Quick Add Badges --}}
                    <div x-show="missingStandardSections.length > 0" class="mb-4 p-3 rounded-lg border border-dashed border-primary/30 bg-primary/5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                <x-lucide-sparkles class="size-3.5 text-primary" />
                                Quick Add Missing Sections:
                            </span>
                            <span class="text-[11px] text-muted-foreground">Click any badge to restore</span>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="item in missingStandardSections" :key="item.type">
                                <button
                                    type="button"
                                    @click="quickAddSection(item)"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md border transition-all hover:scale-[1.02] shadow-xs cursor-pointer"
                                    :class="item.badgeClass"
                                >
                                    <x-lucide-plus class="size-3" />
                                    <span x-text="`Add ${item.name}`"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="mb-3 flex items-center justify-between text-xs text-muted-foreground bg-muted/40 px-3 py-2 rounded-md">
                        <span class="flex items-center gap-1.5 font-medium">
                            <x-lucide-grip-vertical class="size-3.5 text-muted-foreground" />
                            Drag items to reorder homepage layout
                        </span>
                        <span class="font-semibold">{{ $homepageSections->count() }} Sections</span>
                    </div>

                    {{-- Sortable List Container --}}
                    <div
                        id="homepage-sections-sortable"
                        class="space-y-2.5"
                    >
                        @forelse ($homepageSections as $index => $section)
                            <div
                                class="group border border-border bg-card hover:border-primary/40 rounded-lg p-3 transition-all shadow-sm flex items-center gap-3 relative"
                                data-id="{{ $section->id }}"
                            >
                                {{-- Drag Handle --}}
                                <div
                                    data-drag-handle
                                    class="cursor-grab active:cursor-grabbing text-muted-foreground hover:text-foreground p-1 rounded hover:bg-muted"
                                    title="Drag to reorder"
                                >
                                    <x-lucide-grip-vertical class="size-4" />
                                </div>

                                {{-- Order Badge --}}
                                <div
                                    data-order-badge
                                    class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary text-xs font-bold"
                                >{{ $index + 1 }}</div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-medium text-sm text-foreground truncate">
                                            {{ $section->title }}
                                        </h4>
                                        <span
                                            class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded border"
                                            :class="getTypeBadgeClass('{{ $section->section_type }}')"
                                            x-text="getTypeLabel('{{ $section->section_type }}')"
                                        >{{ $section->section_type }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-muted-foreground">
                                        @if ($section->subtitle)
                                            <span class="truncate max-w-[200px]">{{ $section->subtitle }}</span>
                                        @endif
                                        @if (! empty($section->settings['service_ids']))
                                            <span class="inline-flex items-center gap-1 text-primary font-medium">
                                                <x-lucide-compass class="size-3" />
                                                <span>{{ count($section->settings['service_ids']) }} trips linked</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-1 shrink-0">
                                    {{-- Toggle Visibility Form --}}
                                    <form
                                        action="{{ route('admin.settings.homepage-sections.toggle', $section) }}"
                                        method="POST"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="p-1.5 rounded-md transition-colors {{ $section->status ? 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30' : 'text-muted-foreground hover:bg-muted' }}"
                                            title="{{ $section->status ? 'Visible on Homepage (Click to hide)' : 'Hidden (Click to show)' }}"
                                        >
                                            @if ($section->status)
                                                <x-lucide-eye class="size-4" />
                                            @else
                                                <x-lucide-eye-off class="size-4" />
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Edit Button --}}
                                    <button
                                        type="button"
                                        @click="openEditModal(@js($section))"
                                        class="p-1.5 text-muted-foreground hover:text-foreground hover:bg-muted rounded-md transition-colors"
                                        title="Edit Section"
                                    >
                                        <x-lucide-pencil class="size-4" />
                                    </button>

                                    {{-- Delete Button --}}
                                    <form
                                        action="{{ route('admin.settings.homepage-sections.destroy', $section) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to remove this section? You can quickly re-add it anytime.');"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="p-1.5 text-destructive hover:bg-destructive/10 rounded-md transition-colors"
                                            title="Delete Section"
                                        >
                                            <x-lucide-trash-2 class="size-4" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-sm text-muted-foreground">
                                No homepage sections yet. Click "Add Section" or restore standard sections above.
                            </div>
                        @endforelse
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            {{-- ADD SECTION MODAL --}}
            <div
                x-show="isAddOpen"
                style="display: none;"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm"
                x-transition.opacity
            >
                <div
                    @click.away="isAddOpen = false"
                    class="w-full max-w-lg rounded-xl border bg-background p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto"
                    x-show="isAddOpen"
                    x-transition
                >
                    <div class="flex items-center justify-between border-b pb-4 mb-5">
                        <div class="flex items-center gap-2">
                            <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                <x-lucide-layers class="size-5" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-foreground">Add Homepage Section</h3>
                                <p class="text-xs text-muted-foreground">Add a new curated trips block or content section.</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="isAddOpen = false"
                            class="text-muted-foreground hover:text-foreground p-1 rounded-md"
                        >
                            <x-lucide-x class="size-5" />
                        </button>
                    </div>

                    <form action="{{ route('admin.settings.homepage-sections.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <x-ui.field>
                            <x-ui.field-label required>Section Title</x-ui.field-label>
                            <x-ui.input
                                name="title"
                                x-model="addForm.title"
                                placeholder="E.g., Our Best-Selling Trips for 2026 or Our Featured Trips for 2026"
                                required
                            />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Section Subtitle / Tagline</x-ui.field-label>
                            <x-ui.input
                                name="subtitle"
                                x-model="addForm.subtitle"
                                placeholder="E.g., Curated Himalayan routes handpicked by senior guides"
                            />
                        </x-ui.field>

                        <input type="hidden" name="section_type" :value="addForm.section_type">

                        {{-- Service Picker (Active when section_type === 'custom_services') --}}
                        <div x-show="addForm.section_type === 'custom_services'" class="border border-border/70 bg-muted/20 p-4 rounded-lg space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-foreground flex items-center gap-1.5">
                                    <x-lucide-compass class="size-4 text-primary" />
                                    Choose Service Records (Trips)
                                </label>
                                <span class="text-xs text-muted-foreground" x-text="`${addForm.service_ids.length} selected`"></span>
                            </div>
                            <p class="text-xs text-muted-foreground">Select the treks or tours to showcase in this section:</p>

                            <div class="max-h-48 overflow-y-auto space-y-1.5 border rounded-md p-2 bg-background">
                                <template x-for="service in availableServices" :key="service.id">
                                    <label class="flex items-center gap-2.5 p-2 rounded hover:bg-muted/50 cursor-pointer text-sm transition-colors">
                                        <input
                                            type="checkbox"
                                            name="service_ids[]"
                                            :value="service.id"
                                            x-model="addForm.service_ids"
                                            class="rounded border-input text-primary focus:ring-primary size-4"
                                        />
                                        <span class="font-medium text-foreground text-xs" x-text="service.title"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <x-ui.field>
                                <x-ui.field-label>Button Text (Optional)</x-ui.field-label>
                                <x-ui.input
                                    name="button_text"
                                    x-model="addForm.button_text"
                                    placeholder="E.g., View All Trips"
                                />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Button URL (Optional)</x-ui.field-label>
                                <x-ui.input
                                    name="button_url"
                                    x-model="addForm.button_url"
                                    placeholder="E.g., /treks"
                                />
                            </x-ui.field>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t">
                            <x-ui.button
                                type="button"
                                variant="outline"
                                @click="isAddOpen = false"
                            >
                                Cancel
                            </x-ui.button>
                            <x-ui.button type="submit">
                                <x-slot:before>
                                    <x-lucide-plus class="size-4" />
                                </x-slot:before>
                                Create Section
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- EDIT SECTION MODAL --}}
            <div
                x-show="isEditOpen"
                style="display: none;"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm"
                x-transition.opacity
            >
                <div
                    @click.away="isEditOpen = false"
                    class="w-full max-w-lg rounded-xl border bg-background p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto"
                    x-show="isEditOpen"
                    x-transition
                >
                    <div class="flex items-center justify-between border-b pb-4 mb-5">
                        <div class="flex items-center gap-2">
                            <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                <x-lucide-pencil class="size-5" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-foreground">Edit Homepage Section</h3>
                                <p class="text-xs text-muted-foreground">Update title, subtitle, and linked trip records.</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="isEditOpen = false"
                            class="text-muted-foreground hover:text-foreground p-1 rounded-md"
                        >
                            <x-lucide-x class="size-5" />
                        </button>
                    </div>

                    <form
                        :action="`{{ url('admin/settings/homepage-sections') }}/${editForm.id}`"
                        method="POST"
                        class="space-y-4"
                    >
                        @csrf
                        @method('PUT')

                        <x-ui.field>
                            <x-ui.field-label required>Section Title</x-ui.field-label>
                            <x-ui.input
                                name="title"
                                x-model="editForm.title"
                                required
                            />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Section Subtitle / Tagline</x-ui.field-label>
                            <x-ui.input
                                name="subtitle"
                                x-model="editForm.subtitle"
                            />
                        </x-ui.field>

                        <input type="hidden" name="section_type" :value="editForm.section_type">

                        {{-- Service Picker (Active when section_type === 'custom_services') --}}
                        <div x-show="editForm.section_type === 'custom_services'" class="border border-border/70 bg-muted/20 p-4 rounded-lg space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-foreground flex items-center gap-1.5">
                                    <x-lucide-compass class="size-4 text-primary" />
                                    Choose Service Records (Trips)
                                </label>
                                <span class="text-xs text-muted-foreground" x-text="`${editForm.service_ids.length} selected`"></span>
                            </div>
                            <p class="text-xs text-muted-foreground">Select the treks or tours to showcase in this section:</p>

                            <div class="max-h-48 overflow-y-auto space-y-1.5 border rounded-md p-2 bg-background">
                                <template x-for="service in availableServices" :key="service.id">
                                    <label class="flex items-center gap-2.5 p-2 rounded hover:bg-muted/50 cursor-pointer text-sm transition-colors">
                                        <input
                                            type="checkbox"
                                            name="service_ids[]"
                                            :value="service.id"
                                            :checked="editForm.service_ids.includes(service.id) || editForm.service_ids.includes(String(service.id))"
                                            @change="
                                                let val = parseInt(service.id);
                                                if ($event.target.checked) {
                                                    if (!editForm.service_ids.includes(val)) editForm.service_ids.push(val);
                                                } else {
                                                    editForm.service_ids = editForm.service_ids.filter(id => id != val);
                                                }
                                            "
                                            class="rounded border-input text-primary focus:ring-primary size-4"
                                        />
                                        <span class="font-medium text-foreground text-xs" x-text="service.title"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <x-ui.field>
                                <x-ui.field-label>Button Text (Optional)</x-ui.field-label>
                                <x-ui.input
                                    name="button_text"
                                    x-model="editForm.button_text"
                                />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Button URL (Optional)</x-ui.field-label>
                                <x-ui.input
                                    name="button_url"
                                    x-model="editForm.button_url"
                                />
                            </x-ui.field>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t">
                            <x-ui.button
                                type="button"
                                variant="outline"
                                @click="isEditOpen = false"
                            >
                                Cancel
                            </x-ui.button>
                            <x-ui.button type="submit">
                                <x-slot:before>
                                    <x-lucide-save class="size-4" />
                                </x-slot:before>
                                Save Changes
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-ui.tabs-content>

        {{-- ── About Us Tab ── --}}
        <x-ui.tabs-content value="about">
            <form
                action="{{ route('admin.settings.about.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-4 space-y-6"
            >
                @csrf
                @method('PUT')

                {{-- Page Header --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-monitor class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Page Header</x-ui.card-title>
                                <x-ui.card-description>
                                    The banner shown at the top of the About Us page.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.field>
                                <x-ui.field-label>Subtitle</x-ui.field-label>
                                <x-ui.input
                                    name="about_header_subtitle"
                                    value="{{ old('about_header_subtitle', $settings['about_header_subtitle'] ?? '') }}"
                                    placeholder="Our Story"
                                />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Title</x-ui.field-label>
                                <x-ui.input
                                    name="about_header_title"
                                    value="{{ old('about_header_title', $settings['about_header_title'] ?? '') }}"
                                    placeholder="About Us"
                                />
                            </x-ui.field>
                        </div>
                        <x-ui.field>
                            <x-ui.field-label>Description</x-ui.field-label>
                            <x-ui.textarea
                                name="about_header_text"
                                rows="2"
                            >
                                {{ old('about_header_text', $settings['about_header_text'] ?? '') }}</x-ui.textarea>
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.field-label>Background Image</x-ui.field-label>
                            @if (! empty($settings['about_header_image']))
                                <img
                                    src="{{ Storage::url($settings['about_header_image']) }}"
                                    alt="Header"
                                    class="border-border mb-2 h-40 w-full rounded-md border object-cover"
                                />
                            @endif
                            <x-ui.file-upload name="about_header_image" accept="image/*" />
                        </x-ui.field>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Company Overview --}}
                @php
                    $overviewParagraphs = json_decode($settings['about_overview_paragraphs'] ?? '[]', true) ?: [];
                    $overviewParagraphsJson = json_encode(old('about_overview_paragraphs', $overviewParagraphs));
                @endphp
                <div x-data="aboutParagraphs({{ $overviewParagraphsJson }})">
                    <x-ui.card>
                        <x-ui.card-header>
                            <div class="flex items-center gap-2">
                                <x-lucide-file-text class="text-muted-foreground size-5" />
                                <div>
                                    <x-ui.card-title>Company Overview</x-ui.card-title>
                                    <x-ui.card-description>
                                        The main content block describing the company.</x-ui.card-description>
                                </div>
                            </div>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label>Subtitle</x-ui.field-label>
                                    <x-ui.input
                                        name="about_overview_subtitle"
                                        value="{{ old('about_overview_subtitle', $settings['about_overview_subtitle'] ?? '') }}"
                                        placeholder="Company Overview"
                                    />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label>Title</x-ui.field-label>
                                    <x-ui.input
                                        name="about_overview_title"
                                        value="{{ old('about_overview_title', $settings['about_overview_title'] ?? '') }}"
                                        placeholder="Building Legacies, Not Just Structures"
                                    />
                                </x-ui.field>
                            </div>
                            <x-ui.field>
                                <x-ui.field-label>Intro Text</x-ui.field-label>
                                <x-ui.textarea
                                    name="about_overview_text"
                                    rows="2"
                                >
                                    {{ old('about_overview_text', $settings['about_overview_text'] ?? '') }}</x-ui.textarea>
                            </x-ui.field>

                            {{-- Paragraphs --}}
                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label class="text-sm font-medium">Body Paragraphs</label>
                                    <x-ui.button type="button" variant="outline" size="sm" @click="addParagraph">
                                        <x-slot:before>
                                            <x-lucide-plus class="size-4" />
                                        </x-slot:before>
                                        Add Paragraph
                                    </x-ui.button>
                                </div>
                                <div class="space-y-2">
                                    <template x-if="paragraphs.length === 0">
                                        <p class="text-muted-foreground py-3 text-center text-sm">No paragraphs yet.</p>
                                    </template>
                                    <template x-for="(p, i) in paragraphs" :key="i">
                                        <div class="flex items-start gap-2">
                                            <textarea
                                                :name="`about_overview_paragraphs[${i}]`"
                                                x-model="paragraphs[i]"
                                                rows="8"
                                                placeholder="Paragraph text..."
                                                class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex w-full rounded-md border px-3 py-2 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:outline-none"
                                            ></textarea>
                                            <button
                                                type="button"
                                                @click="removeParagraph(i)"
                                                class="text-destructive hover:bg-destructive/10 mt-1 inline-flex size-8 shrink-0 items-center justify-center rounded-md transition-colors"
                                            >
                                                <x-lucide-trash-2 class="size-4" />
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Grid Images --}}
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label>Grid Image 1</x-ui.field-label>
                                    @if (! empty($settings['about_overview_image_1']))
                                        <img
                                            src="{{ Storage::url($settings['about_overview_image_1']) }}"
                                            class="border-border mb-2 h-32 w-full rounded-md border object-cover"
                                        />
                                    @endif
                                    <x-ui.file-upload name="about_overview_image_1" accept="image/*" />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label>Grid Image 2</x-ui.field-label>
                                    @if (! empty($settings['about_overview_image_2']))
                                        <img
                                            src="{{ Storage::url($settings['about_overview_image_2']) }}"
                                            class="border-border mb-2 h-32 w-full rounded-md border object-cover"
                                        />
                                    @endif
                                    <x-ui.file-upload name="about_overview_image_2" accept="image/*" />
                                </x-ui.field>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                {{-- Core Values --}}
                @php
                    $valuesItems = json_decode($settings['about_values_items'] ?? '[]', true) ?: [];
                    $valuesJson = json_encode(old('about_values_items', $valuesItems));
                @endphp
                <div x-data="aboutValues({{ $valuesJson }})">
                    <x-ui.card>
                        <x-ui.card-header>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <x-lucide-shield class="text-muted-foreground size-5" />
                                    <div>
                                        <x-ui.card-title>Core Values</x-ui.card-title>
                                        <x-ui.card-description>
                                            The value cards shown in the coloured strip.</x-ui.card-description>
                                    </div>
                                </div>
                                <x-ui.button type="button" variant="outline" size="sm" @click="addValue">
                                    <x-slot:before>
                                        <x-lucide-plus class="size-4" />
                                    </x-slot:before>
                                    Add Value
                                </x-ui.button>
                            </div>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label>Section Title</x-ui.field-label>
                                    <x-ui.input
                                        name="about_values_title"
                                        value="{{ old('about_values_title', $settings['about_values_title'] ?? '') }}"
                                        placeholder="Our Core Values"
                                    />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label>Section Description</x-ui.field-label>
                                    <x-ui.input
                                        name="about_values_text"
                                        value="{{ old('about_values_text', $settings['about_values_text'] ?? '') }}"
                                        placeholder="The principles that guide every..."
                                    />
                                </x-ui.field>
                            </div>
                            <div class="space-y-3">
                                <template x-if="values.length === 0">
                                    <p class="text-muted-foreground py-3 text-center text-sm">No values yet.</p>
                                </template>
                                <template x-for="(v, i) in values" :key="i">
                                    <div class="border-border bg-muted/30 space-y-3 rounded-lg border p-4">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                                x-text="'Value ' + (i + 1)"
                                            ></span>
                                            <button
                                                type="button"
                                                @click="removeValue(i)"
                                                class="text-destructive hover:bg-destructive/10 inline-flex size-7 items-center justify-center rounded-md transition-colors"
                                            >
                                                <x-lucide-trash-2 class="size-4" />
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-muted-foreground mb-1 block text-xs">Lucide Icon Name</label>
                                                <input
                                                    type="text"
                                                    :name="`about_values_items[${i}][icon]`"
                                                    x-model="v.icon"
                                                    placeholder="e.g. shield-check"
                                                    class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:outline-none"
                                                />
                                            </div>
                                            <div>
                                                <label class="text-muted-foreground mb-1 block text-xs">Title</label>
                                                <input
                                                    type="text"
                                                    :name="`about_values_items[${i}][title]`"
                                                    x-model="v.title"
                                                    placeholder="Value title"
                                                    class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:outline-none"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-muted-foreground mb-1 block text-xs">Description</label>
                                            <textarea
                                                :name="`about_values_items[${i}][text]`"
                                                x-model="v.text"
                                                rows="2"
                                                placeholder="Short description..."
                                                class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex w-full rounded-md border px-3 py-2 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:outline-none"
                                            ></textarea>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                {{-- Team Section --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-users class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Team Section</x-ui.card-title>
                                <x-ui.card-description>Heading for the team members grid.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.field>
                                <x-ui.field-label>Subtitle</x-ui.field-label>
                                <x-ui.input
                                    name="about_team_subtitle"
                                    value="{{ old('about_team_subtitle', $settings['about_team_subtitle'] ?? '') }}"
                                    placeholder="Leadership Team"
                                />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Title</x-ui.field-label>
                                <x-ui.input
                                    name="about_team_title"
                                    value="{{ old('about_team_title', $settings['about_team_title'] ?? '') }}"
                                    placeholder="Meet The Experts"
                                />
                            </x-ui.field>
                        </div>
                        <x-ui.field>
                            <x-ui.field-label>Description</x-ui.field-label>
                            <x-ui.textarea
                                name="about_team_text"
                                rows="2"
                            >
                                {{ old('about_team_text', $settings['about_team_text'] ?? '') }}</x-ui.textarea>
                        </x-ui.field>
                    </x-ui.card-content>
                    <x-ui.card-footer class="flex justify-end border-t pt-4">
                        <x-ui.button type="submit">
                            <x-slot:before>
                                <x-lucide-save class="size-4" />
                            </x-slot:before>
                            Save About Settings
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-ui.tabs-content>

        {{-- ── System Tab ── --}}
        <x-ui.tabs-content value="system">
            <form
                action="{{ route('admin.settings.system.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-4"
            >
                @csrf
                @method('PUT')

                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-settings class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Admin Portal</x-ui.card-title>
                                <x-ui.card-description>
                                    Customize the admin portal branding shown in the sidebar.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <x-ui.field>
                            <x-ui.field-label>Admin Portal Title</x-ui.field-label>
                            <x-ui.input
                                name="admin_portal_title"
                                value="{{ old('admin_portal_title', $settings['admin_portal_title'] ?? 'Blaze Admin') }}"
                                placeholder="Blaze Admin"
                            />
                            <x-ui.field-description>
                                This appears in the admin sidebar in place of the default label.</x-ui.field-description>
                        </x-ui.field>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="mt-6">
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-code class="size-5 text-muted-foreground" />
                            <div>
                                <x-ui.card-title>Custom Scripts</x-ui.card-title>
                                <x-ui.card-description>Inject custom third-party scripts (like Tawk.to, Meta Pixel, etc.) into the website.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <x-ui.field>
                            <x-ui.field-label>Footer Scripts</x-ui.field-label>
                            <x-ui.textarea
                                name="custom_footer_scripts"
                                rows="6"
                                placeholder="<!-- Paste your custom scripts here... -->"
                            >{{ old('custom_footer_scripts', $settings['custom_footer_scripts'] ?? '') }}</x-ui.textarea>
                            <x-ui.field-description>
                                These scripts will be placed right before the closing &lt;/body&gt; tag on all pages. Perfect for chat widgets or tracking scripts.
                            </x-ui.field-description>
                        </x-ui.field>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="mt-6">
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-menu class="size-5 text-muted-foreground" />
                            <div>
                                <x-ui.card-title>Sidebar Modules</x-ui.card-title>
                                <x-ui.card-description>Rename the modules in the sidebar.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="grid grid-cols-2 gap-4">
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('services'))
                        <x-ui.field>
                            <x-ui.field-label>Services Label</x-ui.field-label>
                            <x-ui.input name="label_services" value="{{ old('label_services', $settings['label_services'] ?? 'Services') }}" placeholder="Services" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.field-label>Service Features Label</x-ui.field-label>
                            <x-ui.input name="label_services_features" value="{{ old('label_services_features', $settings['label_services_features'] ?? 'Features') }}" placeholder="Features" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('projects'))
                        <x-ui.field>
                            <x-ui.field-label>Projects Label</x-ui.field-label>
                            <x-ui.input name="label_projects" value="{{ old('label_projects', $settings['label_projects'] ?? 'Projects') }}" placeholder="Projects" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('enquiries'))
                        <x-ui.field>
                            <x-ui.field-label>Enquiries Label</x-ui.field-label>
                            <x-ui.input name="label_enquiries" value="{{ old('label_enquiries', $settings['label_enquiries'] ?? 'Enquiries') }}" placeholder="Enquiries" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('contact-messages'))
                        <x-ui.field>
                            <x-ui.field-label>Contact Messages Label</x-ui.field-label>
                            <x-ui.input name="label_contact_messages" value="{{ old('label_contact_messages', $settings['label_contact_messages'] ?? 'Contact Messages') }}" placeholder="Contact Messages" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('testimonials'))
                        <x-ui.field>
                            <x-ui.field-label>Testimonials Label</x-ui.field-label>
                            <x-ui.input name="label_testimonials" value="{{ old('label_testimonials', $settings['label_testimonials'] ?? 'Testimonials') }}" placeholder="Testimonials" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('team-members'))
                        <x-ui.field>
                            <x-ui.field-label>Team Members Label</x-ui.field-label>
                            <x-ui.input name="label_team_members" value="{{ old('label_team_members', $settings['label_team_members'] ?? 'Team Members') }}" placeholder="Team Members" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('gallery'))
                        <x-ui.field>
                            <x-ui.field-label>Gallery Label</x-ui.field-label>
                            <x-ui.input name="label_gallery" value="{{ old('label_gallery', $settings['label_gallery'] ?? 'Gallery') }}" placeholder="Gallery" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('blog'))
                        <x-ui.field>
                            <x-ui.field-label>Blog Label</x-ui.field-label>
                            <x-ui.input name="label_blog" value="{{ old('label_blog', $settings['label_blog'] ?? 'Blog') }}" placeholder="Blog" />
                        </x-ui.field>
                        @endif
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('downloads'))
                        <x-ui.field>
                            <x-ui.field-label>Downloads Label</x-ui.field-label>
                            <x-ui.input name="label_downloads" value="{{ old('label_downloads', $settings['label_downloads'] ?? 'Downloads') }}" placeholder="Downloads" />
                        </x-ui.field>
                        @endif
                        <x-ui.field>
                            <x-ui.field-label>Company Menu Label</x-ui.field-label>
                            <x-ui.input name="label_company" value="{{ old('label_company', $settings['label_company'] ?? 'Company') }}" placeholder="Company" />
                        </x-ui.field>
                        @if (app(\Blaze\AdminCore\AdminCoreConfiguration::class)->enabled('company-info'))
                        <x-ui.field>
                            <x-ui.field-label>Company Info Label</x-ui.field-label>
                            <x-ui.input name="label_company_info" value="{{ old('label_company_info', $settings['label_company_info'] ?? 'Company Info') }}" placeholder="Company Info" />
                        </x-ui.field>
                        @endif
                        <x-ui.field>
                            <x-ui.field-label>Tags Label</x-ui.field-label>
                            <x-ui.input name="label_tags" value="{{ old('label_tags', $settings['label_tags'] ?? 'Tags') }}" placeholder="Tags" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.field-label>FAQs Label</x-ui.field-label>
                            <x-ui.input name="label_faqs" value="{{ old('label_faqs', $settings['label_faqs'] ?? 'FAQs') }}" placeholder="FAQs" />
                        </x-ui.field>
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t pt-4 flex justify-end">
                        <x-ui.button type="submit">
                            <x-slot:before>
                                <x-lucide-save class="size-4" />
                            </x-slot:before>
                            Save System Settings
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-ui.tabs-content>

        {{-- ── SEO Tab ── --}}
        <x-ui.tabs-content value="seo">
            <form
                action="{{ route('admin.settings.seo.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-4"
            >
                @csrf
                @method('PUT')

                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-globe class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Default SEO Settings</x-ui.card-title>
                                <x-ui.card-description>
                                    Fallback meta tags used when a page has no specific SEO
                                    configuration.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <x-ui.field>
                            <x-ui.field-label>Default Meta Title</x-ui.field-label>
                            <x-ui.input
                                name="seo_default_title"
                                value="{{ old('seo_default_title', $settings['seo_default_title'] ?? '') }}"
                                placeholder="E.g., Blaze AdminOpen source admin panel"
                            />
                            <x-ui.field-description>Used when a page has no specific SEO title.</x-ui.field-description>
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Default Meta Description</x-ui.field-label>
                            <x-ui.textarea
                                name="seo_default_description"
                                rows="3"
                                placeholder="Enter default description..."
                            >
                                {{ old('seo_default_description', $settings['seo_default_description'] ?? '') }}</x-ui.textarea>
                            <x-ui.field-description>Recommended: 150–160 characters.</x-ui.field-description>
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Default Meta Keywords</x-ui.field-label>
                            <x-ui.input
                                name="seo_default_keywords"
                                value="{{ old('seo_default_keywords', $settings['seo_default_keywords'] ?? '') }}"
                                placeholder="construction, building, finishing..."
                            />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Google Analytics ID</x-ui.field-label>
                            <x-ui.input
                                name="google_analytics"
                                value="{{ old('google_analytics', $settings['google_analytics'] ?? '') }}"
                                placeholder="G-XXXXXXXXXX"
                            />
                            <x-ui.field-description>
                                Enter your Google Analytics measurement ID to enable tracking.</x-ui.field-description>
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Default OG Image</x-ui.field-label>
                            @if (! empty($settings['seo_default_image']))
                                <img
                                    src="{{ Storage::url($settings['seo_default_image']) }}"
                                    alt="OG Image"
                                    class="border-border mb-2 h-32 rounded-md border object-cover"
                                />
                            @endif
                            <x-ui.file-upload name="seo_default_image" accept="image/*" />
                            <x-ui.field-description>Recommended size: 1200×630 px.</x-ui.field-description>
                        </x-ui.field>
                    </x-ui.card-content>
                    <x-ui.card-footer class="flex justify-end border-t pt-4">
                        <x-ui.button type="submit">
                            <x-slot:before>
                                <x-lucide-save class="size-4" />
                            </x-slot:before>
                            Save SEO Settings
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-ui.tabs-content>

        {{-- ── Subpages Tab ── --}}
        <x-ui.tabs-content value="subpages">
            <form
                action="{{ route('admin.settings.subpages.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-4 space-y-6"
            >
                @csrf
                @method('PUT')

                {{-- Reviews Banner --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-star class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Reviews Page Banner</x-ui.card-title>
                                <x-ui.card-description>Banner settings for the Customer Reviews page.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.field>
                                <x-ui.field-label>Banner Title</x-ui.field-label>
                                <x-ui.input
                                    name="reviews_banner_title"
                                    value="{{ old('reviews_banner_title', $settings['reviews_banner_title'] ?? '') }}"
                                    placeholder="Customer Reviews"
                                />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Banner Text</x-ui.field-label>
                                <x-ui.input
                                    name="reviews_banner_text"
                                    value="{{ old('reviews_banner_text', $settings['reviews_banner_text'] ?? '') }}"
                                    placeholder="Real stories and experiences..."
                                />
                            </x-ui.field>
                        </div>
                        <x-ui.field>
                            <x-ui.field-label>Background Image</x-ui.field-label>
                            @if (! empty($settings['reviews_banner_image']))
                                <img
                                    src="{{ Storage::url($settings['reviews_banner_image']) }}"
                                    class="border-border mb-2 h-32 w-full rounded-md border object-cover"
                                />
                            @endif
                            <x-ui.file-upload name="reviews_banner_image" accept="image/*" />
                        </x-ui.field>
                        
                        <div class="mt-6 border-t pt-4">
                            <h4 class="mb-4 text-sm font-semibold">SEO Meta Data</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label>SEO Title</x-ui.field-label>
                                    <x-ui.input
                                        name="reviews_seo_title"
                                        value="{{ old('reviews_seo_title', $settings['reviews_seo_title'] ?? '') }}"
                                        placeholder="Customer Reviews - Neepa Adventure"
                                    />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label>SEO Description</x-ui.field-label>
                                    <x-ui.input
                                        name="reviews_seo_description"
                                        value="{{ old('reviews_seo_description', $settings['reviews_seo_description'] ?? '') }}"
                                        placeholder="Read real reviews from our past travelers."
                                    />
                                </x-ui.field>
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Team Banner --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-users class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Team Page Banner</x-ui.card-title>
                                <x-ui.card-description>Banner settings for the Our Team page.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.field>
                                <x-ui.field-label>Banner Title</x-ui.field-label>
                                <x-ui.input
                                    name="team_banner_title"
                                    value="{{ old('team_banner_title', $settings['team_banner_title'] ?? '') }}"
                                    placeholder="Meet Our Team"
                                />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Banner Text</x-ui.field-label>
                                <x-ui.input
                                    name="team_banner_text"
                                    value="{{ old('team_banner_text', $settings['team_banner_text'] ?? '') }}"
                                    placeholder="The guides and people behind..."
                                />
                            </x-ui.field>
                        </div>
                        <x-ui.field>
                            <x-ui.field-label>Background Image</x-ui.field-label>
                            @if (! empty($settings['team_banner_image']))
                                <img
                                    src="{{ Storage::url($settings['team_banner_image']) }}"
                                    class="border-border mb-2 h-32 w-full rounded-md border object-cover"
                                />
                            @endif
                            <x-ui.file-upload name="team_banner_image" accept="image/*" />
                        </x-ui.field>

                        <div class="mt-6 border-t pt-4">
                            <h4 class="mb-4 text-sm font-semibold">SEO Meta Data</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label>SEO Title</x-ui.field-label>
                                    <x-ui.input
                                        name="team_seo_title"
                                        value="{{ old('team_seo_title', $settings['team_seo_title'] ?? '') }}"
                                        placeholder="Our Team - Neepa Adventure"
                                    />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label>SEO Description</x-ui.field-label>
                                    <x-ui.input
                                        name="team_seo_description"
                                        value="{{ old('team_seo_description', $settings['team_seo_description'] ?? '') }}"
                                        placeholder="Meet our experienced and dedicated team."
                                    />
                                </x-ui.field>
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Careers Banner --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center gap-2">
                            <x-lucide-briefcase class="text-muted-foreground size-5" />
                            <div>
                                <x-ui.card-title>Careers Page Banner</x-ui.card-title>
                                <x-ui.card-description>Banner settings for the Careers page.</x-ui.card-description>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.field>
                                <x-ui.field-label>Banner Title</x-ui.field-label>
                                <x-ui.input
                                    name="careers_banner_title"
                                    value="{{ old('careers_banner_title', $settings['careers_banner_title'] ?? '') }}"
                                    placeholder="Join Our Team"
                                />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Banner Text</x-ui.field-label>
                                <x-ui.input
                                    name="careers_banner_text"
                                    value="{{ old('careers_banner_text', $settings['careers_banner_text'] ?? '') }}"
                                    placeholder="Explore opportunities to work with us."
                                />
                            </x-ui.field>
                        </div>
                        <x-ui.field>
                            <x-ui.field-label>Background Image</x-ui.field-label>
                            @if (! empty($settings['careers_banner_image']))
                                <img
                                    src="{{ Storage::url($settings['careers_banner_image']) }}"
                                    class="border-border mb-2 h-32 w-full rounded-md border object-cover"
                                />
                            @endif
                            <x-ui.file-upload name="careers_banner_image" accept="image/*" />
                        </x-ui.field>

                        <div class="mt-6 border-t pt-4">
                            <h4 class="mb-4 text-sm font-semibold">SEO Meta Data</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label>SEO Title</x-ui.field-label>
                                    <x-ui.input
                                        name="careers_seo_title"
                                        value="{{ old('careers_seo_title', $settings['careers_seo_title'] ?? '') }}"
                                        placeholder="Careers - Neepa Adventure"
                                    />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label>SEO Description</x-ui.field-label>
                                    <x-ui.input
                                        name="careers_seo_description"
                                        value="{{ old('careers_seo_description', $settings['careers_seo_description'] ?? '') }}"
                                        placeholder="Join our team and build a career with us."
                                    />
                                </x-ui.field>
                            </div>
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t pt-4 flex justify-end">
                        <x-ui.button type="submit">
                            <x-slot:before>
                                <x-lucide-save class="size-4" />
                            </x-slot:before>
                            Save Subpage Settings
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-ui.tabs-content>
    </x-ui.tabs>
</x-layouts.admin>

<script>
    function heroStats(initial) {
        return {
            stats: initial,
            addStat() {
                if (this.stats.length < 6)
                    this.stats.push({
                        icon: '',
                        value: '',
                        label: '',
                    });
            },
            removeStat(i) {
                this.stats.splice(i, 1);
            },
        };
    }

    function whoAreWe(initial) {
        return {
            bullets: initial,
            addBullet() {
                this.bullets.push('');
            },
            removeBullet(i) {
                this.bullets.splice(i, 1);
            },
        };
    }

    function aboutParagraphs(initial) {
        return {
            paragraphs: initial,
            addParagraph() {
                this.paragraphs.push('');
            },
            removeParagraph(i) {
                this.paragraphs.splice(i, 1);
            },
        };
    }

    function aboutValues(initial) {
        return {
            values: initial,
            addValue() {
                this.values.push({
                    icon: '',
                    title: '',
                    text: '',
                });
            },
            removeValue(i) {
                this.values.splice(i, 1);
            },
        };
    }

    function homepageSectionsManager(initialSections, services, standardDefs = []) {
        return {
            sections: initialSections,
            availableServices: services,
            standardDefinitions: standardDefs || [],
            isAddOpen: false,
            isEditOpen: false,
            addForm: {
                title: '',
                subtitle: '',
                section_type: 'custom_services',
                service_ids: [],
                button_text: '',
                button_url: '',
            },
            editForm: {
                id: null,
                title: '',
                subtitle: '',
                section_type: 'custom_services',
                service_ids: [],
                button_text: '',
                button_url: '',
            },
            init() {
                this.$nextTick(() => {
                    const el = document.getElementById('homepage-sections-sortable');
                    const sortableLib = window.Sortable || (typeof Sortable !== 'undefined' ? Sortable : null);
                    if (el && sortableLib) {
                        sortableLib.create(el, {
                            handle: '[data-drag-handle]',
                            draggable: '[data-id]',
                            animation: 150,
                            ghostClass: 'opacity-40',
                            onEnd: () => {
                                const ids = [...el.querySelectorAll('[data-id]')].map(
                                    (item) => parseInt(item.dataset.id)
                                );
                                // Re-sync visual badge numbers
                                el.querySelectorAll('[data-order-badge]').forEach((badge, idx) => {
                                    badge.textContent = idx + 1;
                                });

                                fetch('{{ route('admin.reorder', 'homepage-sections') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({ ids }),
                                }).then(res => {
                                    if (!res.ok) {
                                        console.error('Reorder failed');
                                    }
                                });
                            },
                        });
                    }
                });
            },
            get missingStandardSections() {
                const existingTypes = this.sections.map(s => s.section_type);
                return this.standardDefinitions.filter(d => !existingTypes.includes(d.type));
            },
            quickAddSection(item) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.settings.homepage-sections.store') }}';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const titleInput = document.createElement('input');
                titleInput.type = 'hidden';
                titleInput.name = 'title';
                titleInput.value = item.title || '';
                form.appendChild(titleInput);

                const subtitleInput = document.createElement('input');
                subtitleInput.type = 'hidden';
                subtitleInput.name = 'subtitle';
                subtitleInput.value = item.subtitle || '';
                form.appendChild(subtitleInput);

                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'section_type';
                typeInput.value = item.type;
                form.appendChild(typeInput);

                const btnTextInput = document.createElement('input');
                btnTextInput.type = 'hidden';
                btnTextInput.name = 'button_text';
                btnTextInput.value = item.button_text || '';
                form.appendChild(btnTextInput);

                const btnUrlInput = document.createElement('input');
                btnUrlInput.type = 'hidden';
                btnUrlInput.name = 'button_url';
                btnUrlInput.value = item.button_url || '';
                form.appendChild(btnUrlInput);

                document.body.appendChild(form);
                form.submit();
            },
            openAddModal() {
                this.addForm = {
                    title: '',
                    subtitle: '',
                    section_type: 'custom_services',
                    service_ids: [],
                    button_text: 'Explore All Trips',
                    button_url: '/treks',
                };
                this.isAddOpen = true;
            },
            openEditModal(section) {
                const serviceIds = (section.settings && section.settings.service_ids) 
                    ? [...section.settings.service_ids].map(id => parseInt(id))
                    : [];
                this.editForm = {
                    id: section.id,
                    title: section.title || '',
                    subtitle: section.subtitle || '',
                    section_type: section.section_type || 'custom_services',
                    service_ids: serviceIds,
                    button_text: section.button_text || '',
                    button_url: section.button_url || '',
                };
                this.isEditOpen = true;
            },
            getTypeLabel(type) {
                if (type === 'custom_services') return 'Curated Trips';
                const found = this.standardDefinitions.find(d => d.type === type);
                if (found && found.name) return found.name;
                if (type === 'about_us' || type === 'who_are_we') return 'About Us';
                return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            },
            getTypeBadgeClass(type) {
                if (type === 'custom_services') return 'bg-amber-500/10 text-amber-600 border-amber-500/20';
                const found = this.standardDefinitions.find(d => d.type === type);
                if (found && found.badgeClass) return found.badgeClass;
                return 'bg-muted text-muted-foreground border-border';
            },
        };
    }
</script>
