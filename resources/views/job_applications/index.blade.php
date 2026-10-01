<x-layouts.admin title="Job Applications">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Job Applications</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Job Applications</h2>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Applicant</x-ui.table-head>
                        <x-ui.table-head>Job Title</x-ui.table-head>
                        <x-ui.table-head>Email</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Applied On</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($applications as $application)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $application->first_name }} {{ $application->last_name }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $application->job?->title ?? '-' }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $application->email }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $variant = match($application->status) {
                                        'pending' => 'secondary',
                                        'reviewed' => 'default',
                                        'shortlisted' => 'success',
                                        'hired' => 'success',
                                        'rejected' => 'destructive',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-ui.badge variant="{{ $variant }}" class="uppercase text-xs">
                                    {{ $application->status }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $application->created_at->format('M d, Y') }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="outline"
                                        size="sm"
                                        href="{{ route('admin.job-applications.show', $application) }}"
                                    >
                                        View
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.job-applications.destroy', $application) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this application?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            variant="ghost"
                                            size="sm"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                            type="submit"
                                        >
                                            Delete
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-muted-foreground py-6 text-center">
                                No applications found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if ($applications->hasPages())
            <x-ui.card-footer class="border-t p-4"> {{ $applications->links() }} </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
