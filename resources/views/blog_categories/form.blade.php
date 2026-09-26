<x-layouts.admin title="{{ isset($category) ? 'Edit Blog Category' : 'Add Blog Category' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.blogs.index') }}">Blog</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.blog-categories.index') }}">
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
                {{ isset($category) ? 'Edit Blog Category' : 'Add New Blog Category' }}
            </h2>
        </div>

        <form
            action="{{ isset($category) ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store') }}"
            method="POST"
        >
            @csrf
            @if (isset($category))
                @method('PUT')
            @endif

            <x-ui.card>
                <x-ui.card-content class="space-y-4">
                    <x-ui.field>
                        <x-ui.field-label for="name">Category Name</x-ui.field-label>
                        <x-ui.input id="name" name="name" value="{{ old('name', $category->name ?? '') }}" />
                        <x-ui.field-error name="name" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label for="description">Description</x-ui.field-label>
                        <x-ui.textarea
                            id="description"
                            name="description"
                            rows="4"
                        >
                            {{ old('description', $category->description ?? '') }}</x-ui.textarea>
                        <x-ui.field-error name="description" />
                    </x-ui.field>

                    <div class="flex items-center justify-between">
                        <x-ui.label for="status" class="flex flex-col space-y-1">
                            <span>Active</span>
                            <span class="text-muted-foreground text-xs font-normal">Available for new posts</span>
                        </x-ui.label>
                        <x-ui.switch
                            id="status"
                            name="status"
                            value="1"
                            :checked="old('status', $category->status ?? true)"
                        />
                    </div>
                </x-ui.card-content>
                <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                    <x-ui.button variant="outline" href="{{ route('admin.blog-categories.index') }}">
                        Cancel</x-ui.button>
                    <x-ui.button type="submit">Save Category</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-layouts.admin>
