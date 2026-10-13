<x-layouts.admin title="Payment Methods">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>We Accept</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">We Accept (Payment Methods)</h2>
            <p class="text-muted-foreground mt-1 text-sm">Configure accepted payment methods (e.g. Dollar Card, MasterCard, Visa Card) and their payment details.</p>
        </div>
        <x-ui.button href="{{ route('admin.payment-methods.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Payment Method
        </x-ui.button>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-2 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-600 dark:text-green-400">
            <x-lucide-check-circle class="size-4 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    <x-ui.filter-bar action="{{ route('admin.payment-methods.index') }}">
        <div class="flex-1 min-w-[280px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search payment methods..." class="pl-9 h-10" />
            @if(request('search'))
                <a href="{{ route('admin.payment-methods.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
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
                    <x-ui.select-item value="active">Active (Visible)</x-ui.select-item>
                    <x-ui.select-item value="draft">Hidden</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $paymentMethods->total() }}</span> payment methods found
                <span class="text-xs text-muted-foreground/80 ml-2">(Drag rows to reorder)</span>
            </div>
            
            <form action="{{ route('admin.payment-methods.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('sort_by'))<input type="hidden" name="sort_by" value="{{ request('sort_by') }}">@endif
                @if(request('sort_dir'))<input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">@endif
                
                <span>Show</span>
                <x-ui.select name="per_page" value="{{ request('per_page', 20) }}">
                    <x-ui.select-trigger class="h-8 w-[70px]">
                        <x-ui.select-value placeholder="20" />
                    </x-ui.select-trigger>
                    <x-ui.select-content>
                        @foreach([10, 20, 50, 100] as $amount)
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
                        <x-ui.table-head class="w-10"></x-ui.table-head>
                        <x-ui.table-head class="w-28">Badge / Logo</x-ui.table-head>
                        <x-ui.sortable-th column="name" class="w-48">Name</x-ui.sortable-th>
                        <x-ui.table-head>Configured Details</x-ui.table-head>
                        <x-ui.sortable-th column="status" class="w-28 text-center">Status</x-ui.sortable-th>
                        <x-ui.table-head class="text-right w-28">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="payment-methods">
                    @forelse ($paymentMethods as $paymentMethod)
                        <x-ui.table-row data-id="{{ $paymentMethod->id }}">
                            <x-ui.table-cell class="text-muted-foreground cursor-grab" data-drag-handle>
                                <x-lucide-grip-vertical class="size-4" />
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="h-10 w-20 rounded-md border bg-muted/20 p-1 flex items-center justify-center overflow-hidden">
                                    @if($paymentMethod->logo)
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::url($paymentMethod->logo) }}"
                                            alt="{{ $paymentMethod->name }}"
                                            class="max-h-full max-w-full object-contain"
                                        />
                                    @else
                                        <x-lucide-credit-card class="size-5 text-muted-foreground/60" />
                                    @endif
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">
                                {{ $paymentMethod->name }}
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if($paymentMethod->details)
                                    <div class="line-clamp-2 text-xs text-muted-foreground prose dark:prose-invert max-w-md">
                                        {!! strip_tags($paymentMethod->details) !!}
                                    </div>
                                @else
                                    <span class="text-muted-foreground text-xs italic">No additional details configured</span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-center">
                                <form action="{{ route('admin.payment-methods.toggle', $paymentMethod) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        title="{{ $paymentMethod->status ? 'Click to hide' : 'Click to show' }}"
                                        class="inline-flex cursor-pointer items-center transition-transform hover:scale-105"
                                    >
                                        <x-ui.badge variant="{{ $paymentMethod->status ? 'default' : 'secondary' }}" class="text-xs">
                                            {{ $paymentMethod->status ? 'Active' : 'Hidden' }}
                                        </x-ui.badge>
                                    </button>
                                </form>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" title="Edit">
                                        <x-lucide-pencil class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" method="POST" onsubmit="return confirm('Delete this payment method?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="ghost" size="icon" type="submit" class="text-destructive hover:text-destructive" title="Delete">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-center py-12 text-muted-foreground">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <x-lucide-credit-card class="size-8 opacity-40" />
                                    <p class="text-sm font-medium">No payment methods found</p>
                                    <p class="text-xs text-muted-foreground">Add accepted cards and payment options like Dollar Card, MasterCard, and Visa Card.</p>
                                    <x-ui.button href="{{ route('admin.payment-methods.create') }}" size="sm" class="mt-2">
                                        <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
                                        Add Payment Method
                                    </x-ui.button>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        @if ($paymentMethods->hasPages())
            <x-ui.card-footer class="border-t p-4">
                {{ $paymentMethods->links() }}
            </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
