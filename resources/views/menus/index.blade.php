<x-layouts.admin title="Menus">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Menus</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold tracking-tight">Menus</h2>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <label for="location-select" class="text-xs text-muted-foreground font-medium whitespace-nowrap">Placement:</label>
                <select
                    id="location-select"
                    onchange="window.location.href = this.value"
                    class="h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                >
                    <option value="{{ route('admin.menus.index', ['location' => 'navbar']) }}" {{ $currentLocation === 'navbar' ? 'selected' : '' }}>
                        Navbar Menus ({{ $navbarCount }})
                    </option>
                    <option value="{{ route('admin.menus.index', ['location' => 'footer']) }}" {{ $currentLocation === 'footer' ? 'selected' : '' }}>
                        Footer Menus ({{ $footerCount }})
                    </option>
                </select>
            </div>
            @if($currentLocation === 'footer' && $footerCount >= 2)
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-muted text-xs text-muted-foreground font-medium border border-border">
                    <x-lucide-info class="size-3.5 shrink-0" />
                    <span>Max 2 footer menus reached</span>
                </div>
            @else
                <x-ui.button href="{{ route('admin.menus.create', ['location' => $currentLocation]) }}">
                    <x-slot:before>
                        <x-lucide-plus class="size-4" />
                    </x-slot:before>
                    Add Menu
                </x-ui.button>
            @endif
        </div>
    </div>

    <!-- Location Filter Tabs -->
    <div class="flex items-center gap-2 mb-6 border-b border-border pb-3">
        <a
            href="{{ route('admin.menus.index', ['location' => 'navbar']) }}"
            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $currentLocation === 'navbar' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground hover:bg-muted/60' }}"
        >
            <x-lucide-layout-grid class="size-4" />
            Navbar Menus
            <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs rounded-full {{ $currentLocation === 'navbar' ? 'bg-primary-foreground/20 text-primary-foreground' : 'bg-muted text-muted-foreground' }}">
                {{ $navbarCount }}
            </span>
        </a>
        <a
            href="{{ route('admin.menus.index', ['location' => 'footer']) }}"
            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $currentLocation === 'footer' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground hover:bg-muted/60' }}"
        >
            <x-lucide-panel-bottom class="size-4" />
            Footer Menus
            <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs rounded-full {{ $currentLocation === 'footer' ? 'bg-primary-foreground/20 text-primary-foreground' : 'bg-muted text-muted-foreground' }}">
                {{ $footerCount }}
            </span>
        </a>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Location</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body
                    x-data="sortableList({
                        reorderUrl: '{{ route('admin.reorder', 'menus') }}',
                        csrfToken: '{{ csrf_token() }}'
                    })"
                >
                    @forelse ($menus as $menu)
                        <x-ui.table-row data-id="{{ $menu->id }}">
                            <x-ui.table-cell class="font-medium">
                                <div class="flex items-center gap-2">
                                    <button type="button" data-drag-handle class="text-muted-foreground hover:text-foreground shrink-0 cursor-grab">
                                        <x-lucide-grip-vertical class="size-4" />
                                    </button>
                                    <div>
                                        <div class="font-medium text-foreground">{{ $menu->title }}</div>
                                        @if($menu->url && $menu->url !== '#')
                                            <div class="text-xs text-muted-foreground font-mono">{{ $menu->url }}</div>
                                        @endif
                                    </div>
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $menu->location === 'footer' ? 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-500/20' }}">
                                    @if($menu->location === 'footer')
                                        <x-lucide-panel-bottom class="size-3" />
                                        Footer
                                    @else
                                        <x-lucide-layout-grid class="size-3" />
                                        Navbar
                                    @endif
                                </span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $menu->status ? 'default' : 'secondary' }}">
                                    {{ $menu->status ? 'Active' : 'Hidden' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.menus.edit', $menu) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    @if($menu->is_deletable)
                                    <form
                                        action="{{ route('admin.menus.destroy', $menu) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            type="submit"
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                        >
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                    @endif
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="4" class="text-muted-foreground py-6 text-center">
                                No menus found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if ($menus->hasPages())
            <x-ui.card-footer class="border-t p-4"> {{ $menus->links() }} </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
