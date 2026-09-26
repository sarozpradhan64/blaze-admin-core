<x-layouts.admin title="{{ isset($video) ? 'Edit Video' : 'Add Video' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.project-videos.index') }}">Videos</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($video) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <h2 class="mb-6 text-2xl font-bold tracking-tight">{{ isset($video) ? 'Edit Video' : 'Add Project Video' }}</h2>

        <form
            action="{{ isset($video) ? route('admin.project-videos.update', $video) : route('admin.project-videos.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($video)) @method('PUT') @endif

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
                                        :selected="old('project_id', $video->project_id ?? $selectedProject) == $project->id"
                                    >
                                        {{ $project->title }}
                                    </x-ui.select-item>
                                @endforeach
                            </x-ui.select-content>
                        </x-ui.select>
                        <x-ui.field-error name="project_id" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label for="title">Title (Optional)</x-ui.field-label>
                        <x-ui.input id="title" name="title" value="{{ old('title', $video->title ?? '') }}" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label for="video_type">Video Type</x-ui.field-label>
                        <x-ui.select name="video_type">
                            <x-ui.select-trigger>
                                <x-ui.select-value placeholder="Select type" />
                            </x-ui.select-trigger>
                            <x-ui.select-content>
                                <x-ui.select-item
                                    value="youtube"
                                    :selected="old('video_type', $video->video_type ?? '') === 'youtube'"
                                >
                                    YouTube</x-ui.select-item>
                                <x-ui.select-item
                                    value="vimeo"
                                    :selected="old('video_type', $video->video_type ?? '') === 'vimeo'"
                                >
                                    Vimeo</x-ui.select-item>
                                <x-ui.select-item
                                    value="uploaded"
                                    :selected="old('video_type', $video->video_type ?? '') === 'uploaded'"
                                >
                                    Uploaded</x-ui.select-item>
                            </x-ui.select-content>
                        </x-ui.select>
                        <x-ui.field-error name="video_type" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label for="video_url">Video URL / Path</x-ui.field-label>
                        <x-ui.input
                            id="video_url"
                            name="video_url"
                            value="{{ old('video_url', $video->video_url ?? '') }}"
                            placeholder="https://www.youtube.com/watch?v=..."
                        />
                        <x-ui.field-error name="video_url" />
                    </x-ui.field>

                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.field>
                            <x-ui.field-label for="thumbnail">Thumbnail (Optional)</x-ui.field-label>
                            <x-ui.file-upload
                                name="thumbnail"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                :current="old('thumbnail', $video->thumbnail ?? null)"
                            />
                            <x-ui.field-error name="thumbnail" />
                        </x-ui.field>
                    </div>
                </x-ui.card-content>
                <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                    <x-ui.button variant="outline" href="{{ route('admin.project-videos.index') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">Save Video</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-layouts.admin>
