<x-layouts.admin title="Legal Documents">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>Legal Documents</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight">Legal Documents</h2>
        <x-ui.button href="{{ route('admin.legal-documents.create') }}">
            <x-slot:before>
                <x-lucide-plus class="size-4" />
            </x-slot:before>
            Add Document
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Title</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Last Updated</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($legalDocuments as $document)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $document->title }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $document->status ? 'default' : 'secondary' }}">
                                    {{ $document->status ? 'Active' : 'Draft' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $document->updated_at->format('M d, Y') }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        variant="ghost"
                                        size="icon"
                                        href="{{ route('admin.legal-documents.edit', $document) }}"
                                    >
                                        <x-lucide-edit class="size-4" />
                                    </x-ui.button>
                                    <form
                                        action="{{ route('admin.legal-documents.destroy', $document) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button
                                            variant="ghost"
                                            size="icon"
                                            class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                            type="submit"
                                        >
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="4" class="text-muted-foreground py-6 text-center">
                                No legal documents found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
        @if ($legalDocuments->hasPages())
            <x-ui.card-footer class="border-t p-4"> {{ $legalDocuments->links() }} </x-ui.card-footer>
        @endif
    </x-ui.card>
</x-layouts.admin>
