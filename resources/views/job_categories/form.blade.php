<x-layouts.admin title="{{ isset($jobCategory) ? 'Edit Category' : 'Create Category' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.job-categories.index') }}">Job Categories</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($jobCategory) ? 'Edit' : 'Create' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">{{ isset($jobCategory) ? 'Edit Category' : 'Create Category' }}</h2>
        <x-ui.button variant="outline" href="{{ route('admin.job-categories.index') }}">Back</x-ui.button>
    </div>

    <form
        action="{{ isset($jobCategory) ? route('admin.job-categories.update', $jobCategory) : route('admin.job-categories.store') }}"
        method="POST"
        class="space-y-6"
    >
        @csrf
        @if (isset($jobCategory))
            @method('PUT')
        @endif

        <div class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-2 space-y-6">
                <x-ui.card>
                    <x-ui.card-content class="space-y-4 pt-6">
                        <div class="space-y-2">
                            <x-ui.label for="title">Title *</x-ui.label>
                            <x-ui.input
                                id="title"
                                name="title"
                                value="{{ old('title', $jobCategory->title ?? '') }}"
                                required
                            />
                            @error('title')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>

            <div class="space-y-6">
                <x-ui.card>
                    <x-ui.card-content class="space-y-4 pt-6">
                        <div class="flex items-center justify-between">
                            <x-ui.label for="status" class="flex flex-col space-y-1">
                                <span>Published</span>
                                <span class="text-xs font-normal text-muted-foreground">Is this category visible?</span>
                            </x-ui.label>
                            <x-ui.switch
                                id="status"
                                name="status"
                                value="1"
                                :checked="old('status', $jobCategory->status ?? true)"
                            />
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t bg-muted/50 px-6 py-4">
                        <x-ui.button type="submit" class="w-full">
                            {{ isset($jobCategory) ? 'Update Category' : 'Create Category' }}
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </div>
        </div>
    </form>
</x-layouts.admin>
