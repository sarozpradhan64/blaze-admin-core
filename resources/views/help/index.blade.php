<x-layouts.admin title="Help & Documentation">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.dashboard') }}">Dashboard</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Help & Guide</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div
        x-data="{
            search: '',
            category: 'all',
            faqOpen: null,
            matches(text, cat) {
                const matchesCat = this.category === 'all' || this.category === cat;
                if (!matchesCat) return false;
                if (!this.search.trim()) return true;
                return text.toLowerCase().includes(this.search.toLowerCase());
            }
        }"
        class="space-y-8"
    >
        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-2xl border border-border bg-gradient-to-br from-card via-card to-primary/5 p-6 md:p-8">
            <div class="relative z-10 max-w-3xl space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                    <x-lucide-book-open class="size-3.5" />
                    <span>Documentation & Knowledge Base</span>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl text-foreground">
                    Admin Portal Guide & Documentation
                </h1>
                <p class="text-base text-muted-foreground leading-relaxed">
                    Welcome to the central documentation for your administration panel. Learn how to manage website content, oversee customer leads, configure brand settings, optimize SEO, and leverage all platform features.
                </p>

                {{-- Instant Search Bar --}}
                <div class="pt-2">
                    <div class="relative max-w-xl">
                        <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search topics (e.g., SEO, enquiries, hero banner, clear cache, images)..."
                            class="w-full rounded-xl border border-input bg-background/80 backdrop-blur pl-10 pr-10 py-2.5 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary shadow-sm"
                        />
                        <button
                            x-show="search.length > 0"
                            @click="search = ''"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-muted-foreground hover:text-foreground p-1"
                        >
                            <x-lucide-x class="size-3.5" />
                        </button>
                    </div>
                </div>
            </div>

            {{-- Background decorative graphic --}}
            <div class="pointer-events-none absolute -right-12 -bottom-12 opacity-10 dark:opacity-5">
                <x-lucide-sparkles class="size-72 text-primary" />
            </div>
        </div>

        {{-- Filter Categories Pills --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <button
                @click="category = 'all'"
                :class="category === 'all' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0"
            >
                All Topics
            </button>
            <button
                @click="category = 'getting-started'"
                :class="category === 'getting-started' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0 flex items-center gap-1.5"
            >
                <x-lucide-rocket class="size-3.5" /> Quick Start
            </button>
            <button
                @click="category = 'content'"
                :class="category === 'content' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0 flex items-center gap-1.5"
            >
                <x-lucide-file-text class="size-3.5" /> Content Management
            </button>
            <button
                @click="category = 'leads'"
                :class="category === 'leads' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0 flex items-center gap-1.5"
            >
                <x-lucide-inbox class="size-3.5" /> Inbound Leads
            </button>
            <button
                @click="category = 'company'"
                :class="category === 'company' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0 flex items-center gap-1.5"
            >
                <x-lucide-building-2 class="size-3.5" /> Company & Brand
            </button>
            <button
                @click="category = 'media'"
                :class="category === 'media' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0 flex items-center gap-1.5"
            >
                <x-lucide-image class="size-3.5" /> Media & Gallery
            </button>
            <button
                @click="category = 'settings'"
                :class="category === 'settings' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0 flex items-center gap-1.5"
            >
                <x-lucide-settings class="size-3.5" /> Settings & SEO
            </button>
            <button
                @click="category = 'troubleshooting'"
                :class="category === 'troubleshooting' ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'"
                class="rounded-lg px-3.5 py-1.5 text-xs font-medium transition-all shrink-0 flex items-center gap-1.5"
            >
                <x-lucide-help-circle class="size-3.5" /> FAQ & Help
            </button>
        </div>

        {{-- Quick Insights & Cheat Sheet Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-border bg-card flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-primary">
                        <x-lucide-rotate-cw class="size-4 shrink-0" />
                        <h4 class="text-sm font-semibold">Changes Not Visible?</h4>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        The website aggressively caches content for maximum visitor speed. Whenever you update settings, menus, or homepage hero banners, clear the cache to immediately publish changes.
                    </p>
                </div>
                <div class="mt-3 pt-3 border-t border-border/60">
                    <form method="POST" action="{{ route('admin.cache.clear') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-medium text-primary hover:underline">
                            <span>Clear Cache Now</span>
                            <x-lucide-arrow-right class="size-3" />
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-4 rounded-xl border border-border bg-card flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-primary">
                        <x-lucide-move class="size-4 shrink-0" />
                        <h4 class="text-sm font-semibold">Drag-to-Reorder</h4>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Menus, social media icons, homepage sections, and service features can be rearranged simply by grabbing their handle icon. Positions save automatically via AJAX without clicking submit.
                    </p>
                </div>
                <div class="mt-3 pt-3 border-t border-border/60">
                    <span class="text-xs text-muted-foreground flex items-center gap-1">
                        <x-lucide-check-circle-2 class="size-3.5 text-emerald-500" /> Auto-saved on drop
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-xl border border-border bg-card flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-primary">
                        <x-lucide-globe class="size-4 shrink-0" />
                        <h4 class="text-sm font-semibold">SEO & Meta Tags</h4>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Every service, blog post, project, and static page has its own SEO drawer. Fill out custom meta titles (50-60 chars) and descriptions (150-160 chars) to rank higher on search engines.
                    </p>
                </div>
                @if (Route::has('admin.settings.index'))
                    <div class="mt-3 pt-3 border-t border-border/60">
                        <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-primary hover:underline">
                            <span>Global SEO Settings</span>
                            <x-lucide-arrow-right class="size-3" />
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Section 1: Quick Start --}}
        <section
            x-show="matches('quick start workflow dashboard setup launch overview guide', 'getting-started')"
            class="space-y-4"
        >
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-lucide-rocket class="size-4" />
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">1. Getting Started & Core Workflow</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-ui.card class="relative">
                    <x-ui.card-header class="pb-2">
                        <div class="flex items-center justify-between">
                            <span class="flex size-6 items-center justify-center rounded-full bg-primary text-primary-foreground text-xs font-bold">1</span>
                            <x-lucide-building-2 class="size-4 text-muted-foreground" />
                        </div>
                        <x-ui.card-title class="text-base pt-2">Brand & Company Info</x-ui.card-title>
                        <x-ui.card-description class="text-xs">Initial foundation</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="text-xs text-muted-foreground space-y-2">
                        <p>Set up your company's official name, primary email address, phone numbers, physical address, and Google Maps iframe embed.</p>
                        <p>Then configure your social media profiles (Facebook, Instagram, LinkedIn, YouTube, etc.) with custom ordering.</p>
                        @if (Route::has('admin.company-info.index'))
                            <div class="pt-2">
                                <x-ui.button variant="outline" size="sm" class="w-full text-xs" href="{{ route('admin.company-info.index') }}">
                                    Go to Company Info
                                </x-ui.button>
                            </div>
                        @endif
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="relative">
                    <x-ui.card-header class="pb-2">
                        <div class="flex items-center justify-between">
                            <span class="flex size-6 items-center justify-center rounded-full bg-primary text-primary-foreground text-xs font-bold">2</span>
                            <x-lucide-palette class="size-4 text-muted-foreground" />
                        </div>
                        <x-ui.card-title class="text-base pt-2">Logo, Favicon & Hero</x-ui.card-title>
                        <x-ui.card-description class="text-xs">First impression</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="text-xs text-muted-foreground space-y-2">
                        <p>Under <strong>Settings</strong>, upload your high-resolution transparent PNG/SVG logo and website favicon.</p>
                        <p>Customize your homepage Hero banner: engaging headline, sub-headline, primary call-to-action button, and background imagery.</p>
                        @if (Route::has('admin.settings.index'))
                            <div class="pt-2">
                                <x-ui.button variant="outline" size="sm" class="w-full text-xs" href="{{ route('admin.settings.index') }}">
                                    Go to Settings
                                </x-ui.button>
                            </div>
                        @endif
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="relative">
                    <x-ui.card-header class="pb-2">
                        <div class="flex items-center justify-between">
                            <span class="flex size-6 items-center justify-center rounded-full bg-primary text-primary-foreground text-xs font-bold">3</span>
                            <x-lucide-list-tree class="size-4 text-muted-foreground" />
                        </div>
                        <x-ui.card-title class="text-base pt-2">Services & Content</x-ui.card-title>
                        <x-ui.card-description class="text-xs">Core offerings</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="text-xs text-muted-foreground space-y-2">
                        <p>Create your primary services or packages. Attach rich descriptions, key bullet points, image galleries, and search keywords.</p>
                        <p>Set up navigation menus so visitors can easily browse your offerings from the header and footer.</p>
                        @if (Route::has('admin.services.index'))
                            <div class="pt-2">
                                <x-ui.button variant="outline" size="sm" class="w-full text-xs" href="{{ route('admin.services.index') }}">
                                    Go to Services
                                </x-ui.button>
                            </div>
                        @endif
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </section>

        {{-- Section 2: Content Management --}}
        <section
            x-show="matches('content management services projects blog pages menus tags faqs downloads documents rich text editor', 'content')"
            class="space-y-4"
        >
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-lucide-file-text class="size-4" />
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">2. Content & Publishing Modules</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Services --}}
                @if ($configuration->enabled('services'))
                    <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                    <x-lucide-briefcase class="size-4" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold">{{ $websiteSettings['label_services'] ?? 'Services' }} & Categories</h3>
                                    <p class="text-xs text-muted-foreground">Core commercial offerings</p>
                                </div>
                            </div>
                            @if (Route::has('admin.services.index'))
                                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.services.index') }}">
                                    Manage <x-lucide-arrow-right class="ml-1 size-3.5" />
                                </x-ui.button>
                            @endif
                        </div>
                        <ul class="text-xs text-muted-foreground space-y-1.5 list-disc list-inside">
                            <li><strong>Categories:</strong> Group your services logically (e.g., Adventure Treks, Peak Climbing, Cultural Tours).</li>
                            <li><strong>Features:</strong> Highlight key bullet-points (e.g. "Experienced Sherpa Guides", "All Permits Included").</li>
                            <li><strong>Linked Gallery & SEO:</strong> Connect photo albums and define custom meta titles/descriptions per service.</li>
                            <li><strong>Form Builder Integration:</strong> Additional site-specific fields (e.g., Duration, Altitude, Difficulty) appear automatically when registered.</li>
                        </ul>
                    </div>
                @endif

                {{-- Projects --}}
                @if ($configuration->enabled('projects'))
                    <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                    <x-lucide-folder-kanban class="size-4" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold">{{ $websiteSettings['label_projects'] ?? 'Projects' }} / Portfolio</h3>
                                    <p class="text-xs text-muted-foreground">Showcase your completed work</p>
                                </div>
                            </div>
                            @if (Route::has('admin.projects.index'))
                                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.projects.index') }}">
                                    Manage <x-lucide-arrow-right class="ml-1 size-3.5" />
                                </x-ui.button>
                            @endif
                        </div>
                        <ul class="text-xs text-muted-foreground space-y-1.5 list-disc list-inside">
                            <li><strong>Case Studies:</strong> Record client name, project duration, date completed, and detailed challenge & solution stories.</li>
                            <li><strong>Project Images:</strong> Upload multiple gallery shots with descriptive captions for portfolio lightboxes.</li>
                            <li><strong>Project Videos:</strong> Embed YouTube or Vimeo video links directly inside the project view.</li>
                            <li><strong>Project Statistics:</strong> Add key milestone metrics (e.g., "100% Summit Rate", "4,500+ Travelers Guided").</li>
                        </ul>
                    </div>
                @endif

                {{-- Blog & Articles --}}
                @if ($configuration->enabled('blog'))
                    <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                    <x-lucide-newspaper class="size-4" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold">{{ $websiteSettings['label_blog'] ?? 'Blog' }} & News</h3>
                                    <p class="text-xs text-muted-foreground">Articles, travel tips, and updates</p>
                                </div>
                            </div>
                            @if (Route::has('admin.blogs.index'))
                                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.blogs.index') }}">
                                    Manage <x-lucide-arrow-right class="ml-1 size-3.5" />
                                </x-ui.button>
                            @endif
                        </div>
                        <ul class="text-xs text-muted-foreground space-y-1.5 list-disc list-inside">
                            <li><strong>Rich Text Editor:</strong> Full formatting support (headings, bold, lists, quotes, code, hyperlinks, and image embeds).</li>
                            <li><strong>Draft vs. Published:</strong> Control article visibility with status toggles and publish dates.</li>
                            <li><strong>Categories & Tags:</strong> Organize posts to enable visitors to filter by topic.</li>
                            <li><strong>Social Sharing Cards:</strong> Custom OpenGraph titles and images ensure your links look great on WhatsApp and Facebook.</li>
                        </ul>
                    </div>
                @endif

                {{-- Navigation Menus --}}
                @if ($configuration->enabled('menus'))
                    <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                    <x-lucide-menu class="size-4" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold">Navigation Menus</h3>
                                    <p class="text-xs text-muted-foreground">Header, footer & mobile menu builder</p>
                                </div>
                            </div>
                            @if (Route::has('admin.menus.index'))
                                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.menus.index') }}">
                                    Manage <x-lucide-arrow-right class="ml-1 size-3.5" />
                                </x-ui.button>
                            @endif
                        </div>
                        <ul class="text-xs text-muted-foreground space-y-1.5 list-disc list-inside">
                            <li><strong>Multiple Locations:</strong> Build different menus for Main Header, Footer Columns, or Mobile Drawers.</li>
                            <li><strong>Nested Dropdowns:</strong> Create parent items and sub-items to generate multi-level dropdowns.</li>
                            <li><strong>Internal or External Links:</strong> Point items to website routes (`/about`, `/services`) or full external URLs.</li>
                            <li><strong>Live Reordering:</strong> Drag items up or down to change their order in real-time.</li>
                        </ul>
                    </div>
                @endif

                {{-- Standalone Pages --}}
                @if ($configuration->enabled('pages'))
                    <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                    <x-lucide-file-code class="size-4" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold">Custom Pages</h3>
                                    <p class="text-xs text-muted-foreground">Static & legal text pages</p>
                                </div>
                            </div>
                            @if (Route::has('admin.pages.index'))
                                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.pages.index') }}">
                                    Manage <x-lucide-arrow-right class="ml-1 size-3.5" />
                                </x-ui.button>
                            @endif
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Create standalone content pages such as Privacy Policy, Terms & Conditions, Booking Terms, or special landing pages. Each page features custom URL slugs (`/page/{slug}`), rich text content, and dedicated SEO tags.
                        </p>
                    </div>
                @endif

                {{-- FAQs --}}
                <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                <x-lucide-message-circle-question class="size-4" />
                            </div>
                            <div>
                                <h3 class="text-base font-semibold">{{ $websiteSettings['label_faqs'] ?? 'FAQs' }}</h3>
                                <p class="text-xs text-muted-foreground">Frequently asked questions</p>
                            </div>
                        </div>
                        @if (Route::has('admin.faqs.index'))
                            <x-ui.button variant="ghost" size="sm" href="{{ route('admin.faqs.index') }}">
                                Manage <x-lucide-arrow-right class="ml-1 size-3.5" />
                            </x-ui.button>
                        @endif
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Add frequently asked questions with clear answers and sort orders to address visitor questions before they book or reach out.
                    </p>
                </div>

                {{-- Downloads (Only if enabled in AdminCoreConfiguration) --}}
                @if ($configuration->enabled('downloads'))
                    <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                    <x-lucide-download class="size-4" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold">{{ $websiteSettings['label_downloads'] ?? 'Downloads' }}</h3>
                                    <p class="text-xs text-muted-foreground">Document and file library</p>
                                </div>
                            </div>
                            @if (Route::has('admin.downloads.index'))
                                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.downloads.index') }}">
                                    Manage <x-lucide-arrow-right class="ml-1 size-3.5" />
                                </x-ui.button>
                            @endif
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Upload PDF brochures, packing lists, route maps, and price lists. The system automatically tracks download counts.
                        </p>
                    </div>
                @endif
            </div>
        </section>

        {{-- Section 3: Inbound Leads & Customer Management --}}
        <section
            x-show="matches('inbound leads enquiries contact messages customer submissions bookings newsletter subscriptions', 'leads')"
            class="space-y-4"
        >
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-lucide-inbox class="size-4" />
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">3. Inbound Leads & Messages</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Enquiries --}}
                <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-inbox class="size-5 text-primary" />
                            <h3 class="text-sm font-semibold">{{ $websiteSettings['label_enquiries'] ?? 'Enquiries' }}</h3>
                        </div>
                        <x-ui.badge variant="secondary">Direct Leads</x-ui.badge>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Contains requests submitted through booking modals, service inquiry forms, and custom itinerary request forms.
                    </p>
                    <div class="rounded-lg bg-muted/50 p-2.5 text-xs text-muted-foreground space-y-1">
                        <p class="font-semibold text-foreground">Lead Status Lifecycle:</p>
                        <p><span class="inline-block w-20 text-foreground font-mono">new:</span> Unread inquiry awaiting response</p>
                        <p><span class="inline-block w-20 text-foreground font-mono">in_progress:</span> Contacted / discussion ongoing</p>
                        <p><span class="inline-block w-20 text-foreground font-mono">completed:</span> Booking finalized / resolved</p>
                        <p><span class="inline-block w-20 text-foreground font-mono">spam:</span> Flagged spam submission</p>
                    </div>
                    @if (Route::has('admin.enquiries.index'))
                        <x-ui.button variant="outline" size="sm" class="w-full text-xs" href="{{ route('admin.enquiries.index') }}">
                            Open Enquiries Inbox
                        </x-ui.button>
                    @endif
                </div>

                {{-- Contact Messages --}}
                <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-message-square class="size-5 text-primary" />
                            <h3 class="text-sm font-semibold">{{ $websiteSettings['label_contact_messages'] ?? 'Contact Messages' }}</h3>
                        </div>
                        <x-ui.badge variant="secondary">Contact Us</x-ui.badge>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Contains messages submitted through your public <code>/contact</code> page form. View sender name, email, phone number, subject, and detailed message body.
                    </p>
                    <div class="rounded-lg bg-muted/50 p-2.5 text-xs text-muted-foreground space-y-1">
                        <p class="font-semibold text-foreground">Status Tracking:</p>
                        <p><span class="inline-block w-16 text-foreground font-mono">new:</span> Indicates unread message</p>
                        <p><span class="inline-block w-16 text-foreground font-mono">read:</span> Reviewed by administrator</p>
                        <p><span class="inline-block w-16 text-foreground font-mono">replied:</span> Follow-up completed</p>
                    </div>
                    @if (Route::has('admin.contact-messages.index'))
                        <x-ui.button variant="outline" size="sm" class="w-full text-xs" href="{{ route('admin.contact-messages.index') }}">
                            Open Messages Inbox
                        </x-ui.button>
                    @endif
                </div>

                {{-- Newsletter Subscriptions --}}
                <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-mail class="size-5 text-primary" />
                            <h3 class="text-sm font-semibold">Subscribers</h3>
                        </div>
                        <x-ui.badge variant="secondary">Email List</x-ui.badge>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        List of visitor email addresses subscribed to your newsletter updates from footer or popup subscription forms.
                    </p>
                    <div class="rounded-lg bg-muted/50 p-2.5 text-xs text-muted-foreground space-y-1">
                        <p class="font-semibold text-foreground">Marketing Integration:</p>
                        <p>View subscription dates, export subscriber lists for marketing campaigns (Mailchimp, Brevo, SendGrid), and remove unsubscribed entries.</p>
                    </div>
                    @if (Route::has('admin.newsletter-subscriptions.index'))
                        <x-ui.button variant="outline" size="sm" class="w-full text-xs" href="{{ route('admin.newsletter-subscriptions.index') }}">
                            View Subscribers
                        </x-ui.button>
                    @endif
                </div>
            </div>
        </section>

        {{-- Section 4: Company & Brand Profile --}}
        <section
            x-show="matches('company brand profile info social links team members legal documents affiliations payment methods testimonials reviews careers jobs', 'company')"
            class="space-y-4"
        >
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-lucide-building-2 class="size-4" />
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">4. Company & Brand Management</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Company Info & Socials --}}
                <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                    <div class="flex items-center gap-2 text-primary">
                        <x-lucide-phone-call class="size-4" />
                        <h4 class="text-sm font-semibold">Contact & Socials</h4>
                    </div>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Maintain official address, phone numbers, contact emails, working hours, and social media handles (Facebook, Instagram, LinkedIn, etc.) with custom ordering.
                    </p>
                    @if (Route::has('admin.company-info.index'))
                        <a href="{{ route('admin.company-info.index') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                            Edit Contact Info <x-lucide-arrow-right class="size-3" />
                        </a>
                    @endif
                </div>

                {{-- Team Members --}}
                @if ($configuration->enabled('team-members'))
                    <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <x-lucide-users class="size-4" />
                            <h4 class="text-sm font-semibold">Team Members</h4>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Add staff, leadership, and trekking guides. Upload professional headshots, job designations, bios, and direct personal social profile links.
                        </p>
                        @if (Route::has('admin.team-members.index'))
                            <a href="{{ route('admin.team-members.index') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                                Manage Team <x-lucide-arrow-right class="size-3" />
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Legal Documents --}}
                @if ($configuration->enabled('legal-documents'))
                    <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <x-lucide-shield-check class="size-4" />
                            <h4 class="text-sm font-semibold">Legal Documents</h4>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Upload government registration certificates, PAN/VAT certificates, tourism board licenses, and insurance documentation for customer credibility.
                        </p>
                        @if (Route::has('admin.legal-documents.index'))
                            <a href="{{ route('admin.legal-documents.index') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                                Legal Documents <x-lucide-arrow-right class="size-3" />
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Affiliations & Partners --}}
                @if ($configuration->enabled('affiliations'))
                    <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <x-lucide-award class="size-4" />
                            <h4 class="text-sm font-semibold">Affiliations</h4>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Showcase industry accreditations (TAAN, NMA, Nepal Tourism Board, KEEP). Upload logos and partner website URLs to build visitor trust.
                        </p>
                        @if (Route::has('admin.affiliations.index'))
                            <a href="{{ route('admin.affiliations.index') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                                Affiliations <x-lucide-arrow-right class="size-3" />
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Payment Methods --}}
                @if ($configuration->enabled('payment-methods'))
                    <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <x-lucide-credit-card class="size-4" />
                            <h4 class="text-sm font-semibold">Accepted Payments</h4>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Display payment badges on the footer and checkout pages (Visa, MasterCard, Amex, PayPal, Bank Wire, local digital wallets).
                        </p>
                        @if (Route::has('admin.payment-methods.index'))
                            <a href="{{ route('admin.payment-methods.index') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                                Payment Methods <x-lucide-arrow-right class="size-3" />
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Testimonials --}}
                @if ($configuration->enabled('testimonials'))
                    <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <x-lucide-star class="size-4" />
                            <h4 class="text-sm font-semibold">Testimonials</h4>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Highlight client reviews, star ratings (1 to 5), reviewer country/company, and avatars. Featured reviews automatically highlight on the homepage.
                        </p>
                        @if (Route::has('admin.testimonials.index'))
                            <a href="{{ route('admin.testimonials.index') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                                Manage Reviews <x-lucide-arrow-right class="size-3" />
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Careers & Jobs --}}
                @if ($configuration->enabled('careers'))
                    <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <x-lucide-briefcase class="size-4" />
                            <h4 class="text-sm font-semibold">Careers & Jobs</h4>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Post job openings with employment types (Full Time, Seasonal, Remote). Review submitted applications, candidate resumes, and update hiring pipeline status.
                        </p>
                        @if (Route::has('admin.jobs.index'))
                            <a href="{{ route('admin.jobs.index') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                                Jobs & Openings <x-lucide-arrow-right class="size-3" />
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Tags Management --}}
                @if ($configuration->enabled('tags'))
                    <div class="rounded-xl border border-border bg-card p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <x-lucide-tags class="size-4" />
                            <h4 class="text-sm font-semibold">Global Tags</h4>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Maintain global keyword tags applied across blog posts, services, or treks for cross-filtering and internal search indexing.
                        </p>
                        @if (Route::has('admin.tags.manage'))
                            <a href="{{ route('admin.tags.manage') }}" class="text-xs font-medium text-primary hover:underline inline-flex items-center gap-1">
                                Manage Tags <x-lucide-arrow-right class="size-3" />
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </section>

        {{-- Section 5: Media & Gallery Management --}}
        <section
            x-show="matches('media gallery albums items uploads photos dimensions webp compression sizing', 'media')"
            class="space-y-4"
        >
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-lucide-image class="size-4" />
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">5. Media & Gallery Best Practices</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    <div class="rounded-xl border border-border bg-card p-5 space-y-3">
                        <h3 class="text-base font-semibold">Gallery Albums & Photos</h3>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            The gallery system organizes media by <strong>Albums</strong> (e.g., "Everest Base Camp Treks", "Annapurna Circuit Landscapes", "Cultural Heritage Festivals"). Individual photos can be bulk uploaded, captioned, and linked directly to relevant services or treks.
                        </p>
                        <div class="flex gap-2 pt-2">
                            @if (Route::has('admin.gallery-albums.index'))
                                <x-ui.button variant="outline" size="sm" class="text-xs" href="{{ route('admin.gallery-albums.index') }}">
                                    View Albums
                                </x-ui.button>
                            @endif
                            @if (Route::has('admin.gallery-items.index'))
                                <x-ui.button variant="outline" size="sm" class="text-xs" href="{{ route('admin.gallery-items.index') }}">
                                    All Gallery Photos
                                </x-ui.button>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-xl border border-border bg-card p-5 space-y-2">
                        <h4 class="text-sm font-semibold flex items-center gap-2 text-primary">
                            <x-lucide-lightbulb class="size-4" />
                            Image Optimization Tips
                        </h4>
                        <ul class="text-xs text-muted-foreground space-y-1.5 list-disc list-inside">
                            <li><strong>Preferred Formats:</strong> Modern <code>.webp</code> or optimized <code>.jpg</code>.</li>
                            <li><strong>File Size Target:</strong> Keep standard photos under <strong>400 KB</strong>. Large uncompressed 5MB camera photos slow down mobile loading.</li>
                            <li><strong>Transparent Graphics:</strong> Logos and affiliation stamps should always be transparent <code>.png</code> or <code>.svg</code>.</li>
                        </ul>
                    </div>
                </div>

                {{-- Recommended Dimensions Table --}}
                <div class="rounded-xl border border-border bg-card overflow-hidden">
                    <div class="px-4 py-3 border-b bg-muted/40">
                        <h4 class="text-sm font-semibold">Recommended Dimensions Cheat Sheet</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="border-b bg-muted/20 text-muted-foreground font-medium">
                                <tr>
                                    <th class="px-4 py-2.5">Asset Type</th>
                                    <th class="px-4 py-2.5">Aspect Ratio</th>
                                    <th class="px-4 py-2.5">Resolution</th>
                                    <th class="px-4 py-2.5">Format</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                <tr>
                                    <td class="px-4 py-2.5 font-medium">Homepage Hero Banner</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">16:9 or 21:9</td>
                                    <td class="px-4 py-2.5 font-mono">1920 &times; 1080 px</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">WebP / JPG</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 font-medium">Services & Treks Cover</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">16:10 or 4:3</td>
                                    <td class="px-4 py-2.5 font-mono">1200 &times; 800 px</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">WebP / JPG</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 font-medium">Blog Featured Image</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">16:9</td>
                                    <td class="px-4 py-2.5 font-mono">1200 &times; 675 px</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">WebP / JPG</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 font-medium">OpenGraph Social Share</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">1.91:1</td>
                                    <td class="px-4 py-2.5 font-mono">1200 &times; 630 px</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">JPG / PNG</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 font-medium">Team Member Headshots</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">1:1 Square</td>
                                    <td class="px-4 py-2.5 font-mono">600 &times; 600 px</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">WebP / JPG</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 font-medium">Website Logo</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">Horizontal / Free</td>
                                    <td class="px-4 py-2.5 font-mono">Height 80&ndash;120 px</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">PNG (Transparent) / SVG</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2.5 font-medium">Favicon</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">1:1 Square</td>
                                    <td class="px-4 py-2.5 font-mono">64 &times; 64 px</td>
                                    <td class="px-4 py-2.5 text-muted-foreground">PNG / ICO</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section 6: Settings & SEO --}}
        <section
            x-show="matches('settings configuration homepage sections about us system tabs seo meta opengraph subpages analytics tracking', 'settings')"
            class="space-y-4"
        >
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-lucide-settings class="size-4" />
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">6. Website Settings & SEO Management</h2>
                </div>
                @if (Route::has('admin.settings.index'))
                    <x-ui.button variant="outline" size="sm" href="{{ route('admin.settings.index') }}">
                        Open Settings Page <x-lucide-arrow-right class="ml-1 size-3.5" />
                    </x-ui.button>
                @endif
            </div>

            <p class="text-xs text-muted-foreground">
                The <strong>Settings</strong> console is unified into five specialized tabs. Below is an overview of what each tab controls:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="p-4 rounded-xl border border-border bg-card space-y-1.5">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Tab 1</span>
                    <h4 class="text-sm font-semibold">Homepage</h4>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Hero banner title, subtitle, primary CTA button text & link, background image/video. Also manage and reorder modular homepage sections.
                    </p>
                </div>
                <div class="p-4 rounded-xl border border-border bg-card space-y-1.5">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Tab 2</span>
                    <h4 class="text-sm font-semibold">About Us</h4>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Company story, vision statement, mission, core values, experience statistics, and about section images.
                    </p>
                </div>
                <div class="p-4 rounded-xl border border-border bg-card space-y-1.5">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Tab 3</span>
                    <h4 class="text-sm font-semibold">System</h4>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Site title, Admin portal title, Logo and Favicon upload, copyright text, and custom terminology labels (e.g. rename "Services" to "Treks").
                    </p>
                </div>
                <div class="p-4 rounded-xl border border-border bg-card space-y-1.5">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Tab 4</span>
                    <h4 class="text-sm font-semibold">Global SEO</h4>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Fallback meta title, meta description, keywords, default OpenGraph share image, and Google Analytics / Tag Manager script injections.
                    </p>
                </div>
                <div class="p-4 rounded-xl border border-border bg-card space-y-1.5">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Tab 5</span>
                    <h4 class="text-sm font-semibold">Subpages</h4>
                    <p class="text-xs text-muted-foreground leading-relaxed">
                        Default hero banner images, headings, and breadcrumb banners for inner pages (About, Contact, Services, Blog, etc.).
                    </p>
                </div>
            </div>

            {{-- SEO Optimization Guide Card --}}
            <div class="p-5 rounded-xl border border-border bg-gradient-to-r from-card to-card/60 space-y-3">
                <div class="flex items-center gap-2 text-primary font-semibold text-sm">
                    <x-lucide-search class="size-4" />
                    <span>Search Engine Optimization (SEO) Best Practices</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-muted-foreground">
                    <div class="space-y-1">
                        <strong class="text-foreground block">Meta Title (50–60 characters)</strong>
                        <span>Include primary keywords and your brand name. Example: <em>Everest Base Camp Trek 14 Days | Neepa Adventure</em>.</span>
                    </div>
                    <div class="space-y-1">
                        <strong class="text-foreground block">Meta Description (150–160 characters)</strong>
                        <span>Compelling summary that entices clicks on Google results. Avoid keyword stuffing and provide a clear call to action.</span>
                    </div>
                    <div class="space-y-1">
                        <strong class="text-foreground block">Open Graph Share Image</strong>
                        <span>Always set an OG Image (1200 &times; 630 px). When anyone shares your website on WhatsApp, Twitter, or Facebook, this image appears as the preview card.</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section 7: FAQ & Troubleshooting --}}
        <section
            x-show="matches('faq troubleshooting questions answers help issues problems cache not showing', 'troubleshooting')"
            class="space-y-4"
        >
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-lucide-help-circle class="size-4" />
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">7. Frequently Asked Questions & Troubleshooting</h2>
                </div>
            </div>

            <div class="space-y-3">
                {{-- FAQ 1 --}}
                <div class="rounded-xl border border-border bg-card overflow-hidden">
                    <button
                        @click="faqOpen = (faqOpen === 1 ? null : 1)"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-left text-sm font-semibold hover:bg-muted/30 transition-colors"
                    >
                        <span>Why are my updates not appearing on the live website?</span>
                        <x-lucide-chevron-down class="size-4 text-muted-foreground transition-transform" x-bind:class="{ 'rotate-180': faqOpen === 1 }" />
                    </button>
                    <div x-show="faqOpen === 1" x-collapse class="px-5 pb-4 pt-1 text-xs text-muted-foreground leading-relaxed space-y-2 border-t border-border/40">
                        <p>There are three common reasons:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li><strong>Application Cache:</strong> The site caches settings, menus, and company info for high speed. Click <strong>Clear Cache</strong> in the top-right user menu.</li>
                            <li><strong>Active Status Toggle:</strong> Check if the service, blog post, or project is marked as <em>Active / Published</em>. Inactive items are hidden from public view.</li>
                            <li><strong>Browser Cache:</strong> Press <kbd class="px-1.5 py-0.5 rounded bg-muted border font-mono">Ctrl + F5</kbd> (or <kbd class="px-1.5 py-0.5 rounded bg-muted border font-mono">Cmd + Shift + R</kbd> on Mac) to bypass local browser cache.</li>
                        </ol>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="rounded-xl border border-border bg-card overflow-hidden">
                    <button
                        @click="faqOpen = (faqOpen === 2 ? null : 2)"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-left text-sm font-semibold hover:bg-muted/30 transition-colors"
                    >
                        <span>How do I rearrange items (Menus, Social links, Homepage sections)?</span>
                        <x-lucide-chevron-down class="size-4 text-muted-foreground transition-transform" x-bind:class="{ 'rotate-180': faqOpen === 2 }" />
                    </button>
                    <div x-show="faqOpen === 2" x-collapse class="px-5 pb-4 pt-1 text-xs text-muted-foreground leading-relaxed space-y-2 border-t border-border/40">
                        <p>
                            Items with reordering support display a grip or drag icon. Simply click and hold the item, drag it to the desired position, and release. The new sort order is automatically saved via AJAX to the database without needing to press a "Save" button.
                        </p>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="rounded-xl border border-border bg-card overflow-hidden">
                    <button
                        @click="faqOpen = (faqOpen === 3 ? null : 3)"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-left text-sm font-semibold hover:bg-muted/30 transition-colors"
                    >
                        <span>How can I customize navigation labels like renaming "Services" to "Treks"?</span>
                        <x-lucide-chevron-down class="size-4 text-muted-foreground transition-transform" x-bind:class="{ 'rotate-180': faqOpen === 3 }" />
                    </button>
                    <div x-show="faqOpen === 3" x-collapse class="px-5 pb-4 pt-1 text-xs text-muted-foreground leading-relaxed space-y-2 border-t border-border/40">
                        <p>
                            Go to <strong>Settings &rarr; System Tab</strong>. Under the <em>Custom Terminology & Labels</em> section, you can customize the public and sidebar labels for Services (e.g. "Treks & Expeditions"), Blog (e.g. "Travel Stories"), and Projects (e.g. "Our Adventures").
                        </p>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="rounded-xl border border-border bg-card overflow-hidden">
                    <button
                        @click="faqOpen = (faqOpen === 4 ? null : 4)"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-left text-sm font-semibold hover:bg-muted/30 transition-colors"
                    >
                        <span>What is the difference between Enquiries and Contact Messages?</span>
                        <x-lucide-chevron-down class="size-4 text-muted-foreground transition-transform" x-bind:class="{ 'rotate-180': faqOpen === 4 }" />
                    </button>
                    <div x-show="faqOpen === 4" x-collapse class="px-5 pb-4 pt-1 text-xs text-muted-foreground leading-relaxed space-y-2 border-t border-border/40">
                        <p>
                            <strong>Enquiries:</strong> Actionable business leads generated from booking modals or specific package inquiry buttons. They include package preferences, passenger counts, and travel dates.
                        </p>
                        <p>
                            <strong>Contact Messages:</strong> General communications submitted through the standard Contact Us form with subject lines and feedback or partner requests.
                        </p>
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="rounded-xl border border-border bg-card overflow-hidden">
                    <button
                        @click="faqOpen = (faqOpen === 5 ? null : 5)"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-left text-sm font-semibold hover:bg-muted/30 transition-colors"
                    >
                        <span>How do developers add custom fields to core models (Form Builder)?</span>
                        <x-lucide-chevron-down class="size-4 text-muted-foreground transition-transform" x-bind:class="{ 'rotate-180': faqOpen === 5 }" />
                    </button>
                    <div x-show="faqOpen === 5" x-collapse class="px-5 pb-4 pt-1 text-xs text-muted-foreground leading-relaxed space-y-2 border-t border-border/40">
                        <p>
                            In the site repo, developers override <code>serviceFormFields()</code> in <code>app/Providers/AdminCoreConfiguration.php</code> using <code>ServiceFormField::make()</code>. The package automatically renders the form input, validates the data, and persists it into the database without altering core package files.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer Support Contact --}}
        <div class="rounded-xl border border-border bg-card p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="space-y-1 text-center md:text-left">
                <h4 class="text-sm font-semibold">Need further technical assistance or custom features?</h4>
                <p class="text-xs text-muted-foreground">
                    Blaze Admin Core is actively maintained. Contact your technical webmaster or development team for enhancements and customizations.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <x-ui.button variant="outline" size="sm" href="{{ route('admin.dashboard') }}">
                    <x-lucide-layout-dashboard class="mr-1.5 size-3.5" /> Back to Dashboard
                </x-ui.button>
            </div>
        </div>
    </div>
</x-layouts.admin>
