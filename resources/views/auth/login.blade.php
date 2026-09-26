<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin Login — {{ config('app.name', 'Blaze Admin') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-foreground flex min-h-screen items-center justify-center font-sans antialiased">
    <div class="w-full max-w-sm px-4">
        <div class="mb-8 flex flex-col items-center gap-2">
            <div class="bg-primary text-primary-foreground flex size-10 items-center justify-center rounded-lg">
                <x-lucide-hard-hat class="size-6" />
            </div>
            <h1 class="text-xl font-semibold tracking-tight">Blaze Admin</h1>
            <p class="text-muted-foreground text-sm">Sign in to the admin dashboard</p>
        </div>

        <x-ui.card>
            <x-ui.card-content class="pt-6">
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                    @csrf

                    <x-ui.field>
                        <x-ui.field-label for="username">Username</x-ui.field-label>
                        <x-ui.input
                            id="username"
                            name="username"
                            type="text"
                            :value="old('username')"
                            autofocus
                            autocomplete="username"
                            placeholder="admin"
                            :aria-invalid="$errors->has('username') ? 'true' : null"
                        />
                        @error('username')
                            <x-ui.field-error>{{ $message }}</x-ui.field-error>
                        @enderror
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label for="password">Password</x-ui.field-label>
                        <x-ui.password-input
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            :aria-invalid="$errors->has('password') ? 'true' : null"
                        />
                        @error('password')
                            <x-ui.field-error>{{ $message }}</x-ui.field-error>
                        @enderror
                    </x-ui.field>

                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="border-input accent-primary size-4 rounded"
                        />
                        <label for="remember" class="text-muted-foreground cursor-pointer text-sm">Remember me</label>
                    </div>

                    <x-ui.button type="submit" class="w-full">
                        Sign In
                        <x-slot:after>
                            <x-lucide-log-in class="size-4" />
                        </x-slot:after>
                    </x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</body>
</html>
