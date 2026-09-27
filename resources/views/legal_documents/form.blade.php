<x-layouts.admin title="{{ isset($legalDocument) ? 'Edit Legal Document' : 'Add Legal Document' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.legal-documents.index') }}">Legal Documents</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($legalDocument) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($legalDocument) ? 'Edit Legal Document' : 'Add New Legal Document' }}</h2>
        </div>

        <form action="{{ isset($legalDocument) ? route('admin.legal-documents.update', $legalDocument) : route('admin.legal-documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($legalDocument))
                @method('PUT')
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="md:col-span-2 space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Document Information</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label for="title">Title</x-ui.field-label>
                                <x-ui.input id="title" name="title"
                                    value="{{ old('title', $legalDocument->title ?? '') }}" required />
                                <x-ui.field-error name="title" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="file">Document File</x-ui.field-label>
                                <x-ui.file-upload name="file" accept=".pdf,.doc,.docx,.txt,.png,.jpg,.jpeg,.webp" :current="old('file', $legalDocument->file_path ?? null)" />
                                <x-ui.field-error name="file" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Visibility</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-ui.label for="status" class="flex flex-col space-y-1">
                                    <span>Active</span>
                                    <span class="font-normal text-xs text-muted-foreground">Publish to website</span>
                                </x-ui.label>
                                <x-ui.switch id="status" name="status" value="1" :checked="old('status', $legalDocument->status ?? true)" />
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.legal-documents.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Document</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
