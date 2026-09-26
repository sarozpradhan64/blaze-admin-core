<x-layouts.admin title="Contact Messages">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Contact Messages</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Contact Messages</h2>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Subject</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Date</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($messages as $msg)
                        <x-ui.table-row class="{{ $msg->status === 'new' ? 'bg-primary/5 font-semibold' : '' }}">
                            <x-ui.table-cell>
                                <div>{{ $msg->name }}</div>
                                <div class="text-muted-foreground text-xs">{{ $msg->email }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ Str::limit($msg->subject ?? $msg->message, 50) }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ match($msg->status) { 'new' => 'default', 'spam' => 'destructive', default => 'secondary' } }}">
                                    {{ ucfirst($msg->status) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground text-sm">
                                {{ $msg->created_at->format('M d, Y') }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.contact-messages.show', $msg) }}"
                                    >
                                        <x-lucide-eye class="size-4"
                                    /></x-ui.button>
                                    <form
                                        action="{{ route('admin.contact-messages.destroy', $msg) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete message?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="ghost" size="icon" class="text-destructive">
                                            <x-lucide-trash-2 class="size-4"
                                        /></x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="5" class="py-6 text-center">
                                No messages found.</x-ui.table-cell
                            ></x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>
</x-layouts.admin>
