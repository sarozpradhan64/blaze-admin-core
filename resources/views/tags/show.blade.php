<x-layouts.admin title="Tag: {{ $tag->name }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.tags.manage') }}">Tags</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ $tag->name }}</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">{{ $tag->name }}</h2>
            <p class="text-muted-foreground text-sm">
                {{ $usedItems->count() }} {{ Str::plural('item', $usedItems->count()) }} use this tag.
            </p>
        </div>
        <x-ui.button variant="outline" href="{{ route('admin.tags.manage') }}">
            <x-slot:before>
                <x-lucide-arrow-left class="size-4" />
            </x-slot:before>
            Back to Tags
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Used Items</x-ui.card-title>
            <x-ui.card-description>Services and projects currently assigned to this tag.</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content class="p-0">
            <div class="overflow-x-auto">
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Type</x-ui.table-head>
                            <x-ui.table-head>Item</x-ui.table-head>
                            <x-ui.table-head>Slug</x-ui.table-head>
                            <x-ui.table-head class="text-right">Action</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse ($usedItems as $item)
                            <x-ui.table-row class="group">
                                <x-ui.table-cell>
                                    <x-ui.badge variant="outline">{{ $item['type'] }}</x-ui.badge>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="font-medium">{{ $item['title'] }}</x-ui.table-cell>
                                <x-ui.table-cell class="text-muted-foreground">{{ $item['slug'] }}</x-ui.table-cell>
                                <x-ui.table-cell class="text-right">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ $item['url'] }}"
                                        title="Edit {{ strtolower($item['type']) }}"
                                    >
                                        <x-lucide-external-link class="size-4" />
                                    </x-ui.button>
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @empty
                            <x-ui.table-row>
                                <x-ui.table-cell colspan="4" class="text-muted-foreground py-10 text-center">
                                    No services or projects use this tag yet.
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</x-layouts.admin>
