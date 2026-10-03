<x-layouts.admin title="Users">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>Users</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Users</h2>
        <x-ui.button href="{{ route('admin.users.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add User
        </x-ui.button>
    </div>

    <x-ui.filter-bar action="{{ route('admin.users.index') }}">
        <div class="flex-1 min-w-[300px] relative">
            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <x-ui.input name="search" value="{{ request('search') }}" placeholder="Search users..." class="pl-9 h-10" />
            @if(request('search'))
                <a href="{{ route('admin.users.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <x-lucide-x class="size-4 text-muted-foreground hover:text-foreground" />
                </a>
            @endif
        </div>
    </x-ui.filter-bar>

    <x-ui.card>
        <div class="flex items-center justify-between p-4 pb-0 border-b-0">
            <div class="text-sm text-muted-foreground">
                <span class="font-medium text-foreground">{{ $users->total() }}</span> users found
            </div>
            
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2 text-sm text-muted-foreground">
                @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
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
                        <x-ui.sortable-th column="name">Name</x-ui.sortable-th>
                        <x-ui.sortable-th column="username">Username</x-ui.sortable-th>
                        <x-ui.sortable-th column="email">Email</x-ui.sortable-th>
                        <x-ui.sortable-th column="created_at">Joined</x-ui.sortable-th>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($users as $user)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $user->name }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $user->username }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $user->email }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-muted-foreground">
                                {{ $user->created_at->format('M j, Y') }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.users.edit', $user) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            type="submit"
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:bg-destructive/10 hover:text-destructive"
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
                                No users found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if ($users->hasPages())
            <x-ui.card-footer class="border-t p-4">{{ $users->links() }}</x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
