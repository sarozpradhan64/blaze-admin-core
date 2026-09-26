<x-layouts.admin title="Edit Tag">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.tags.manage') }}">Tags</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Edit Tag</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold tracking-tight">Edit Tag</h2>
            <p class="text-sm text-muted-foreground">Update the name used for this tag across your content.</p>
        </div>

        <x-ui.card>
            <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
                @csrf
                @method('PUT')
                <x-ui.card-content class="space-y-4 pt-6">
                    <x-ui.field>
                        <x-ui.field-label for="name">Name</x-ui.field-label>
                        <x-ui.input id="name" name="name" value="{{ old('name', $tag->name) }}" autofocus />
                        <x-ui.field-error name="name" />
                    </x-ui.field>
                    <div class="rounded-md border bg-muted/40 px-3 py-2 text-sm">
                        <span class="text-muted-foreground">Current slug:</span>
                        <span class="font-mono">{{ $tag->slug }}</span>
                    </div>
                </x-ui.card-content>
                <x-ui.card-footer class="flex justify-end gap-2 border-t bg-muted/50 p-4">
                    <x-ui.button variant="outline" href="{{ route('admin.tags.manage') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">Save Changes</x-ui.button>
                </x-ui.card-footer>
            </form>
        </x-ui.card>
    </div>
</x-layouts.admin>
