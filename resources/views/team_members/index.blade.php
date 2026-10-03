<x-layouts.admin title="Team Members">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Team Members</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Team Members</h2>
        <x-ui.button href="{{ route('admin.team-members.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Member
        </x-ui.button>
    </div>

    <x-ui.filter-bar action="{{ route('admin.team-members.index') }}">
        <div class="flex-1 min-w-[300px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search team members..." class="pl-9 h-10" />
            @if(request('search'))
                <a href="{{ route('admin.team-members.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
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
                    <x-ui.select-item value="active">Active</x-ui.select-item>
                    <x-ui.select-item value="draft">Inactive</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $members->total() }}</span> team members found
            </div>
            
            <form action="{{ route('admin.team-members.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                @if(request('sort_by'))<input type="hidden" name="sort_by" value="{{ request('sort_by') }}">@endif
                @if(request('sort_dir'))<input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">@endif
                
                <span>Show</span>
                <x-ui.select name="per_page" value="{{ request('per_page', 10) }}">
                    <x-ui.select-trigger class="h-8 w-[70px]">
                        <x-ui.select-value placeholder="10" />
                    </x-ui.select-trigger>
                    <x-ui.select-content>
                        @foreach([10, 25, 50, 100] as $amount)
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
                        <x-ui.table-head class="w-8"></x-ui.table-head>
                        <x-ui.table-head class="w-14">Photo</x-ui.table-head>
                        <x-ui.sortable-th column="name">Name</x-ui.sortable-th>
                        <x-ui.sortable-th column="role">Role</x-ui.sortable-th>
                        <x-ui.sortable-th column="is_active">Status</x-ui.sortable-th>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-admin::sortable-tbody resource="team-members">
                    @forelse ($members as $member)
                        <x-ui.table-row data-id="{{ $member->id }}">
                            <x-ui.table-cell class="w-8">
                                <button
                                    type="button"
                                    data-drag-handle
                                    class="text-muted-foreground hover:text-foreground cursor-grab"
                                >
                                    <x-lucide-grip-vertical class="size-4" />
                                </button>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if ($member->image)
                                    <img
                                        src="{{ asset('storage/' . $member->image) }}"
                                        alt="{{ $member->name }}"
                                        class="border-border size-10 rounded-full border object-cover"
                                    />
                                @else
                                    <div class="bg-muted flex size-10 items-center justify-center rounded-full">
                                        <x-lucide-user class="text-muted-foreground size-5" />
                                    </div>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="font-medium">{{ $member->name }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground">{{ $member->role }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $member->is_active ? 'default' : 'secondary' }}">
                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.team-members.edit', $member) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.team-members.destroy', $member) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this member?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            type="submit"
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                        >
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="5" class="text-muted-foreground py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <x-lucide-users class="text-muted-foreground/50 size-8" />
                                    <span>No team members found.</span>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-admin::sortable-tbody>
            </x-ui.table>
        </x-ui.card-content>
        @if ($members->hasPages())
            <x-ui.card-footer class="border-t p-4"> {{ $members->links() }} </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
