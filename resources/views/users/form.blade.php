<x-layouts.admin title="{{ isset($user) ? 'Edit User' : 'Add User' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-link
                        href="{{ route('admin.users.index') }}">Users</x-ui.breadcrumb-link></x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>{{ isset($user) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <h2 class="mb-6 text-2xl font-bold tracking-tight">{{ isset($user) ? 'Edit User' : 'Add New User' }}</h2>

        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST"
            class="space-y-6">
            @csrf
            @if (isset($user))
                @method('PUT')
            @endif

            <x-ui.card>
                <x-ui.card-header><x-ui.card-title>Account Details</x-ui.card-title></x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <x-ui.field>
                        <x-ui.field-label for="name">Name</x-ui.field-label>
                        <x-ui.input id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                            required />
                        <x-ui.field-error name="name" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="username">Username</x-ui.field-label>
                        <x-ui.input id="username" name="username" value="{{ old('username', $user->username ?? '') }}"
                            autocomplete="username" required />
                        <x-ui.field-error name="username" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="email">Email</x-ui.field-label>
                        <x-ui.input id="email" name="email" type="email"
                            value="{{ old('email', $user->email ?? '') }}" autocomplete="email" required />
                        <x-ui.field-error name="email" />
                    </x-ui.field>
                </x-ui.card-content>
            </x-ui.card>

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Password</x-ui.card-title>
                    @if (isset($user))
                        <x-ui.card-description>Leave blank to keep the current password.</x-ui.card-description>
                    @endif
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <x-ui.field>
                        <x-ui.field-label
                            for="password">{{ isset($user) ? 'New Password' : 'Password' }}</x-ui.field-label>
                        <x-ui.password-input id="password" name="password" autocomplete="new-password"
                            {{ isset($user) ? '' : 'required' }} />
                        <x-ui.field-error name="password" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="password_confirmation">Confirm Password</x-ui.field-label>
                        <x-ui.password-input id="password_confirmation" name="password_confirmation"
                            autocomplete="new-password" {{ isset($user) ? '' : 'required' }} />
                        <x-ui.field-error name="password_confirmation" />
                    </x-ui.field>
                </x-ui.card-content>
            </x-ui.card>

            <div class="flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.users.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save User</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
