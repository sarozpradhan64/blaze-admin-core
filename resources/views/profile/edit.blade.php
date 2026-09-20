<x-layouts.admin title="My Profile">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>My Profile</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl space-y-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">My Profile</h2>
            <p class="text-muted-foreground">Manage your account details and password.</p>
        </div>

        <x-ui.card>
            <x-ui.card-header><x-ui.card-title>Account Details</x-ui.card-title></x-ui.card-header>
            <x-ui.card-content>
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <x-ui.field>
                        <x-ui.field-label for="name">Name</x-ui.field-label>
                        <x-ui.input id="name" name="name" value="{{ old('name', $user->name) }}" required />
                        <x-ui.field-error name="name" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="username">Username</x-ui.field-label>
                        <x-ui.input id="username" name="username" value="{{ old('username', $user->username) }}"
                            autocomplete="username" required />
                        <x-ui.field-error name="username" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="email">Email</x-ui.field-label>
                        <x-ui.input id="email" name="email" type="email"
                            value="{{ old('email', $user->email) }}" autocomplete="email" required />
                        <x-ui.field-error name="email" />
                    </x-ui.field>
                    <div class="flex justify-end"><x-ui.button type="submit">Save Profile</x-ui.button></div>
                </form>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-header><x-ui.card-title>Change Password</x-ui.card-title></x-ui.card-header>
            <x-ui.card-content>
                <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <x-ui.field>
                        <x-ui.field-label for="current_password">Current Password</x-ui.field-label>
                        <x-ui.password-input id="current_password" name="current_password"
                            autocomplete="current-password" required />
                        <x-ui.field-error name="current_password" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="password">New Password</x-ui.field-label>
                        <x-ui.password-input id="password" name="password" autocomplete="new-password" required />
                        <x-ui.field-error name="password" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label for="password_confirmation">Confirm New Password</x-ui.field-label>
                        <x-ui.password-input id="password_confirmation" name="password_confirmation"
                            autocomplete="new-password" required />
                        <x-ui.field-error name="password_confirmation" />
                    </x-ui.field>
                    <div class="flex justify-end"><x-ui.button type="submit">Change Password</x-ui.button></div>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-layouts.admin>
