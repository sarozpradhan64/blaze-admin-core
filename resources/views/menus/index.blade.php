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

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold tracking-tight">Menus</h2>
        <x-ui.button href="{{ route('admin.menus.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Menu
        </x-ui.button>
    </div>

    @if(!empty($missingRoutes))
        <div class="mb-6 rounded-xl border border-dashed border-primary/30 bg-primary/5 p-4 sm:p-5">
            <div class="space-y-1">
                <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                    <x-lucide-compass class="size-4 text-primary" />
                    Missing Root Paths Suggested for Menus
                </h3>
                <p class="text-xs text-muted-foreground">
                    These static routes are registered in your application but not yet linked to any top-level menu. Click to quickly add them as a base menu.
                </p>
            </div>
            <div class="mt-4 flex flex-wrap gap-2.5">
                @foreach($missingRoutes as $route)
                    <div class="flex items-center gap-3 rounded-lg border border-border bg-background px-3 py-2 shadow-2xs">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-foreground">{{ $route['label'] }}</span>
                            <span class="text-[11px] font-mono text-muted-foreground">{{ $route['path'] }}</span>
                        </div>
                        <x-ui.button
                            size="sm"
                            variant="secondary"
                            class="h-7 text-xs ml-1"
                            href="{{ route('admin.menus.create', ['title' => $route['label'], 'url' => $route['path']]) }}"
                        >
                            <x-slot:before>
                                <x-lucide-plus class="size-3" />
                            </x-slot:before>
                            Add Menu
                        </x-ui.button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Name</x-ui.table-head>
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
                            <x-ui.table-cell colspan="3" class="text-muted-foreground py-6 text-center">
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
