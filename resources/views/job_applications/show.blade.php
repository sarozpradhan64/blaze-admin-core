<x-layouts.admin title="View Application">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.job-applications.index') }}">Job Applications</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>View</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Applicant: {{ $jobApplication->first_name }} {{ $jobApplication->last_name }}</h2>
        <x-ui.button variant="outline" href="{{ route('admin.job-applications.index') }}">Back to List</x-ui.button>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div class="md:col-span-2 space-y-6">
            <x-ui.card>
                <x-ui.card-header>
                    <h3 class="text-lg font-semibold">Application Details</h3>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-muted-foreground text-sm font-semibold uppercase tracking-wider">Job Applied For</span>
                            <div class="font-medium mt-1">{{ $jobApplication->job?->title ?? 'Deleted Job' }}</div>
                        </div>
                        <div>
                            <span class="text-muted-foreground text-sm font-semibold uppercase tracking-wider">Applied On</span>
                            <div class="font-medium mt-1">{{ $jobApplication->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                        <div>
                            <span class="text-muted-foreground text-sm font-semibold uppercase tracking-wider">Email</span>
                            <div class="font-medium mt-1">
                                <a href="mailto:{{ $jobApplication->email }}" class="text-primary hover:underline">{{ $jobApplication->email }}</a>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted-foreground text-sm font-semibold uppercase tracking-wider">Phone</span>
                            <div class="font-medium mt-1">{{ $jobApplication->phone ?? 'N/A' }}</div>
                        </div>
                    </div>

                    @if($jobApplication->message)
                    <div class="pt-4 border-t mt-4">
                        <span class="text-muted-foreground text-sm font-semibold uppercase tracking-wider">Message</span>
                        <div class="mt-2 text-sm whitespace-pre-wrap">{{ $jobApplication->message }}</div>
                    </div>
                    @endif
                    
                    @if($jobApplication->cover_letter)
                    <div class="pt-4 border-t mt-4">
                        <span class="text-muted-foreground text-sm font-semibold uppercase tracking-wider">Cover Letter</span>
                        <div class="mt-2 text-sm whitespace-pre-wrap">{{ $jobApplication->cover_letter }}</div>
                    </div>
                    @endif
                </x-ui.card-content>
            </x-ui.card>

            <x-ui.card>
                <x-ui.card-header>
                    <h3 class="text-lg font-semibold">Documents</h3>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border rounded-lg flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-primary/10 p-2 rounded-md">
                                    <x-lucide-file-text class="size-5 text-primary" />
                                </div>
                                <div>
                                    <div class="font-medium">CV / Resume</div>
                                    <div class="text-xs text-muted-foreground">Required Document</div>
                                </div>
                            </div>
                            @if($jobApplication->cv_url)
                                <x-ui.button size="sm" variant="outline" href="{{ $jobApplication->cv_url }}" target="_blank">View</x-ui.button>
                            @else
                                <span class="text-sm text-destructive">Missing</span>
                            @endif
                        </div>

                        <div class="p-4 border rounded-lg flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-primary/10 p-2 rounded-md">
                                    <x-lucide-award class="size-5 text-primary" />
                                </div>
                                <div>
                                    <div class="font-medium">License</div>
                                    <div class="text-xs text-muted-foreground">Optional</div>
                                </div>
                            </div>
                            @if($jobApplication->license_url)
                                <x-ui.button size="sm" variant="outline" href="{{ $jobApplication->license_url }}" target="_blank">View</x-ui.button>
                            @else
                                <span class="text-sm text-muted-foreground">N/A</span>
                            @endif
                        </div>

                        @if($jobApplication->other_documents && is_array($jobApplication->other_documents))
                            @foreach($jobApplication->other_documents as $doc)
                            <div class="p-4 border rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-primary/10 p-2 rounded-md">
                                        <x-lucide-file-archive class="size-5 text-primary" />
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $doc['title'] ?? 'Other Document' }}</div>
                                        <div class="text-xs text-muted-foreground">Additional</div>
                                    </div>
                                </div>
                                <x-ui.button size="sm" variant="outline" href="{{ Storage::disk('public')->url($doc['file']) }}" target="_blank">View</x-ui.button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card>
                <x-ui.card-header>
                    <h3 class="text-lg font-semibold">Update Status</h3>
                </x-ui.card-header>
                <form action="{{ route('admin.job-applications.status.update', $jobApplication) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <x-ui.card-content class="space-y-4">
                        <div class="space-y-2">
                            <x-ui.label for="status">Current Status</x-ui.label>
                            <x-ui.select name="status" id="status" :value="$jobApplication->status">
                                <x-ui.select-trigger>
                                    <x-ui.select-value placeholder="Select Status" />
                                </x-ui.select-trigger>
                                <x-ui.select-content>
                                    <x-ui.select-item value="pending">Pending</x-ui.select-item>
                                    <x-ui.select-item value="reviewed">Reviewed</x-ui.select-item>
                                    <x-ui.select-item value="shortlisted">Shortlisted</x-ui.select-item>
                                    <x-ui.select-item value="hired">Hired</x-ui.select-item>
                                    <x-ui.select-item value="rejected">Rejected</x-ui.select-item>
                                </x-ui.select-content>
                            </x-ui.select>
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t bg-muted/50 px-6 py-4">
                        <x-ui.button type="submit" class="w-full">Update Status</x-ui.button>
                    </x-ui.card-footer>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
