<x-layouts.admin title="Enquiries">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Enquiries</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
        {{-- Total --}}
        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-muted text-muted-foreground flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-inbox class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">Total</p>
                <p class="text-foreground text-2xl leading-tight font-bold">{{ $stats['total'] }}</p>
            </div>
        </div>

        {{-- New --}}
        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-primary/10 text-primary flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-sparkles class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">New</p>
                <p class="text-primary text-2xl leading-tight font-bold">{{ $stats['new'] }}</p>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-muted text-muted-foreground flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-loader class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">In Progress</p>
                <p class="text-foreground text-2xl leading-tight font-bold">{{ $stats['in_progress'] }}</p>
            </div>
        </div>

        {{-- Urgent --}}
        <div class="bg-card border-border flex items-center gap-4 rounded-xl border px-5 py-4">
            <div class="bg-destructive/10 text-destructive flex size-10 shrink-0 items-center justify-center rounded-lg">
                <x-lucide-alert-triangle class="size-5" />
            </div>
            <div>
                <p class="text-muted-foreground text-xs font-medium tracking-wide uppercase">Urgent</p>
                <p class="text-destructive text-2xl leading-tight font-bold">{{ $stats['urgent'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form
        method="GET"
        action="{{ route('admin.enquiries.index') }}"
        class="bg-card border-border flex flex-wrap items-center gap-2 rounded-xl border px-4 py-3"
    >
        {{-- Search --}}
        <div class="min-w-52 flex-1">
            <x-ui.input
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, email or company…"
                class="h-9"
            >
                <x-slot:leading>
                    <x-lucide-search class="size-4" />
                </x-slot:leading>
            </x-ui.input>
        </div>

        <div class="bg-border hidden h-5 w-px sm:block"></div>

        {{-- Status --}}
        <div class="w-40">
            <x-ui.select name="status">
                <x-ui.select-trigger class="h-9"><x-ui.select-value placeholder="All statuses" /></x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="">All statuses</x-ui.select-item>
                    @foreach (['new', 'in_progress', 'contacted', 'converted', 'closed', 'spam'] as $s)
                        <x-ui.select-item
                            value="{{ $s }}"
                            :selected="request('status') === $s"
                        >
                            {{ ucfirst(str_replace('_',' ',$s)) }}</x-ui.select-item>
                    @endforeach
                </x-ui.select-content>
            </x-ui.select>
        </div>

        {{-- Priority --}}
        <div class="w-36">
            <x-ui.select name="priority">
                <x-ui.select-trigger class="h-9">
                    <x-ui.select-value placeholder="All priorities"
                /></x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="">All priorities</x-ui.select-item>
                    @foreach (['low', 'normal', 'high', 'urgent'] as $p)
                        <x-ui.select-item
                            value="{{ $p }}"
                            :selected="request('priority') === $p"
                        >
                            {{ ucfirst($p) }}</x-ui.select-item>
                    @endforeach
                </x-ui.select-content>
            </x-ui.select>
        </div>

        <div class="ml-auto flex items-center gap-1.5">
            <x-ui.button type="submit" size="sm">
                <x-slot:before>
                    <x-lucide-filter class="size-3.5" />
                </x-slot:before>
                Filter
            </x-ui.button>
            @if (request()->hasAny(['search', 'status', 'priority']))
                <x-ui.button variant="ghost" size="sm" href="{{ route('admin.enquiries.index') }}">
                    <x-slot:before>
                        <x-lucide-x class="size-3.5" />
                    </x-slot:before>
                    Clear
                </x-ui.button>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Subject</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Priority</x-ui.table-head>
                        <x-ui.table-head>Received</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($enquiries as $enquiry)
                        <x-ui.table-row class="{{ $enquiry->status === 'new' ? 'bg-primary/5' : '' }}">
                            <x-ui.table-cell>
                                <div class="font-medium">{{ $enquiry->name }}</div>
                                <div class="text-muted-foreground text-xs">{{ $enquiry->email }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="max-w-xs truncate">{{ $enquiry->subject ?? '—' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $statusVariant = match ($enquiry->status) {
                                        'new' => 'default',
                                        'in_progress' => 'secondary',
                                        'contacted' => 'outline',
                                        'converted' => 'default',
                                        'closed' => 'secondary',
                                        'spam' => 'destructive',
                                        default => 'outline',
                                    };
                                @endphp
                                <x-ui.badge variant="{{ $statusVariant }}">
                                    {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $priorityVariant = match ($enquiry->priority) {
                                        'urgent' => 'destructive',
                                        'high' => 'default',
                                        default => 'outline',
                                    };
                                @endphp
                                <x-ui.badge variant="{{ $priorityVariant }}">
                                    {{ ucfirst($enquiry->priority) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground text-sm">
                                {{ $enquiry->created_at->diffForHumans() }}
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.enquiries.show', $enquiry) }}"
                                    >
                                        <x-lucide-eye class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this enquiry?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                            type="submit"
                                        >
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-muted-foreground py-10 text-center">
                                No enquiries found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if ($enquiries->hasPages())
            <x-ui.card-footer class="border-t p-4">{{ $enquiries->links() }}</x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
