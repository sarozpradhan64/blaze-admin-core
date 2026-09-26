<x-layouts.admin title="{{ isset($project) ? 'Edit Project' : 'Add Project' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($project) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    @php

        // if ($errors->any()) {
        //     dd($errors->all());
        // }
    @endphp

    <div class="max-w-5xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($project) ? 'Edit Project' : 'Add New Project' }}</h2>
        </div>

        <x-ui.tabs value="general">
            <x-ui.tabs-list class="mb-4">
                <x-ui.tabs-trigger value="general">General</x-ui.tabs-trigger>

            </x-ui.tabs-list>

            <x-ui.tabs-content value="general">
                <form
                    action="{{ isset($project) ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($project))
                        @method('PUT')
                    @endif

                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="md:col-span-2 space-y-6">
                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>General Information</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <x-ui.field>
                                        <x-ui.field-label for="title">Project Title</x-ui.field-label>
                                        <x-ui.input id="title" name="title"
                                            value="{{ old('title', $project->title ?? '') }}" />
                                        <x-ui.field-error name="title" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="short_description">Short Description</x-ui.field-label>
                                        <x-ui.textarea id="short_description" name="short_description"
                                            rows="3">{{ old('short_description', $project->short_description ?? '') }}</x-ui.textarea>
                                        <x-ui.field-error name="short_description" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="description">Full Description</x-ui.field-label>
                                        <x-ui.rich-text-editor name="description" :value="old('description', $project->description ?? '')" />
                                        <x-ui.field-error name="description" />
                                    </x-ui.field>
                                </x-ui.card-content>
                            </x-ui.card>


                        </div>

                        <div class="space-y-6">
                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Featured Image</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content>
                                    <x-ui.file-upload name="featured_image"
                                        accept="image/jpeg,image/png,image/gif,image/webp" :current="old('featured_image', $project->featured_image ?? null)" />
                                    <x-ui.field-error name="featured_image" />
                                </x-ui.card-content>
                            </x-ui.card>

                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Organization</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <x-ui.field>
                                        <x-ui.field-label for="project_category_id">Category</x-ui.field-label>
                                        <x-ui.select name="project_category_id">
                                            <x-ui.select-trigger>
                                                <x-ui.select-value placeholder="Select a category" />
                                            </x-ui.select-trigger>
                                            <x-ui.select-content>
                                                <x-ui.select-item value="">None</x-ui.select-item>
                                                @foreach ($categories as $cat)
                                                    <x-ui.select-item value="{{ $cat->id }}" :selected="old(
                                                        'project_category_id',
                                                        $project->project_category_id ?? '',
                                                    ) == $cat->id">
                                                        {{ $cat->name }}
                                                    </x-ui.select-item>
                                                @endforeach
                                            </x-ui.select-content>
                                        </x-ui.select>
                                        <x-ui.field-error name="project_category_id" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="project_status">Phase / Status</x-ui.field-label>
                                        <x-ui.select name="project_status" :value="old('project_status', $project->project_status ?? '')">
                                            <x-ui.select-trigger>
                                                <x-ui.select-value placeholder="Select Phase" />
                                            </x-ui.select-trigger>
                                            <x-ui.select-content>
                                                <x-ui.select-item value="upcoming"
                                                    :selected="old('project_status', $project->project_status ?? '') === 'upcoming'">Upcoming</x-ui.select-item>
                                                <x-ui.select-item value="ongoing"
                                                    :selected="old('project_status', $project->project_status ?? '') === 'ongoing'">Ongoing</x-ui.select-item>
                                                <x-ui.select-item value="completed"
                                                    :selected="old('project_status', $project->project_status ?? '') === 'completed'">Completed</x-ui.select-item>
                                            </x-ui.select-content>
                                        </x-ui.select>
                                        <x-ui.field-error name="project_status" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="tags">Tags</x-ui.field-label>
                                        <x-admin::tag-picker :value="old('tags', isset($project) ? $project->tagList() : '')" placeholder="Search or create tags" />
                                        <x-ui.field-error name="tags" />
                                    </x-ui.field>

                                </x-ui.card-content>
                            </x-ui.card>

                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Visibility</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <x-ui.label for="status" class="flex flex-col space-y-1">
                                            <span>Active</span>
                                            <span class="font-normal text-xs text-muted-foreground">Publish to
                                                website</span>
                                        </x-ui.label>
                                        <x-ui.switch id="status" name="status" value="1" :checked="old('status', $project->status ?? true)" />
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <x-ui.label for="is_featured" class="flex flex-col space-y-1">
                                            <span>Featured</span>
                                            <span class="font-normal text-xs text-muted-foreground">Show on
                                                homepage</span>
                                        </x-ui.label>
                                        <x-ui.switch id="is_featured" name="is_featured" value="1"
                                            :checked="old('is_featured', $project->is_featured ?? false)" />
                                    </div>
                                </x-ui.card-content>
                                <x-ui.card-footer class="border-t bg-muted/50 flex justify-end gap-2 p-4">
                                    <x-ui.button variant="outline"
                                        href="{{ route('admin.projects.index') }}">Cancel</x-ui.button>
                                    <x-ui.button type="submit">Save Project</x-ui.button>
                                </x-ui.card-footer>
                            </x-ui.card>
                        </div>
                    </div>
                </form>
            </x-ui.tabs-content>


        </x-ui.tabs>
    </div>
</x-layouts.admin>
