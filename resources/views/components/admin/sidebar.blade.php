@php($configuration = app(\Blaze\AdminCore\AdminCoreConfiguration::class))

<x-ui.sidebar>
    <x-ui.sidebar-header>
        <div class="flex items-center gap-2 px-2 py-1.5">
            @if (! empty($websiteSettings['logo']))
                <img
                    src="{{ Storage::url($websiteSettings['logo']) }}"
                    alt="{{ $contactInfo?->company_name ?? 'Company logo' }}"
                    class="size-8 rounded-lg object-contain"
                />
            @else
                <div class="bg-primary text-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg">
                    <x-lucide-building-2 class="size-4" />
                </div>
            @endif
            <div class="flex flex-col gap-0.5 leading-none">
                <span class="font-semibold">{{ $websiteSettings['admin_portal_title'] ?? 'Blaze Admin' }}</span
                ><span class="text-muted-foreground text-xs">v1.0</span>
            </div>
        </div>
    </x-ui.sidebar-header>
    <x-ui.sidebar-content>
        <x-ui.sidebar-group>
            <x-ui.sidebar-group-label>Overview</x-ui.sidebar-group-label
            ><x-ui.sidebar-group-content>
                <x-ui.sidebar-menu>
                    <x-ui.sidebar-menu-item>
                        <x-ui.sidebar-menu-button
                            href="{{ route('admin.dashboard') }}"
                            :active="request()->routeIs('admin.dashboard')"
                        >
                            <x-lucide-layout-dashboard /><span>Dashboard</span></x-ui.sidebar-menu-button
                        ></x-ui.sidebar-menu-item
                    ></x-ui.sidebar-menu
                ></x-ui.sidebar-group-content
            ></x-ui.sidebar-group>

        <x-ui.sidebar-group>
            <x-ui.sidebar-group-label>Content</x-ui.sidebar-group-label>
            <x-ui.sidebar-group-content>
                <x-ui.sidebar-menu>
                    @if ($configuration->enabled('services'))
                        @php($servicesActive = request()->routeIs('admin.services.*') || request()->routeIs('admin.service-*'))
                        <x-ui.sidebar-menu-item x-data="{ expanded: {{ $servicesActive ? 'true' : 'false' }} }">
                            <x-ui.sidebar-menu-button :active="$servicesActive" @click.prevent="expanded = ! expanded">
                                <x-lucide-briefcase /><span>Services</span
                                ><x-lucide-chevron-down
                                    class="ml-auto transition-transform"
                                    x-bind:class="{ 'rotate-180': expanded }" /></x-ui.sidebar-menu-button
                            ><x-ui.sidebar-menu-sub x-show="expanded" x-collapse>
                                <x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.services.index') }}"
                                        :active="request()->routeIs('admin.services.*')"
                                    >
                                        All Services</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ><x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.service-categories.index') }}"
                                        :active="request()->routeIs('admin.service-categories.*')"
                                    >
                                        Categories</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ><x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.service-features.index') }}"
                                        :active="request()->routeIs('admin.service-features.*')"
                                    >
                                        Features</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ></x-ui.sidebar-menu-sub
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('projects'))
                        @php($projectsActive = request()->routeIs('admin.projects.*') || request()->routeIs('admin.project-*'))
                        <x-ui.sidebar-menu-item x-data="{ expanded: {{ $projectsActive ? 'true' : 'false' }} }">
                            <x-ui.sidebar-menu-button :active="$projectsActive" @click.prevent="expanded = ! expanded">
                                <x-lucide-folder-kanban /><span>Projects</span
                                ><x-lucide-chevron-down
                                    class="ml-auto transition-transform"
                                    x-bind:class="{ 'rotate-180': expanded }" /></x-ui.sidebar-menu-button
                            ><x-ui.sidebar-menu-sub x-show="expanded" x-collapse>
                                <x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.projects.index') }}"
                                        :active="request()->routeIs('admin.projects.*')"
                                    >
                                        All Projects</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ><x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.project-categories.index') }}"
                                        :active="request()->routeIs('admin.project-categories.*')"
                                    >
                                        Categories</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ></x-ui.sidebar-menu-sub
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('enquiries'))
                        @php($newEnquiries = \Blaze\AdminCore\Models\Enquiry::where('status', 'new')->count())
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.enquiries.index') }}"
                                :active="request()->routeIs('admin.enquiries.*')"
                            >
                                <x-lucide-inbox /><span>Enquiries</span>
                                @if ($newEnquiries > 0)
                                    <x-ui.sidebar-menu-badge>{{ $newEnquiries }}</x-ui.sidebar-menu-badge>
                                @endif
                            </x-ui.sidebar-menu-button>
                        </x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('contact-messages'))
                        @php($newMsgs = \Blaze\AdminCore\Models\ContactMessage::where('status', 'new')->count())
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.contact-messages.index') }}"
                                :active="request()->routeIs('admin.contact-messages.*')"
                            >
                                <x-lucide-message-square /><span>Contact Messages</span>
                                @if ($newMsgs > 0)
                                    <x-ui.sidebar-menu-badge>{{ $newMsgs }}</x-ui.sidebar-menu-badge>
                                @endif</x-ui.sidebar-menu-button
                        ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('testimonials'))
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.testimonials.index') }}"
                                :active="request()->routeIs('admin.testimonials.*')"
                            >
                                <x-lucide-message-circle-heart /><span>Testimonials</span></x-ui.sidebar-menu-button
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('team-members'))
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.team-members.index') }}"
                                :active="request()->routeIs('admin.team-members.*')"
                            >
                                <x-lucide-users /><span>Team Members</span></x-ui.sidebar-menu-button
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('gallery'))
                        @php($galleryActive = request()->routeIs('admin.gallery-*'))
                        <x-ui.sidebar-menu-item x-data="{ expanded: {{ $galleryActive ? 'true' : 'false' }} }">
                            <x-ui.sidebar-menu-button :active="$galleryActive" @click.prevent="expanded = ! expanded">
                                <x-lucide-image /><span>Gallery</span
                                ><x-lucide-chevron-down
                                    class="ml-auto transition-transform"
                                    x-bind:class="{ 'rotate-180': expanded }" /></x-ui.sidebar-menu-button
                            ><x-ui.sidebar-menu-sub x-show="expanded" x-collapse>
                                <x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.gallery-albums.index') }}"
                                        :active="request()->routeIs('admin.gallery-albums.*')"
                                    >
                                        Albums</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ><x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.gallery-items.index') }}"
                                        :active="request()->routeIs('admin.gallery-items.*')"
                                    >
                                        All Images</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ></x-ui.sidebar-menu-sub
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('blog'))
                        @php($blogActive = request()->routeIs('admin.blogs.*') || request()->routeIs('admin.blog-categories.*'))
                        <x-ui.sidebar-menu-item x-data="{ expanded: {{ $blogActive ? 'true' : 'false' }} }">
                            <x-ui.sidebar-menu-button :active="$blogActive" @click.prevent="expanded = ! expanded">
                                <x-lucide-file-text /><span>Blog</span
                                ><x-lucide-chevron-down
                                    class="ml-auto transition-transform"
                                    x-bind:class="{ 'rotate-180': expanded }" /></x-ui.sidebar-menu-button
                            ><x-ui.sidebar-menu-sub x-show="expanded" x-collapse>
                                <x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.blogs.index') }}"
                                        :active="request()->routeIs('admin.blogs.*')"
                                    >
                                        All Posts</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ><x-ui.sidebar-menu-sub-item>
                                    <x-ui.sidebar-menu-sub-button
                                        href="{{ route('admin.blog-categories.index') }}"
                                        :active="request()->routeIs('admin.blog-categories.*')"
                                    >
                                        Categories</x-ui.sidebar-menu-sub-button
                                    ></x-ui.sidebar-menu-sub-item
                                ></x-ui.sidebar-menu-sub
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('downloads'))
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.downloads.index') }}"
                                :active="request()->routeIs('admin.downloads.*')"
                            >
                                <x-lucide-download /><span>Downloads</span></x-ui.sidebar-menu-button
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('company-info'))
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.company-info.index') }}"
                                :active="request()->routeIs('admin.company-info.*')"
                            >
                                <x-lucide-building-2 /><span>Company Info</span></x-ui.sidebar-menu-button
                            ></x-ui.sidebar-menu-item>
                    @endif
                    <x-ui.sidebar-menu-item>
                        <x-ui.sidebar-menu-button
                            href="{{ route('admin.tags.manage') }}"
                            :active="request()->routeIs('admin.tags.manage')"
                        >
                            <x-lucide-tags /><span
                                >{{ $websiteSettings['label_tags'] ?? 'Tags' }}</span
                            ></x-ui.sidebar-menu-button
                        ></x-ui.sidebar-menu-item>
                    <x-ui.sidebar-menu-item>
                        <x-ui.sidebar-menu-button
                            href="{{ route('admin.faqs.index') }}"
                            :active="request()->routeIs('admin.faqs.*')"
                        >
                            <x-lucide-message-circle-question /><span
                                >{{ $websiteSettings['label_faqs'] ?? 'FAQs' }}</span
                            ></x-ui.sidebar-menu-button
                        ></x-ui.sidebar-menu-item>
                </x-ui.sidebar-menu></x-ui.sidebar-group-content>
        </x-ui.sidebar-group>

        <x-ui.sidebar-group>
            <x-ui.sidebar-group-label>System</x-ui.sidebar-group-label
            ><x-ui.sidebar-group-content>
                <x-ui.sidebar-menu>
                    @if ($configuration->enabled('users'))
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.users.index') }}"
                                :active="request()->routeIs('admin.users.*')"
                            >
                                <x-lucide-users /><span>Users</span></x-ui.sidebar-menu-button
                            ></x-ui.sidebar-menu-item>
                    @endif
                    @if ($configuration->enabled('settings'))
                        <x-ui.sidebar-menu-item>
                            <x-ui.sidebar-menu-button
                                href="{{ route('admin.settings.index') }}"
                                :active="request()->routeIs('admin.settings.*')"
                            >
                                <x-lucide-settings /><span>Settings</span></x-ui.sidebar-menu-button
                            ></x-ui.sidebar-menu-item>
                    @endif</x-ui.sidebar-menu></x-ui.sidebar-group-content
        ></x-ui.sidebar-group>
    </x-ui.sidebar-content>
    <x-ui.sidebar-footer>
        <x-ui.sidebar-menu>
            @if ($configuration->enabled('profile'))
                <x-ui.sidebar-menu-item>
                    <x-ui.sidebar-menu-button
                        href="{{ route('admin.profile.edit') }}"
                        :active="request()->routeIs('admin.profile.*')"
                    >
                        <x-lucide-user-cog /><span>My Profile</span></x-ui.sidebar-menu-button
                    ></x-ui.sidebar-menu-item>
            @endif
            <x-ui.sidebar-menu-item>
                <x-ui.sidebar-menu-button href="{{ route('home') }}">
                    <x-lucide-external-link /><span>View Website</span></x-ui.sidebar-menu-button
                ></x-ui.sidebar-menu-item>
            <x-ui.sidebar-menu-item>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-ui.sidebar-menu-button
                        as="button"
                        type="submit"
                        class="text-destructive hover:text-destructive w-full"
                    >
                        <x-lucide-log-out /><span>Sign Out</span></x-ui.sidebar-menu-button>
                </form>
            </x-ui.sidebar-menu-item>
        </x-ui.sidebar-menu></x-ui.sidebar-footer>
    <x-ui.sidebar-rail />
</x-ui.sidebar>
