<x-layouts.admin title="Enquiry #{{ $enquiry->id }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.enquiries.index') }}">Enquiries</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>#{{ $enquiry->id }} — {{ $enquiry->name }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="grid gap-6 md:grid-cols-3 max-w-5xl">

        {{-- Left: Message Details --}}
        <div class="md:col-span-2 space-y-6">

            <x-ui.card>
                <x-ui.card-header class="flex flex-row items-start justify-between">
                    <div>
                        <x-ui.card-title class="text-lg">{{ $enquiry->subject ?? 'No Subject' }}</x-ui.card-title>
                        <x-ui.card-description>
                            From <span class="text-foreground font-medium">{{ $enquiry->name }}</span>
                            &mdash; {{ $enquiry->created_at->format('d M Y, H:i') }}
                        </x-ui.card-description>
                    </div>
                    <div class="flex gap-2">
                        @php
                            $statusVariant = match ($enquiry->status) {
                                'new' => 'default',
                                'converted' => 'default',
                                'spam' => 'destructive',
                                default => 'secondary',
                            };
                        @endphp
                        <x-ui.badge
                            variant="{{ $statusVariant }}">{{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}</x-ui.badge>
                        <x-ui.badge
                            variant="{{ $enquiry->priority === 'urgent' ? 'destructive' : ($enquiry->priority === 'high' ? 'default' : 'outline') }}">
                            {{ ucfirst($enquiry->priority) }}
                        </x-ui.badge>
                    </div>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-muted-foreground mb-1">Email</p>
                            <a href="mailto:{{ $enquiry->email }}"
                                class="font-medium text-primary hover:underline">{{ $enquiry->email }}</a>
                        </div>
                        <div>
                            <p class="text-muted-foreground mb-1">Phone</p>
                            <p class="font-medium">{{ $enquiry->phone }}</p>
                        </div>
                        @if ($enquiry->company)
                            <div>
                                <p class="text-muted-foreground mb-1">Company</p>
                                <p class="font-medium">{{ $enquiry->company }}</p>
                            </div>
                        @endif
                        @if ($enquiry->source)
                            <div>
                                <p class="text-muted-foreground mb-1">Source</p>
                                <p class="font-medium">{{ $enquiry->source }}</p>
                            </div>
                        @endif
                        @if ($enquiry->service)
                            <div>
                                <p class="text-muted-foreground mb-1">Related Service</p>
                                <a href="{{ route('admin.services.edit', $enquiry->service_id) }}"
                                    class="font-medium text-primary hover:underline">
                                    {{ $enquiry->service->title }}
                                </a>
                            </div>
                        @endif
                        @if ($enquiry->project)
                            <div>
                                <p class="text-muted-foreground mb-1">Related Project</p>
                                <a href="{{ route('admin.projects.edit', $enquiry->project_id) }}"
                                    class="font-medium text-primary hover:underline">
                                    {{ $enquiry->project->title }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <x-ui.separator />

                    <div>
                        <p class="text-sm text-muted-foreground mb-2">Message</p>
                        <div class="bg-muted/40 rounded-lg p-4 text-sm leading-relaxed whitespace-pre-wrap">
                            {{ $enquiry->message }}</div>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            {{-- Admin Notes --}}
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Admin Notes</x-ui.card-title>
                    <x-ui.card-description>Internal notes — not visible to the customer.</x-ui.card-description>
                </x-ui.card-header>
                <form action="{{ route('admin.enquiries.update', $enquiry) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Keep existing status/priority/assigned_to unchanged when only updating notes --}}
                    <input type="hidden" name="status" value="{{ $enquiry->status }}">
                    <input type="hidden" name="priority" value="{{ $enquiry->priority }}">
                    <input type="hidden" name="assigned_to" value="{{ $enquiry->assigned_to }}">
                    <x-ui.card-content>
                        <x-ui.textarea name="admin_notes" rows="5"
                            placeholder="Write internal notes here…">{{ old('admin_notes', $enquiry->admin_notes) }}</x-ui.textarea>
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t bg-muted/50 flex justify-end p-4">
                        <x-ui.button type="submit" size="sm">Save Notes</x-ui.button>
                    </x-ui.card-footer>
                </form>
            </x-ui.card>
        </div>

        {{-- Right: Actions --}}
        <div class="space-y-6">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Update Status</x-ui.card-title>
                </x-ui.card-header>
                <form action="{{ route('admin.enquiries.update', $enquiry) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="admin_notes" value="{{ $enquiry->admin_notes }}">
                    <x-ui.card-content class="space-y-4">
                        <x-ui.field>
                            <x-ui.field-label>Status</x-ui.field-label>
                            <x-ui.select name="status" value="{{ $enquiry->status }}">
                                <x-ui.select-trigger><x-ui.select-value /></x-ui.select-trigger>
                                <x-ui.select-content>
                                    @foreach (['new', 'in_progress', 'contacted', 'converted', 'closed', 'spam'] as $s)
                                        <x-ui.select-item value="{{ $s }}">
                                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </x-ui.select-item>
                                    @endforeach
                                </x-ui.select-content>
                            </x-ui.select>
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Priority</x-ui.field-label>
                            <x-ui.select name="priority" value="{{ $enquiry->priority }}">
                                <x-ui.select-trigger><x-ui.select-value /></x-ui.select-trigger>
                                <x-ui.select-content>
                                    @foreach (['low', 'normal', 'high', 'urgent'] as $p)
                                        <x-ui.select-item value="{{ $p }}">
                                            {{ ucfirst($p) }}
                                        </x-ui.select-item>
                                    @endforeach
                                </x-ui.select-content>
                            </x-ui.select>
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label>Assign To</x-ui.field-label>
                            <x-ui.select name="assigned_to" value="{{ $enquiry->assigned_to }}"
                                placeholder="Unassigned">
                                <x-ui.select-trigger><x-ui.select-value
                                        placeholder="Unassigned" /></x-ui.select-trigger>
                                <x-ui.select-content>
                                    <x-ui.select-item value="">Unassigned</x-ui.select-item>
                                    @foreach ($users as $user)
                                        <x-ui.select-item value="{{ $user->id }}">
                                            {{ $user->name }}
                                        </x-ui.select-item>
                                    @endforeach
                                </x-ui.select-content>
                            </x-ui.select>
                        </x-ui.field>
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t bg-muted/50 flex flex-col gap-2 p-4">
                        <x-ui.button type="submit" class="w-full">Update Enquiry</x-ui.button>
                        <x-ui.button variant="outline" href="mailto:{{ $enquiry->email }}" class="w-full">
                            <x-slot:before><x-lucide-mail class="size-4" /></x-slot:before>
                            Reply by Email
                        </x-ui.button>
                    </x-ui.card-footer>
                </form>
            </x-ui.card>

            {{-- Metadata Card --}}
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Metadata</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">IP Address</span>
                        <span>{{ $enquiry->ip_address ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Received</span>
                        <span>{{ $enquiry->created_at->format('d M Y') }}</span>
                    </div>
                    @if ($enquiry->replied_at)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Replied At</span>
                            <span>{{ $enquiry->replied_at->format('d M Y, h:i A') }}</span>
                        </div>
                    @endif
                    @if ($enquiry->assignee)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Assigned To</span>
                            <span>{{ $enquiry->assignee->name }}</span>
                        </div>
                    @endif
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
