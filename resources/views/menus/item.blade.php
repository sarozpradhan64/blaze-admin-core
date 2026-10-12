<div class="flex items-center justify-between p-2 bg-background border rounded-md" style="margin-left: {{ $depth * 20 }}px">
    <div class="flex items-center gap-3">
        @if($item->icon)
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-muted text-muted-foreground">
                @php
                    $svgPath = base_path('vendor/mallardduck/blade-lucide-icons/resources/svg/icons/'.$item->icon.'.svg');
                @endphp
                @if(file_exists($svgPath))
                    {!! file_get_contents($svgPath) !!}
                @else
                    <x-lucide-file class="size-4" />
                @endif
            </div>
        @endif
        <div class="flex flex-col">
            <div class="flex items-center gap-1.5">
                <span class="font-medium">{{ $item->title }}</span>
                @if($item->type === 'service_category')
                    <span class="inline-flex items-center rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-medium text-primary">Dynamic Category</span>
                @endif
            </div>
            <span class="text-xs text-muted-foreground">
                @if($item->subtitle) {{ $item->subtitle }} &bull; @endif
                {{ ucfirst(str_replace('_', ' ', $item->type)) }} {{ $item->reference_id ? '(#'.$item->reference_id.')' : '' }}
            </span>
        </div>
    </div>
    <div class="flex items-center gap-1">
        @if($item->type !== 'service_category')
        <x-ui.button variant="ghost" size="icon" type="button" @click="openAddModal({{ $item->id }})" title="Add sub-item">
            <x-lucide-plus class="size-4" />
        </x-ui.button>
        @endif
        @if($item->is_editable)
        <x-ui.button variant="ghost" size="icon" type="button" @click="openEditModal({{ json_encode($item) }})" title="Edit">
            <x-lucide-edit class="size-4" />
        </x-ui.button>
        @endif
        @if($item->is_deletable)
        <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this menu item?');">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="ghost" size="icon" class="text-destructive hover:text-destructive hover:bg-destructive/10">
                <x-lucide-trash-2 class="size-4" />
            </x-ui.button>
        </form>
        @endif
    </div>
</div>
@if($item->children->isNotEmpty())
    @foreach($item->children as $child)
        @include('admin-core::menus.item', ['item' => $child, 'depth' => $depth + 1])
    @endforeach
@endif
