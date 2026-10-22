<x-ui.card x-data="menuBuilder()">
    <x-ui.card-header class="flex flex-row items-center justify-between">
        <x-ui.card-title>Menu Items</x-ui.card-title>
        <x-ui.button type="button" size="sm" @click="openAddModal()">
            <x-lucide-plus class="size-4 mr-2" /> Add Item
        </x-ui.button>
    </x-ui.card-header>
    <x-ui.card-content>
        @if ($menu->children->isEmpty())
            <div class="text-muted-foreground py-10 text-center">
                <p>No items added to this menu yet.</p>
            </div>
        @else
            <div class="border rounded-md p-4 bg-muted/20">
                {{-- To keep it simple and robust, we use a standard list with Edit/Delete. For reordering, we provide explicit sort_order inputs --}}
                <div class="space-y-2"
                     x-data="sortableList({
                         reorderUrl: '{{ route('admin.reorder', 'menus') }}',
                         csrfToken: '{{ csrf_token() }}'
                     })"
                >
                    @foreach ($menu->children as $item)
                        <div data-id="{{ $item->id }}" class="flex items-center gap-2">
                            <button type="button" data-drag-handle class="text-muted-foreground hover:text-foreground shrink-0 cursor-grab px-2">
                                <x-lucide-grip-vertical class="size-4" />
                            </button>
                            <div class="flex-grow">
                                @include('admin-core::menus.item', ['item' => $item, 'depth' => 0])
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </x-ui.card-content>

    {{-- Add/Edit Modal --}}
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-background rounded-lg shadow-lg w-full max-w-lg p-6" @click.outside="showModal = false">
            <h3 class="text-lg font-semibold mb-4" x-text="editMode ? 'Edit Menu Item' : 'Add Menu Item'"></h3>
            <form :action="formAction" method="POST">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- Hidden title input when type is service_category or service so title is submitted --}}
                <template x-if="formData.type === 'service_category' || formData.type === 'service'">
                    <input type="hidden" name="title" x-model="formData.title">
                </template>
                
                <div class="space-y-4">
                    <x-ui.field>
                        <x-ui.field-label for="type" required>Type</x-ui.field-label>
                        <select name="type" id="type" x-model="formData.type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="custom_url">Custom URL</option>
                            <option value="page">Page</option>
                            <option value="service_category">Service Category</option>
                            <option value="service">Service</option>
                        </select>
                    </x-ui.field>

                    {{-- Service Category Selection (Grouped & Nested per Parent-Child Combination with SlimSelect) --}}
                    <div x-show="formData.type === 'service_category'">
                        <x-ui.field>
                            <x-ui.field-label for="reference_id_category" required>Service Category</x-ui.field-label>
                            <div class="w-full">
                                <select 
                                    id="reference_id_category" 
                                    class="w-full"
                                    :name="formData.type === 'service_category' ? 'reference_id' : ''"
                                >
                                    <option data-placeholder="true" value=""></option>
                                    @php
                                        $usedCategoryIds = $menu->children()->where('type', 'service_category')->pluck('reference_id')->filter()->all();
                                        $parentCats = $serviceCategories->whereNull('parent_id')->sortBy('sort_order');
                                        $handledCategoryIds = collect();
                                    @endphp
                                    @foreach($parentCats as $parent)
                                        @php
                                            $handledCategoryIds->push($parent->id);
                                            $isParentUsed = in_array($parent->id, $usedCategoryIds);
                                            $children = $serviceCategories->where('parent_id', $parent->id)->sortBy('sort_order');
                                        @endphp
                                        <optgroup label="{{ $parent->name }}">
                                            <option 
                                                value="{{ $parent->id }}" 
                                                data-title="{{ $parent->name }}"
                                            >
                                                {{ $parent->name }} (Main Category){{ $isParentUsed ? ' — (Already added)' : '' }}
                                            </option>
                                            @foreach($children as $child)
                                                @php
                                                    $handledCategoryIds->push($child->id);
                                                    $isChildUsed = in_array($child->id, $usedCategoryIds);
                                                    $grandChildren = $serviceCategories->where('parent_id', $child->id)->sortBy('sort_order');
                                                @endphp
                                                <option 
                                                    value="{{ $child->id }}" 
                                                    data-title="{{ $child->name }}"
                                                >
                                                    {{ $child->name }}{{ $isChildUsed ? ' — (Already added)' : '' }}
                                                </option>
                                                @foreach($grandChildren as $gc)
                                                    @php
                                                        $handledCategoryIds->push($gc->id);
                                                        $isGcUsed = in_array($gc->id, $usedCategoryIds);
                                                    @endphp
                                                    <option 
                                                        value="{{ $gc->id }}" 
                                                        data-title="{{ $gc->name }}"
                                                    >
                                                        &nbsp;&nbsp;↳ {{ $gc->name }}{{ $isGcUsed ? ' — (Already added)' : '' }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </optgroup>
                                    @endforeach

                                    @php
                                        $otherCats = $serviceCategories->whereNotIn('id', $handledCategoryIds);
                                    @endphp
                                    @if($otherCats->isNotEmpty())
                                        <optgroup label="Other Categories">
                                            @foreach($otherCats as $other)
                                                @php
                                                    $isOtherUsed = in_array($other->id, $usedCategoryIds);
                                                @endphp
                                                <option 
                                                    value="{{ $other->id }}" 
                                                    data-title="{{ $other->name }}"
                                                >
                                                    {{ $other->name }}{{ $isOtherUsed ? ' — (Already added)' : '' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                        </x-ui.field>
                    </div>

                    {{-- Service Selection (Grouped & Nested per Parent-Child Combination with SlimSelect) --}}
                    <div x-show="formData.type === 'service'">
                        <x-ui.field>
                            <x-ui.field-label for="reference_id_service" required>Service</x-ui.field-label>
                            <div class="w-full">
                                <select 
                                    id="reference_id_service" 
                                    class="w-full"
                                    :name="formData.type === 'service' ? 'reference_id' : ''"
                                >
                                    <option data-placeholder="true" value=""></option>
                                    @php
                                        $usedServiceIds = $menu->children()->where('type', 'service')->pluck('reference_id')->filter()->all();
                                        $servicesGrouped = ($services ?? collect())->groupBy(function($srv) {
                                            $cat = $srv->category;
                                            if (! $cat) return 'Uncategorized';
                                            if ($cat->parent) {
                                                return $cat->parent->name . ' → ' . $cat->name;
                                            }
                                            return $cat->name;
                                        })->sortKeys();
                                    @endphp
                                    @foreach($servicesGrouped as $groupName => $srvList)
                                        <optgroup label="{{ $groupName }}">
                                            @foreach($srvList as $srv)
                                                @php
                                                    $isSrvUsed = in_array($srv->id, $usedServiceIds);
                                                @endphp
                                                <option 
                                                    value="{{ $srv->id }}" 
                                                    data-title="{{ $srv->title }}"
                                                >
                                                    {{ $srv->title }}{{ $isSrvUsed ? ' — (Already added)' : '' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </x-ui.field>
                    </div>

                    {{-- Fields for non-service-category and non-service types --}}
                    <div x-show="formData.type !== 'service_category' && formData.type !== 'service'" class="space-y-4">
                        <x-ui.field>
                            <x-ui.field-label for="title" required>Title</x-ui.field-label>
                            <x-ui.input id="title" name="title" x-model="formData.title" x-bind:required="formData.type !== 'service_category' && formData.type !== 'service'" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label for="subtitle">Subtitle</x-ui.field-label>
                            <x-ui.input id="subtitle" name="subtitle" x-model="formData.subtitle" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label for="icon">Icon</x-ui.field-label>
                            <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                <div 
                                    class="flex h-10 w-full cursor-pointer items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background"
                                    @click="open = !open; if(open) { $nextTick(() => $refs.searchInput.focus()); if(icons.length === 0) fetchIcons(); }"
                                >
                                    <div class="flex items-center gap-2">
                                        <template x-if="formData.icon">
                                            <img :src="'/admin/lucide-icon/' + formData.icon" class="size-4" alt="Icon" />
                                        </template>
                                        <span x-text="formData.icon || 'Select an icon...'" :class="!formData.icon && 'text-muted-foreground'"></span>
                                    </div>
                                    <x-lucide-chevron-down class="size-4 opacity-50" />
                                </div>
                                
                                <input type="hidden" name="icon" x-model="formData.icon">

                                <div x-show="open" style="display: none;" class="absolute z-50 mt-1 w-full rounded-md border bg-popover text-popover-foreground shadow-md outline-none">
                                    <div class="flex items-center border-b px-3">
                                        <x-lucide-search class="mr-2 size-4 shrink-0 opacity-50" />
                                        <input 
                                            x-ref="searchInput"
                                            type="text" 
                                            class="flex h-10 w-full rounded-md bg-transparent py-3 text-sm outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50" 
                                            placeholder="Search icons..." 
                                            x-model="search"
                                        >
                                    </div>
                                    <div class="max-h-[300px] overflow-y-auto p-1">
                                        <div x-show="isLoadingIcons" class="py-6 text-center text-sm text-muted-foreground">
                                            Loading icons...
                                        </div>
                                        <div class="grid grid-cols-4 gap-1 sm:grid-cols-6">
                                            <template x-for="icon in filteredIcons" :key="icon">
                                                <div 
                                                    class="flex cursor-pointer flex-col items-center justify-center rounded-sm p-2 text-sm outline-none hover:bg-accent hover:text-accent-foreground"
                                                    :class="formData.icon === icon ? 'bg-accent text-accent-foreground' : ''"
                                                    @click="formData.icon = icon; open = false;"
                                                >
                                                    <img :src="'/admin/lucide-icon/' + icon" class="mb-1 size-5" alt="icon" loading="lazy" />
                                                    <span class="text-[10px] w-full truncate text-center" x-text="icon"></span>
                                                </div>
                                            </template>
                                        </div>
                                        <div x-show="!isLoadingIcons && filteredIcons.length === 0" class="py-6 text-center text-sm text-muted-foreground">
                                            No icons found.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-ui.field>

                        <div x-show="formData.type === 'custom_url'">
                            <x-ui.field>
                                <x-ui.field-label for="url">URL</x-ui.field-label>
                                <x-ui.input id="url" name="url" x-model="formData.url" />
                            </x-ui.field>
                        </div>

                        <div x-show="formData.type === 'page'">
                            <x-ui.field>
                                <x-ui.field-label for="reference_id_page" required>Page</x-ui.field-label>
                                <select id="reference_id_page" x-model="formData.reference_id" :name="formData.type === 'page' ? 'reference_id' : ''" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">Select a page...</option>
                                    @foreach($pages as $page)
                                        <option value="{{ $page->id }}">{{ $page->title }}</option>
                                    @endforeach
                                </select>
                            </x-ui.field>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <x-ui.button type="button" variant="outline" @click="showModal = false">Cancel</x-ui.button>
                    <x-ui.button type="submit">Save</x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-ui.card>

<script>
    function menuBuilder() {
        return {
            showModal: false,
            editMode: false,
            formAction: '{{ route('admin.menus.items.store', $menu) }}',
            isSyncing: false,
            icons: [],
            isLoadingIcons: false,
            formData: {
                title: '',
                subtitle: '',
                icon: '',
                type: 'custom_url',
                url: '',
                reference_id: ''
            },
            get filteredIcons() {
                if (typeof this.$data.search === 'undefined' || this.$data.search === '') return this.icons;
                return this.icons.filter(i => i.includes(this.$data.search.toLowerCase()));
            },
            async fetchIcons() {
                if (this.icons.length > 0 || this.isLoadingIcons) return;
                this.isLoadingIcons = true;
                try {
                    const res = await fetch('{{ route('admin.lucide-icons') }}');
                    this.icons = await res.json();
                } catch (e) {
                    console.error('Failed to fetch icons', e);
                } finally {
                    this.isLoadingIcons = false;
                }
            },
            init() {
                this.$watch('formData.type', (newType) => {
                    this.$nextTick(() => {
                        this.initSlimSelects();
                        this.syncSlimSelects();
                    });
                });
            },
            initSlimSelects() {
                const catEl = document.getElementById('reference_id_category');
                if (catEl && !catEl._slimSelect && window.SlimSelect) {
                    catEl._slimSelect = new window.SlimSelect({
                        select: catEl,
                        settings: {
                            placeholderText: 'Select a service category...',
                            searchPlaceholder: 'Search categories...',
                            allowDeselect: true,
                            closeOnSelect: true,
                        },
                        events: {
                            afterChange: (newVal) => {
                                if (this.isSyncing) return;
                                const val = (newVal && newVal[0] && newVal[0].value) ? String(newVal[0].value) : '';
                                this.formData.reference_id = val;
                                if (val) {
                                    const opt = catEl.querySelector(`option[value="${val}"]`);
                                    if (opt && opt.dataset.title) {
                                        this.formData.title = opt.dataset.title;
                                    }
                                }
                            }
                        }
                    });
                }

                const srvEl = document.getElementById('reference_id_service');
                if (srvEl && !srvEl._slimSelect && window.SlimSelect) {
                    srvEl._slimSelect = new window.SlimSelect({
                        select: srvEl,
                        settings: {
                            placeholderText: 'Select a service / trek...',
                            searchPlaceholder: 'Search services...',
                            allowDeselect: true,
                            closeOnSelect: true,
                        },
                        events: {
                            afterChange: (newVal) => {
                                if (this.isSyncing) return;
                                const val = (newVal && newVal[0] && newVal[0].value) ? String(newVal[0].value) : '';
                                this.formData.reference_id = val;
                                if (val) {
                                    const opt = srvEl.querySelector(`option[value="${val}"]`);
                                    if (opt && opt.dataset.title) {
                                        this.formData.title = opt.dataset.title;
                                    }
                                }
                            }
                        }
                    });
                }
            },
            syncSlimSelects() {
                this.isSyncing = true;
                const catEl = document.getElementById('reference_id_category');
                if (catEl) {
                    const val = (this.formData.type === 'service_category' && this.formData.reference_id) ? String(this.formData.reference_id) : '';
                    catEl.value = val;
                    if (catEl._slimSelect) {
                        try {
                            if (val) {
                                catEl._slimSelect.setSelected(val);
                            } else {
                                catEl._slimSelect.setSelected('');
                            }
                        } catch (e) {
                            console.error('Error setting SlimSelect category:', e);
                        }
                    }
                }
                const srvEl = document.getElementById('reference_id_service');
                if (srvEl) {
                    const val = (this.formData.type === 'service' && this.formData.reference_id) ? String(this.formData.reference_id) : '';
                    srvEl.value = val;
                    if (srvEl._slimSelect) {
                        try {
                            if (val) {
                                srvEl._slimSelect.setSelected(val);
                            } else {
                                srvEl._slimSelect.setSelected('');
                            }
                        } catch (e) {
                            console.error('Error setting SlimSelect service:', e);
                        }
                    }
                }
                setTimeout(() => {
                    this.isSyncing = false;
                }, 150);
            },
            openAddModal(parentId = null) {
                this.editMode = false;
                this.formAction = parentId ? '/admin/menus/' + parentId + '/items' : '{{ route('admin.menus.items.store', $menu) }}';
                this.formData = { title: '', subtitle: '', icon: '', type: 'custom_url', url: '', reference_id: '' };
                this.showModal = true;
                this.$nextTick(() => {
                    this.initSlimSelects();
                    this.syncSlimSelects();
                });
            },
            openEditModal(item) {
                this.editMode = true;
                this.formAction = '/admin/items/' + item.id;
                this.formData = {
                    title: item.title || '',
                    subtitle: item.subtitle || '',
                    icon: item.icon || '',
                    type: item.type,
                    url: item.url || '',
                    reference_id: (item.reference_id !== null && item.reference_id !== undefined) ? String(item.reference_id) : ''
                };
                this.showModal = true;
                this.$nextTick(() => {
                    this.initSlimSelects();
                    this.syncSlimSelects();
                });
                // Additional sync frames to ensure custom select finishes rendering
                setTimeout(() => {
                    this.initSlimSelects();
                    this.syncSlimSelects();
                }, 100);
                setTimeout(() => {
                    this.syncSlimSelects();
                }, 250);
            }
        }
    }
</script>
