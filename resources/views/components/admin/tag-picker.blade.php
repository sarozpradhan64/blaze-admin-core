@props([
    'name' => 'tags',
    'value' => '',
    'placeholder' => 'Search or create tags',
])

<div
    x-data="tagPicker({
        initial: @js($value),
        searchUrl: @js(route('admin.tags.index')),
    })"
    class="relative"
    @click.outside="open = false"
>
    <input type="hidden" name="{{ $name }}" :value="serializedTags()">

    <div
        class="flex min-h-10 w-full flex-wrap items-center gap-2 rounded-md border border-input bg-background px-2 py-1.5 text-sm focus-within:ring-2 focus-within:ring-ring focus-within:ring-offset-2"
        @click="$refs.input.focus()"
    >
        <template x-for="tag in selected" :key="tag">
            <span class="inline-flex items-center gap-1 rounded-md border bg-muted px-2 py-1 text-xs font-medium">
                <span x-text="tag"></span>
                <button type="button" class="text-muted-foreground hover:text-foreground" @click.stop="remove(tag)">
                    <x-lucide-x class="size-3" />
                </button>
            </span>
        </template>

        <input
            x-ref="input"
            x-model="query"
            type="text"
            class="min-w-32 flex-1 border-0 bg-transparent px-1 py-1 outline-none placeholder:text-muted-foreground"
            placeholder="{{ $placeholder }}"
            @focus="open = true; search()"
            @input.debounce.150ms="open = true; search()"
            @keydown.enter.prevent="chooseHighlightedOrCreate()"
            @keydown.arrow-down.prevent="move(1)"
            @keydown.arrow-up.prevent="move(-1)"
            @keydown.backspace="removeLastWhenEmpty()"
            @keydown.escape.prevent="open = false"
        >
    </div>

    <div
        x-show="open && (suggestions.length || normalizedQuery())"
        x-transition.origin.top
        class="absolute z-50 mt-1 max-h-64 w-full overflow-auto rounded-md border bg-popover p-1 text-popover-foreground shadow-md"
        style="display: none;"
    >
        <template x-for="(tag, index) in suggestions" :key="tag.slug">
            <button
                type="button"
                class="flex w-full items-center justify-between rounded-sm px-2 py-1.5 text-left text-sm hover:bg-accent hover:text-accent-foreground"
                :class="{ 'bg-accent text-accent-foreground': highlighted === index }"
                @mouseenter="highlighted = index"
                @click="select(tag.name)"
            >
                <span x-text="tag.name"></span>
                <span class="text-xs text-muted-foreground" x-show="tag.usage_count" x-text="tag.usage_count"></span>
            </button>
        </template>

        <button
            type="button"
            x-show="canCreateCurrent()"
            class="flex w-full items-center gap-2 rounded-sm px-2 py-1.5 text-left text-sm hover:bg-accent hover:text-accent-foreground"
            :class="{ 'bg-accent text-accent-foreground': highlighted === suggestions.length }"
            @mouseenter="highlighted = suggestions.length"
            @click="select(query)"
        >
            <x-lucide-plus class="size-4" />
            <span>Create "<span x-text="normalizedQuery()"></span>"</span>
        </button>
    </div>
</div>
