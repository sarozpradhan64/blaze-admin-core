<x-layouts.admin title="Message #{{ $message->id }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.contact-messages.index') }}">
                        Contact Messages</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>#{{ $message->id }} — {{ $message->name }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="grid max-w-5xl gap-6 md:grid-cols-3">
        {{-- Left: Message Details --}}
        <div class="space-y-6 md:col-span-2">
            <x-ui.card>
                <x-ui.card-header class="flex flex-row items-start justify-between">
                    <div>
                        <x-ui.card-title class="text-lg">{{ $message->subject ?? 'No Subject' }}</x-ui.card-title>
                        <x-ui.card-description>
                            From
                            <span class="text-foreground font-medium">{{ $message->name }}</span>
                            &mdash; {{ $message->created_at->format('d M Y, H:i') }}
                        </x-ui.card-description>
                    </div>
                    @php
                        $statusVariant = match ($message->status) {
                            'new' => 'default',
                            'replied' => 'default',
                            'spam' => 'destructive',
                            default => 'secondary',
                        };
                    @endphp
                    <x-ui.badge variant="{{ $statusVariant }}">{{ ucfirst($message->status) }}</x-ui.badge>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-muted-foreground mb-1">Email</p>
                            <a
                                href="mailto:{{ $message->email }}"
                                class="text-primary font-medium hover:underline"
                            >{{ $message->email }}</a>
                        </div>
                        @if ($message->phone ?? false)
                            <div>
                                <p class="text-muted-foreground mb-1">Phone</p>
                                <p class="font-medium">{{ $message->phone }}</p>
                            </div>
                        @endif
                    </div>

                    <x-ui.separator />

                    <div>
                        <p class="text-muted-foreground mb-2 text-sm">Message</p>
                        <div class="bg-muted/40 rounded-lg p-4 text-sm leading-relaxed whitespace-pre-wrap">
                            {{ $message->message }}
                        </div>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        {{-- Right: Actions --}}
        <div class="space-y-6">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Update Status</x-ui.card-title>
                </x-ui.card-header>
                <form action="{{ route('admin.contact-messages.update', $message) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <x-ui.card-content>
                        <x-ui.field>
                            <x-ui.field-label>Status</x-ui.field-label>
                            <x-ui.select name="status" value="{{ $message->status }}">
                                <x-ui.select-trigger class="w-full"><x-ui.select-value /></x-ui.select-trigger>
                                <x-ui.select-content>
                                    @foreach (['new', 'read', 'replied', 'closed', 'spam'] as $s)
                                        <x-ui.select-item value="{{ $s }}">{{ ucfirst($s) }}</x-ui.select-item>
                                    @endforeach
                                </x-ui.select-content>
                            </x-ui.select>
                        </x-ui.field>
                    </x-ui.card-content>
                    <x-ui.card-footer class="bg-muted/50 flex flex-col gap-2 border-t p-4">
                        <x-ui.button type="submit" class="w-full">Update Status</x-ui.button>
                        <x-ui.button variant="outline" href="mailto:{{ $message->email }}" class="w-full">
                            <x-slot:before>
                                <x-lucide-mail class="size-4" />
                            </x-slot:before>
                            Reply by Email
                        </x-ui.button>
                    </x-ui.card-footer>
                </form>
            </x-ui.card>

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Metadata</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Received</span>
                        <span>{{ $message->created_at->format('d M Y') }}</span>
                    </div>
                    @if ($message->ip_address ?? false)
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">IP Address</span>
                            <span>{{ $message->ip_address }}</span>
                        </div>
                    @endif
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
