<x-layouts.admin title="{{ isset($feature) ? 'Edit Feature' : 'Add Feature' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.services.index') }}">Services</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.service-features.index') }}">Features</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($feature) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($feature) ? 'Edit Feature' : 'Add New Feature' }}</h2>
        </div>

        <form action="{{ isset($feature) ? route('admin.service-features.update', $feature) : route('admin.service-features.store') }}" method="POST">
            @csrf
            @if(isset($feature))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <x-ui.card>
                    <x-ui.card-content class="space-y-4 pt-6">
                        
                        <x-ui.field>
                            <x-ui.field-label for="service_id">Parent Service</x-ui.field-label>
                            <x-ui.select name="service_id">
                                <x-ui.select-trigger>
                                    <x-ui.select-value placeholder="Select a service" />
                                </x-ui.select-trigger>
                                <x-ui.select-content>
                                    @foreach($services as $srv)
                                        <x-ui.select-item value="{{ $srv->id }}" :selected="old('service_id', $feature->service_id ?? $selectedService) == $srv->id">
                                            {{ $srv->title }}
                                        </x-ui.select-item>
                                    @endforeach
                                </x-ui.select-content>
                            </x-ui.select>
                            <x-ui.field-error name="service_id" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label for="title">Feature Title</x-ui.field-label>
                            <x-ui.input id="title" name="title" value="{{ old('title', $feature->title ?? '') }}" />
                            <x-ui.field-error name="title" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.field-label for="description">Description (Optional)</x-ui.field-label>
                            <x-ui.textarea id="description" name="description" rows="3">{{ old('description', $feature->description ?? '') }}</x-ui.textarea>
                            <x-ui.field-error name="description" />
                        </x-ui.field>

                        @if(isset($extraFields) && !empty($extraFields))
                            @include('admin-core::services._extra_fields', ['fields' => $extraFields, 'model' => $feature ?? null])
                        @endif
                    </x-ui.card-content>
                    <x-ui.card-footer class="border-t bg-muted/50 flex justify-end gap-2 p-4">
                        <x-ui.button variant="outline" href="{{ route('admin.service-features.index') }}">Cancel</x-ui.button>
                        <x-ui.button type="submit">Save Feature</x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </div>
        </form>
    </div>
</x-layouts.admin>
