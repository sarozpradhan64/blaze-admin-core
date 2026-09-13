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
        <div class="bg-card border border-border rounded-xl px-5 py-4 flex items-center gap-4">
            <div class="shrink-0 flex items-center justify-center size-10 rounded-lg bg-muted text-muted-foreground">
                <x-lucide-folder-kanban class="size-5" />
            </div>
            <div>
                <p class="text-xs text-muted-foreground font-medium uppercase tracking-wide">Projects</p>
                <p class="text-2xl font-bold text-foreground leading-tight">{{ \App\Models\Project::count() }}</p>
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl px-5 py-4 flex items-center gap-4">
            <div class="shrink-0 flex items-center justify-center size-10 rounded-lg bg-muted text-muted-foreground">
                <x-lucide-briefcase class="size-5" />
            </div>
            <div>
                <p class="text-xs text-muted-foreground font-medium uppercase tracking-wide">Services</p>
                <p class="text-2xl font-bold text-foreground leading-tight">{{ \App\Models\Service::count() }}</p>
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl px-5 py-4 flex items-center gap-4">
            <div class="shrink-0 flex items-center justify-center size-10 rounded-lg bg-primary/10 text-primary">
                <x-lucide-inbox class="size-5" />
            </div>
            <div>
                <p class="text-xs text-muted-foreground font-medium uppercase tracking-wide">Pending Enquiries</p>
                <p class="text-2xl font-bold text-primary leading-tight">{{ \Blaze\AdminCore\Models\Enquiry::where('status', 'new')->count() }}</p>
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl px-5 py-4 flex items-center gap-4">
            <div class="shrink-0 flex items-center justify-center size-10 rounded-lg bg-primary/10 text-primary">
                <x-lucide-mail class="size-5" />
            </div>
            <div>
                <p class="text-xs text-muted-foreground font-medium uppercase tracking-wide">Unread Messages</p>
                <p class="text-2xl font-bold text-primary leading-tight">{{ \Blaze\AdminCore\Models\ContactMessage::where('status', 'new')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
        <div class="bg-card border border-border rounded-xl lg:col-span-4">
            <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                <div>
                    <p class="text-sm font-semibold text-foreground">Recent Enquiries</p>
                    <p class="text-xs text-muted-foreground mt-0.5">Latest 5 submissions</p>
                </div>
                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.enquiries.index') }}">
                    View all
                    <x-slot:after><x-lucide-arrow-right class="size-3.5" /></x-slot:after>
                </x-ui.button>
            </div>
            <div class="divide-y divide-border">
                @forelse(\Blaze\AdminCore\Models\Enquiry::latest()->take(5)->get() as $enquiry)
                    <div class="flex items-center justify-between px-5 py-3.5 gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="shrink-0 flex items-center justify-center size-8 rounded-full bg-muted text-muted-foreground text-xs font-semibold uppercase">
                                {{ mb_substr($enquiry->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-foreground truncate">{{ $enquiry->name }}</p>
                                <p class="text-xs text-muted-foreground truncate">{{ $enquiry->email }}</p>
                            </div>
                        </div>
                        <x-ui.badge variant="{{ $enquiry->status === 'new' ? 'default' : 'secondary' }}" class="shrink-0">
                            {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                        </x-ui.badge>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <x-lucide-inbox class="size-8 text-muted-foreground mx-auto mb-2" />
                        <p class="text-sm text-muted-foreground">No recent enquiries.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>

