<x-layouts.admin title="{{ isset($download) ? 'Edit' : 'Add' }} Download">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.downloads.index') }}">
                        Downloads</x-ui.breadcrumb-link
                    ></x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>
                        {{ isset($download) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page
                    ></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($download) ? 'Edit' : 'Add' }} Download</h2>
        </div>

        <form
            action="{{ isset($download) ? route('admin.downloads.update', $download) : route('admin.downloads.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($download)) @method('PUT') @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Details</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label>Title</x-ui.field-label>
                                <x-ui.input name="title" value="{{ old('title', $download->title ?? '') }}" />
                                <x-ui.field-error name="title" />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.field-label>Description</x-ui.field-label>
                                <x-ui.rich-text-editor name="description">
                                    {!! old('description', $download->description ?? '') !!}</x-ui.rich-text-editor>
                                <x-ui.field-error name="description" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>File</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content>
                            <x-ui.file-upload
                                name="file_path"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt"
                                :current="old('file_path', $download->file_path ?? null)"
                                preview-type="file"
                            />
                            <x-ui.field-description class="mt-2">
                                PDF, Word, Excel, PowerPoint, ZIP, TXT (max 50 MB)</x-ui.field-description>
                            <x-ui.field-error name="file_path" />
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header><x-ui.card-title>Settings</x-ui.card-title></x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-ui.label for="is_public" class="flex flex-col space-y-1">
                                    <span>Public</span>
                                    <span class="text-muted-foreground text-xs font-normal">Visible to all visitors</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="is_public"
                                    name="is_public"
                                    value="1"
                                    :checked="old('is_public', $download->is_public ?? true)"
                                />
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.downloads.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Download</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
