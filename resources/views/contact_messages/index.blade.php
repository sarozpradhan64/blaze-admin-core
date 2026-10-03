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

    <x-ui.filter-bar action="{{ route('admin.contact-messages.index') }}">
        <div class="flex-1 min-w-[300px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search messages..." class="pl-9 h-10" />
            @if(request('search'))
                <a href="{{ route('admin.contact-messages.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <x-lucide-x class="size-4 text-muted-foreground hover:text-foreground" />
                </a>
            @endif
        </div>

        <div class="w-[200px]">
            <x-ui.select name="status" value="{{ request('status') }}">
                <x-ui.select-trigger class="h-10">
                    <x-slot:icon><x-lucide-eye class="size-4" /></x-slot:icon>
                    <div class="flex flex-col items-start text-xs">
                        <span class="font-medium text-foreground">Status</span>
                        <x-ui.select-value placeholder="All Statuses" />
                    </div>
                </x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="all">All Statuses</x-ui.select-item>
                    <x-ui.select-item value="new">New</x-ui.select-item>
                    <x-ui.select-item value="read">Read</x-ui.select-item>
                    <x-ui.select-item value="spam">Spam</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $messages->total() }}</span> messages found
            </div>
            
            <form action="{{ route('admin.contact-messages.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('sort_by'))<input type="hidden" name="sort_by" value="{{ request('sort_by') }}">@endif
                @if(request('sort_dir'))<input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">@endif
                
                <span>Show</span>
                <x-ui.select name="per_page" value="{{ request('per_page', 15) }}">
                    <x-ui.select-trigger class="h-8 w-[70px]">
                        <x-ui.select-value placeholder="15" />
                    </x-ui.select-trigger>
                    <x-ui.select-content>
                        @foreach([15, 25, 50, 100] as $amount)
                            <x-ui.select-item value="{{ $amount }}">{{ $amount }}</x-ui.select-item>
                        @endforeach
                    </x-ui.select-content>
                </x-ui.select>
                <span>entries</span>
                <x-ui.button type="submit" variant="outline" size="sm" class="h-8">Apply</x-ui.button>
            </form>
        </div>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.sortable-th column="name">Name</x-ui.sortable-th>
                        <x-ui.sortable-th column="subject">Subject</x-ui.sortable-th>
                        <x-ui.sortable-th column="status">Status</x-ui.sortable-th>
                        <x-ui.sortable-th column="created_at">Date</x-ui.sortable-th>
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
        <x-ui.card-footer class="border-t p-4">{{ $messages->links() }}</x-ui.card-footer>
    </x-ui.card>
</x-layouts.admin>
