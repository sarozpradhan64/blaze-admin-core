<x-layouts.admin title="Testimonials">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Testimonials</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Testimonials</h2>
        <x-ui.button href="{{ route('admin.testimonials.create') }}">
            <x-slot:before><x-lucide-plus class="size-4" /></x-slot:before>
            Add Testimonial
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Role / Company</x-ui.table-head>
                        <x-ui.table-head>Rating</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($testimonials as $testimonial)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $testimonial->name }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $testimonial->role }}{{ $testimonial->company ? ' @ '.$testimonial->company : '' }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $testimonial->rating }}/5</x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex items-center gap-2">
                                    <x-ui.badge variant="{{ $testimonial->status ? 'default' : 'secondary' }}">{{ $testimonial->status ? 'Active' : 'Hidden' }}</x-ui.badge>
                                    @if($testimonial->is_featured)<x-lucide-star class="size-4 text-primary" />@endif
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="icon" href="{{ route('admin.testimonials.edit', $testimonial) }}">
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <x-ui.button type="submit" variant="ghost" size="icon" class="text-destructive hover:text-destructive hover:bg-destructive/10">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="5" class="text-center py-6 text-muted-foreground">No testimonials found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>
</x-layouts.admin>
