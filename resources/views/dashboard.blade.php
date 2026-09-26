<x-layouts.admin title="Dashboard">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Dashboard</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-muted text-muted-foreground flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-folder-kanban class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">Projects</p>
                <p class="text-foreground text-2xl leading-tight font-bold">
                    {{ \Blaze\AdminCore\Models\Project::count() }}
                </p>
            </div>
        </div>

        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-muted text-muted-foreground flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-briefcase class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">Services</p>
                <p class="text-foreground text-2xl leading-tight font-bold">
                    {{ \Blaze\AdminCore\Models\Service::count() }}
                </p>
            </div>
        </div>

        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-primary/10 text-primary flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-inbox class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">Pending Enquiries</p>
                <p class="text-primary text-2xl leading-tight font-bold">
                    {{ \Blaze\AdminCore\Models\Enquiry::where('status', 'new')->count() }}
                </p>
            </div>
        </div>

        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-primary/10 text-primary flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-mail class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">Unread Messages</p>
                <p class="text-primary text-2xl leading-tight font-bold">
                    {{ \Blaze\AdminCore\Models\ContactMessage::where('status', 'new')->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
        <div class="bg-card border-border rounded-xl border lg:col-span-4">
            <div class="border-border flex items-center justify-between border-b px-5 py-4">
                <div>
                    <p class="text-foreground text-sm font-semibold">Recent Enquiries</p>
                    <p class="text-muted-foreground mt-0.5 text-xs">Latest 5 submissions</p>
                </div>
                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.enquiries.index') }}">
                    View all
                    <x-slot:after>
                        <x-lucide-arrow-right class="size-3.5" />
                    </x-slot:after>
                </x-ui.button>
            </div>
            <div class="divide-border divide-y">
                @forelse (\Blaze\AdminCore\Models\Enquiry::latest()->take(5)->get() as $enquiry)
                    <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="bg-muted text-muted-foreground flex size-8 shrink-0 items-center justify-center rounded-full text-xs font-semibold uppercase">
                                {{ mb_substr($enquiry->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-foreground truncate text-sm font-medium">{{ $enquiry->name }}</p>
                                <p class="text-muted-foreground truncate text-xs">{{ $enquiry->email }}</p>
                            </div>
                        </div>
                        <x-ui.badge
                            variant="{{ $enquiry->status === 'new' ? 'default' : 'secondary' }}"
                            class="shrink-0"
                        >
                            {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                        </x-ui.badge>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <x-lucide-inbox class="text-muted-foreground mx-auto mb-2 size-8" />
                        <p class="text-muted-foreground text-sm">No recent enquiries.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
