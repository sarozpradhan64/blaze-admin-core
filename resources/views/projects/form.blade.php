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
                @if (isset($project))
                    <x-ui.tabs-trigger value="images">Images Gallery
                        ({{ $project->images()->count() }})</x-ui.tabs-trigger>
                    <x-ui.tabs-trigger value="videos">Videos ({{ $project->videos()->count() }})</x-ui.tabs-trigger>
                    <x-ui.tabs-trigger value="statistics">Statistics
                        ({{ $project->statistics()->count() }})</x-ui.tabs-trigger>
                @endif
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

                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Project Details</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <x-ui.field>
                                            <x-ui.field-label for="client_name">Client Name</x-ui.field-label>
                                            <x-ui.input id="client_name" name="client_name"
                                                value="{{ old('client_name', $project->client_name ?? '') }}" />
                                        </x-ui.field>
                                        <x-ui.field>
                                            <x-ui.field-label for="location">Location</x-ui.field-label>
                                            <x-ui.input id="location" name="location"
                                                value="{{ old('location', $project->location ?? '') }}" />
                                        </x-ui.field>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <x-ui.field>
                                            <x-ui.field-label for="project_type">Project Type</x-ui.field-label>
                                            <x-ui.input id="project_type" name="project_type"
                                                value="{{ old('project_type', $project->project_type ?? '') }}"
                                                placeholder="e.g. Commercial" />
                                        </x-ui.field>
                                        <x-ui.field>
                                            <x-ui.field-label for="website_url">Website URL</x-ui.field-label>
                                            <x-ui.input id="website_url" name="website_url"
                                                value="{{ old('website_url', $project->website_url ?? '') }}" />
                                        </x-ui.field>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <x-ui.field>
                                            <x-ui.field-label for="start_date">Start Date</x-ui.field-label>
                                            <x-ui.input type="date" id="start_date" name="start_date"
                                                value="{{ old('start_date', $project->start_date ?? '') }}" />
                                        </x-ui.field>
                                        <x-ui.field>
                                            <x-ui.field-label for="completion_date">Completion Date</x-ui.field-label>
                                            <x-ui.input type="date" id="completion_date" name="completion_date"
                                                value="{{ old('completion_date', $project->completion_date ?? '') }}" />
                                        </x-ui.field>
                                    </div>
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

            @if (isset($project))
                <x-ui.tabs-content value="images">
                    <x-ui.card>
                        <x-ui.card-header class="flex flex-row justify-between items-center">
                            <div>
                                <x-ui.card-title>Project Images</x-ui.card-title>
                                <x-ui.card-description>Manage gallery images for this project.</x-ui.card-description>
                            </div>
                            <x-ui.button size="sm"
                                href="{{ route('admin.project-images.index', ['project_id' => $project->id]) }}">
                                <x-lucide-external-link class="mr-2 size-4" />
                                Manage Images
                            </x-ui.button>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <p class="text-sm text-muted-foreground mb-4">Click "Manage Images" to open the dedicated
                                CRUD for images and upload new ones.</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($project->images()->take(8)->get() as $image)
                                    <div
                                        class="relative group aspect-square rounded-md overflow-hidden bg-muted border">
                                        <!-- Placeholder for image, in real app would use $image->image -->
                                        <div
                                            class="absolute inset-0 flex items-center justify-center text-muted-foreground text-xs text-center p-2">
                                            {{ $image->image }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </x-ui.tabs-content>

                <x-ui.tabs-content value="videos">
                    <x-ui.card>
                        <x-ui.card-header class="flex flex-row justify-between items-center">
                            <div>
                                <x-ui.card-title>Project Videos</x-ui.card-title>
                                <x-ui.card-description>Manage videos embedded in this project.</x-ui.card-description>
                            </div>
                            <x-ui.button size="sm"
                                href="{{ route('admin.project-videos.index', ['project_id' => $project->id]) }}">
                                <x-lucide-external-link class="mr-2 size-4" />
                                Manage Videos
                            </x-ui.button>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <ul class="space-y-2">
                                @forelse($project->videos as $video)
                                    <li class="flex items-center gap-2 text-sm p-2 border rounded-md">
                                        <x-lucide-video class="size-4 text-muted-foreground" />
                                        <span class="font-medium">{{ $video->title ?? 'Untitled Video' }}</span>
                                        <span class="text-muted-foreground">({{ $video->video_type }})</span>
                                    </li>
                                @empty
                                    <li class="text-sm text-muted-foreground">No videos added yet.</li>
                                @endforelse
                            </ul>
                        </x-ui.card-content>
                    </x-ui.card>
                </x-ui.tabs-content>

                <x-ui.tabs-content value="statistics">
                    <x-ui.card>
                        <x-ui.card-header class="flex flex-row justify-between items-center">
                            <div>
                                <x-ui.card-title>Project Statistics</x-ui.card-title>
                                <x-ui.card-description>Manage quick facts (e.g. Area, Duration) for this
                                    project.</x-ui.card-description>
                            </div>
                            <x-ui.button size="sm"
                                href="{{ route('admin.project-statistics.index', ['project_id' => $project->id]) }}">
                                <x-lucide-external-link class="mr-2 size-4" />
                                Manage Statistics
                            </x-ui.button>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @forelse($project->statistics as $stat)
                                    <div class="p-4 border rounded-md text-center bg-muted/20">
                                        <div class="text-xl font-bold text-primary">{{ $stat->value }}</div>
                                        <div class="text-sm text-muted-foreground mt-1">{{ $stat->label }}</div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-sm text-muted-foreground text-center py-4">No
                                        statistics added yet.</div>
                                @endforelse
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </x-ui.tabs-content>
            @endif
        </x-ui.tabs>
    </div>
</x-layouts.admin>
