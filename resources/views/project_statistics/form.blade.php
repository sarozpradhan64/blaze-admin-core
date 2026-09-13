<x-layouts.admin title="{{ isset($statistic) ? 'Edit Statistic' : 'Add Statistic' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.projects.index') }}">Projects</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.project-statistics.index') }}">Statistics</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($statistic) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <h2 class="text-2xl font-bold tracking-tight mb-6">{{ isset($statistic) ? 'Edit Statistic' : 'Add Project Statistic' }}</h2>

        <form action="{{ isset($statistic) ? route('admin.project-statistics.update', $statistic) : route('admin.project-statistics.store') }}" method="POST">
            @csrf
            @if(isset($statistic)) @method('PUT') @endif

            <x-ui.card>
                <x-ui.card-content class="space-y-4 pt-6">
                    <x-ui.field>
                        <x-ui.field-label for="project_id">Project</x-ui.field-label>
                        <x-ui.select name="project_id">
                            <x-ui.select-trigger>
                                <x-ui.select-value placeholder="Select project" />
                            </x-ui.select-trigger>
                            <x-ui.select-content>
                                @foreach($projects as $project)
                                    <x-ui.select-item value="{{ $project->id }}" :selected="old('project_id', $statistic->project_id ?? $selectedProject) == $project->id">
                                        {{ $project->title }}
                                    </x-ui.select-item>
                                @endforeach
                            </x-ui.select-content>
                        </x-ui.select>
                        <x-ui.field-error name="project_id" />
                    </x-ui.field>

                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.field>
                            <x-ui.field-label for="label">Label</x-ui.field-label>
                            <x-ui.input id="label" name="label" value="{{ old('label', $statistic->label ?? '') }}" placeholder="e.g. Project Area" />
                            <x-ui.field-error name="label" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.field-label for="value">Value</x-ui.field-label>
                            <x-ui.input id="value" name="value" value="{{ old('value', $statistic->value ?? '') }}" placeholder="e.g. 50,000 sq.ft" />
                            <x-ui.field-error name="value" />
                        </x-ui.field>
                    </div>

                </x-ui.card-content>
                <x-ui.card-footer class="border-t bg-muted/50 flex justify-end gap-2 p-4">
                    <x-ui.button variant="outline" href="{{ route('admin.project-statistics.index') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">Save Statistic</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-layouts.admin>
