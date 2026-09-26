@props([
    'name' => 'faqs',
    'value' => [],
    'placeholder' => 'Search and select FAQs',
])

@php
    $allFaqs = \Blaze\AdminCore\Models\Faq::where('status', true)->orderBy('sort_order')->pluck('question', 'id')->map(function($q, $id) {
        return ['id' => $id, 'question' => $q];
    })->values()->toArray();
@endphp

<div x-data="{
        open: false,
        query: '',
        selectedIds: @js(is_array($value) ? array_map('strval', $value) : []),
        options: @js($allFaqs),
        
        get filteredOptions() {
            if (this.query === '') return this.options;
            const q = this.query.toLowerCase();
            return this.options.filter(opt => opt.question.toLowerCase().includes(q));
        },
        
        get selectedOptions() {
            return this.options.filter(opt => this.selectedIds.includes(String(opt.id)));
        },
        
        toggle(id) {
            const strId = String(id);
            const index = this.selectedIds.indexOf(strId);
            if (index > -1) {
                this.selectedIds.splice(index, 1);
            } else {
                this.selectedIds.push(strId);
            }
        },
        
        remove(id) {
            const strId = String(id);
            this.selectedIds = this.selectedIds.filter(v => v !== strId);
        }
    }" 
    class="relative"
    @click.outside="open = false">
    
    <template x-for="id in selectedIds" :key="id">
        <input type="hidden" name="{{ $name }}[]" :value="id">
    </template>

    <div
        class="flex min-h-10 w-full flex-wrap items-center gap-2 rounded-md border border-input bg-background px-2 py-1.5 text-sm focus-within:ring-2 focus-within:ring-ring focus-within:ring-offset-2"
        @click="$refs.input.focus(); open = true"
    >
        <template x-for="opt in selectedOptions" :key="opt.id">
            <span class="inline-flex items-center gap-1 rounded-md border bg-muted px-2 py-1 text-xs font-medium">
                <span x-text="opt.question"></span>
                <button type="button" class="text-muted-foreground hover:text-foreground" @click.stop="remove(opt.id)">
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
            @focus="open = true"
            @keydown.escape.prevent="open = false"
            @keydown.backspace="if (query === '' && selectedIds.length > 0) { selectedIds.pop(); }"
        >
    </div>

    <div
        x-show="open"
        x-transition.origin.top
        class="absolute z-50 mt-1 max-h-64 w-full overflow-auto rounded-md border bg-popover p-1 text-popover-foreground shadow-md"
        style="display: none;"
    >
        <div x-show="filteredOptions.length === 0" class="px-2 py-2 text-sm text-muted-foreground text-center">
            No FAQs found.
        </div>
        
        <template x-for="opt in filteredOptions" :key="opt.id">
            <label class="flex w-full items-center gap-2 rounded-sm px-2 py-1.5 text-left text-sm hover:bg-accent hover:text-accent-foreground cursor-pointer">
                <input type="checkbox" 
                    :checked="selectedIds.includes(String(opt.id))" 
                    @change="toggle(opt.id)"
                    class="rounded border-input text-primary focus:ring-primary size-4">
                <span x-text="opt.question" class="flex-1"></span>
            </label>
        </template>
    </div>
</div>
