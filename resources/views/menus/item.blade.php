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
            <span class="font-medium">{{ $item->title }}</span>
            <span class="text-xs text-muted-foreground">
                @if($item->subtitle) {{ $item->subtitle }} &bull; @endif
                {{ ucfirst(str_replace('_', ' ', $item->type)) }} {{ $item->reference_id ? '(#'.$item->reference_id.')' : '' }}
            </span>
        </div>
    </div>
    <div class="flex items-center gap-2">
        @if($item->is_editable)
        <x-ui.button variant="ghost" size="icon" type="button" @click="openEditModal({{ json_encode($item) }})">
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
