<x-layouts.admin title="{{ isset($job) ? 'Edit Job' : 'Create Job' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.jobs.index') }}">Jobs</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($job) ? 'Edit' : 'Create' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">{{ isset($job) ? 'Edit Job' : 'Create Job' }}</h2>
        <x-ui.button variant="outline" href="{{ route('admin.jobs.index') }}">Back</x-ui.button>
    </div>

    <form
        action="{{ isset($job) ? route('admin.jobs.update', $job) : route('admin.jobs.store') }}"
        method="POST"
        class="space-y-6"
    >
        @csrf
        @if (isset($job))
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
                                value="{{ old('title', $job->title ?? '') }}"
                                required
                            />
                            @error('title')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="excerpt">Short Description (Excerpt)</x-ui.label>
                            <x-ui.textarea
                                id="excerpt"
                                name="excerpt"
                                rows="2"
                            >{{ old('excerpt', $job->excerpt ?? '') }}</x-ui.textarea>
                            @error('excerpt')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="description">Full Description</x-ui.label>
                            <x-ui.rich-text-editor
                                id="description"
                                name="description"
                                :value="old('description', $job->description ?? '')"
                            />
                            @error('description')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="requirements">Requirements</x-ui.label>
                            <x-ui.rich-text-editor
                                id="requirements"
                                name="requirements"
                                :value="old('requirements', $job->requirements ?? '')"
                            />
                            @error('requirements')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>

            <div class="space-y-6">
                <x-ui.card>
                    <x-ui.card-content class="space-y-4 pt-6">
                        <div class="flex items-center justify-between border-b pb-4">
                            <x-ui.label for="status" class="flex flex-col space-y-1">
                                <span>Published</span>
                                <span class="text-xs font-normal text-muted-foreground">Is this job open?</span>
                            </x-ui.label>
                            <x-ui.switch
                                id="status"
                                name="status"
                                value="1"
                                :checked="old('status', $job->status ?? true)"
                            />
                        </div>

                        <div class="space-y-2 pt-2">
                            <x-ui.label for="job_category_id">Category</x-ui.label>
                            <x-ui.select name="job_category_id" id="job_category_id" :value="old('job_category_id', $job->job_category_id ?? '')">
                                <x-ui.select-trigger>
                                    <x-ui.select-value placeholder="None" />
                                </x-ui.select-trigger>
                                <x-ui.select-content>
                                    <x-ui.select-item value="">None</x-ui.select-item>
                                    @foreach($categories as $cat)
                                        <x-ui.select-item :value="$cat->id">{{ $cat->title }}</x-ui.select-item>
                                    @endforeach
                                </x-ui.select-content>
                            </x-ui.select>
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="positions">Number of Positions</x-ui.label>
                            <x-ui.input
                                id="positions"
                                name="positions"
                                type="number"
                                min="1"
                                value="{{ old('positions', $job->positions ?? '') }}"
                            />
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="location">Location</x-ui.label>
                            <x-ui.input
                                id="location"
                                name="location"
                                value="{{ old('location', $job->location ?? '') }}"
                            />
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="salary">Salary</x-ui.label>
                            <x-ui.input
                                id="salary"
                                name="salary"
                                value="{{ old('salary', $job->salary ?? '') }}"
                                placeholder="e.g. Negotiable, $50,000/yr"
                            />
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="deadline">Application Deadline</x-ui.label>
                            <x-ui.input
                                id="deadline"
                                name="deadline"
                                type="date"
                                value="{{ old('deadline', isset($job) && $job->deadline ? $job->deadline->format('Y-m-d') : '') }}"
                            />
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t bg-muted/50 px-6 py-4">
                        <x-ui.button type="submit" class="w-full">
                            {{ isset($job) ? 'Update Job' : 'Create Job' }}
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </div>
        </div>
    </form>
</x-layouts.admin>
