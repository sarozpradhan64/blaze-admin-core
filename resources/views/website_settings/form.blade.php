<x-layouts.admin title="{{ isset($setting) ? 'Edit' : 'Add' }} Setting">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item><x-ui.breadcrumb-link href="{{ route('admin.settings.index') }}">Settings</x-ui.breadcrumb-link></x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item><x-ui.breadcrumb-page>{{ isset($setting) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>
    <div class="max-w-2xl">
        <h2 class="text-2xl font-bold tracking-tight mb-6">{{ isset($setting) ? 'Edit' : 'Add' }} Setting</h2>
        <form action="{{ isset($setting) ? route('admin.settings.update', $setting) : route('admin.settings.store') }}" method="POST">
            @csrf @if(isset($setting)) @method('PUT') @endif
            <x-ui.card>
                <x-ui.card-content class="space-y-4 pt-6">
                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.field>
                            <x-ui.field-label>Group</x-ui.field-label>
                            <x-ui.input name="group" value="{{ old('group', $setting->group ?? 'general') }}" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.field-label>Key (Unique)</x-ui.field-label>
                            <x-ui.input name="key" value="{{ old('key', $setting->key ?? '') }}" />
                        </x-ui.field>
                    </div>
                    <x-ui.field>
                        <x-ui.field-label>Value</x-ui.field-label>
                        <x-ui.textarea name="value" rows="4">{{ old('value', $setting->value ?? '') }}</x-ui.textarea>
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.field-label>Type</x-ui.field-label>
                        <x-ui.select name="type">
                            <x-ui.select-trigger><x-ui.select-value/></x-ui.select-trigger>
                            <x-ui.select-content>
                                @foreach(['string','text','boolean','integer','json'] as $t)
                                    <x-ui.select-item value="{{ $t }}" :selected="old('type', $setting->type ?? '') == $t">{{ ucfirst($t) }}</x-ui.select-item>
                                @endforeach
                            </x-ui.select-content>
                        </x-ui.select>
                    </x-ui.field>
                    <label class="flex items-center gap-2 text-sm pt-2">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', $setting->is_public ?? false) ? 'checked' : '' }}> Publicly exposed via API/Frontend
                    </label>
                </x-ui.card-content>
                <x-ui.card-footer class="border-t p-4 flex justify-end gap-2">
                    <x-ui.button variant="outline" href="{{ route('admin.settings.index') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">Save</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-layouts.admin>
