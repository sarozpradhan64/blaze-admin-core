<x-layouts.admin title="{{ isset($menu) ? 'Edit Menu' : 'Add Menu' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.menus.index') }}">Menus</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($menu) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-5xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">
                {{ isset($menu) ? 'Edit Menu' : 'Add New Menu' }}
            </h2>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="space-y-6 md:col-span-1">
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Menu Details</x-ui.card-title>
                    </x-ui.card-header>
                    <form
                        action="{{ isset($menu) ? route('admin.menus.update', $menu) : route('admin.menus.store') }}"
                        method="POST"
                    >
                        @csrf
                        @if (isset($menu))
                            @method('PUT')
                        @endif
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label for="title" required>Title</x-ui.field-label>
                                <x-ui.input id="title" name="title" value="{{ old('title', $menu->title ?? '') }}" />
                                <x-ui.field-error name="title" />
                            </x-ui.field>

                            <x-ui.field>
                                <div class="flex items-center justify-between">
                                    <x-ui.label for="status" class="flex flex-col space-y-1">
                                        <span>Active</span>
                                        <span class="text-muted-foreground text-xs font-normal">Show on website</span>
                                    </x-ui.label>
                                    <x-ui.switch
                                        id="status"
                                        name="status"
                                        value="1"
                                        :checked="old('status', $menu->status ?? true)"
                                    />
                                </div>
                            </x-ui.field>
                        </x-ui.card-content>
                        <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                            <x-ui.button variant="outline" href="{{ route('admin.menus.index') }}">Cancel</x-ui.button>
                            <x-ui.button type="submit">Save Menu</x-ui.button>
                        </x-ui.card-footer>
                    </form>
                </x-ui.card>
            </div>

            <div class="space-y-6 md:col-span-2">
                @if (isset($menu))
                    @include('admin-core::menus.builder', ['menu' => $menu, 'pages' => $pages ?? collect(), 'serviceCategories' => $serviceCategories ?? collect()])
                @else
                    <x-ui.card>
                        <x-ui.card-content class="text-muted-foreground py-10 text-center flex flex-col items-center justify-center min-h-[300px]">
                            <x-lucide-menu class="size-12 mb-4 opacity-20" />
                            <p>Save the menu first to add items.</p>
                        </x-ui.card-content>
                    </x-ui.card>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
