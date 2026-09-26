<x-layouts.admin title="{{ isset($image) ? 'Edit Image' : 'Add Image' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.project-images.index') }}">Images</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($image) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <h2 class="mb-6 text-2xl font-bold tracking-tight">{{ isset($image) ? 'Edit Image' : 'Add Project Image' }}</h2>

        <form
            action="{{ isset($image) ? route('admin.project-images.update', $image) : route('admin.project-images.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($image)) @method('PUT') @endif

            <x-ui.card>
                <x-ui.card-content class="space-y-4 pt-6">
                    <x-ui.field>
                        <x-ui.field-label for="project_id">Project</x-ui.field-label>
                        <x-ui.select name="project_id">
                            <x-ui.select-trigger>
                                <x-ui.select-value placeholder="Select project" />
                            </x-ui.select-trigger>
                            <x-ui.select-content>
                                @foreach ($projects as $project)
                                    <x-ui.select-item
                                        value="{{ $project->id }}"
                                        :selected="old('project_id', $image->project_id ?? $selectedProject) == $project->id"
                                    >
                                        {{ $project->title }}
                                    </x-ui.select-item>
                                @endforeach
                            </x-ui.select-content>
                        </x-ui.select>
                        <x-ui.field-error name="project_id" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label for="image">Image</x-ui.field-label>
                        <x-ui.file-upload
                            name="image"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            :current="old('image', $image->image ?? null)"
                        />
                        <x-ui.field-description>Upload an image for this project.</x-ui.field-description>
                        <x-ui.field-error name="image" />
                    </x-ui.field>

                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.field>
                            <x-ui.field-label for="alt_text">Alt Text</x-ui.field-label>
                            <x-ui.input
                                id="alt_text"
                                name="alt_text"
                                value="{{ old('alt_text', $image->alt_text ?? '') }}"
                            />
                        </x-ui.field>
                    </div>

                    <x-ui.field>
                        <x-ui.field-label for="caption">Caption</x-ui.field-label>
                        <x-ui.input id="caption" name="caption" value="{{ old('caption', $image->caption ?? '') }}" />
                    </x-ui.field>

                    <div class="flex items-center justify-between pt-2">
                        <x-ui.label for="is_featured" class="flex flex-col space-y-1">
                            <span>Featured Image</span>
                            <span class="text-muted-foreground text-xs font-normal">Use as the project thumbnail</span>
                        </x-ui.label>
                        <x-ui.switch
                            id="is_featured"
                            name="is_featured"
                            value="1"
                            :checked="old('is_featured', $image->is_featured ?? false)"
                        />
                    </div>
                </x-ui.card-content>
                <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                    <x-ui.button variant="outline" href="{{ route('admin.project-images.index') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">Save Image</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-layouts.admin>
