<x-layouts.admin title="Service Features">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.services.index') }}">Services</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Features</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Service Features</h2>
        <x-ui.button href="{{ route('admin.service-features.create', ['service_id' => request('service_id')]) }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Feature
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Parent Service</x-ui.table-head>
                        <x-ui.table-head>Order</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($features as $feature)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $feature->title }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <a href="{{ route('admin.service-features.index', ['service_id' => $feature->service_id]) }}" class="hover:underline text-primary">
                                    {{ $feature->service?->title }}
                                </a>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $feature->sort_order }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.service-features.edit', $feature) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.service-features.destroy', $feature) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="ghost" size="icon" class="text-destructive hover:text-destructive hover:bg-destructive/10" type="submit">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="4" class="text-center py-6 text-muted-foreground">
                                No service features found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if($features->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $features->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
