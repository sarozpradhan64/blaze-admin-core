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

    <x-ui.tabs value="{{ session('active_tab', 'homepage') }}" class="max-w-3xl">
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
            <form
                action="{{ route('admin.settings.homepage.update') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-4 space-y-6"
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
</script>
