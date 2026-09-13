<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — {{ config('app.name', 'Atlas Finish Group') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-sm px-4">
        <div class="flex flex-col items-center gap-2 mb-8">
            <div class="size-10 rounded-lg bg-primary flex items-center justify-center text-primary-foreground">
                <x-lucide-hard-hat class="size-6" />
            </div>
            <h1 class="text-xl font-semibold tracking-tight">Atlas Finish Group</h1>
            <p class="text-sm text-muted-foreground">Sign in to the admin dashboard</p>
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
                        <x-ui.input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            :aria-invalid="$errors->has('password') ? 'true' : null"
                        />
                        @error('password')
                            <x-ui.field-error>{{ $message }}</x-ui.field-error>
                        @enderror
                    </x-ui.field>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember" class="rounded border-input size-4 accent-primary">
                        <label for="remember" class="text-sm text-muted-foreground cursor-pointer">Remember me</label>
                    </div>

                    <x-ui.button type="submit" class="w-full">
                        Sign In
                        <x-slot:after><x-lucide-log-in class="size-4" /></x-slot:after>
                    </x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>

</body>
</html>
