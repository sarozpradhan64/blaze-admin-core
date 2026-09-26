<x-layouts.admin title="{{ isset($category) ? 'Edit Category' : 'Add Category' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.project-categories.index') }}">
                        Categories</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($category) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">
                {{ isset($category) ? 'Edit Category' : 'Add New Category' }}
            </h2>
        </div>

        <form
            action="{{ isset($category) ? route('admin.project-categories.update', $category) : route('admin.project-categories.store') }}"
            method="POST"
        >
            @csrf
            @if (isset($category))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <x-ui.card>
                    <x-ui.card-content class="space-y-4 pt-6">
                        <x-ui.field>
                            <x-ui.field-label for="name">Name</x-ui.field-label>
                            <x-ui.input id="name" name="name" value="{{ old('name', $category->name ?? '') }}" />
                            <x-ui.field-error name="name" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label for="description">Description</x-ui.field-label>
                            <x-ui.textarea
                                id="description"
                                name="description"
                                rows="3"
                            >
                                {{ old('description', $category->description ?? '') }}</x-ui.textarea>
                            <x-ui.field-error name="description" />
                        </x-ui.field>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center pt-8">
                                <x-ui.label for="status" class="flex w-full flex-col space-y-1">
                                    <span>Active Status</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="status"
                                    name="status"
                                    value="1"
                                    :checked="old('status', $category->status ?? true)"
                                />
                            </div>
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                        <x-ui.button variant="outline" href="{{ route('admin.project-categories.index') }}">
                            Cancel</x-ui.button>
                        <x-ui.button type="submit">Save Category</x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </div>
        </form>
    </div>
</x-layouts.admin>
