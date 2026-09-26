<x-layouts.admin title="{{ isset($faq) ? 'Edit FAQ' : 'Add FAQ' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.faqs.index') }}">
                        FAQs</x-ui.breadcrumb-link
                    ></x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>
                        {{ isset($faq) ? 'Edit FAQ' : 'Add FAQ' }}</x-ui.breadcrumb-page
                    ></x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">{{ isset($faq) ? 'Edit FAQ' : 'Add New FAQ' }}</h2>
        </div>

        <form action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="POST">
            @csrf
            @if (isset($faq))
                @method('PUT')
            @endif

            <x-ui.card>
                <x-ui.card-content class="space-y-6 pt-6">
                    <x-ui.field>
                        <x-ui.field-label for="question">Question</x-ui.field-label>
                        <x-ui.input
                            id="question"
                            name="question"
                            value="{{ old('question', $faq->question ?? '') }}"
                            autofocus
                        />
                        <x-ui.field-error name="question" />
                    </x-ui.field>

                    <x-ui.field>
                        <x-ui.field-label for="answer">Answer</x-ui.field-label>
                        <x-ui.rich-text-editor name="answer" :value="old('answer', $faq->answer ?? '')" />
                        <x-ui.field-error name="answer" />
                    </x-ui.field>

                    <div class="flex items-center justify-between">
                        <x-ui.label for="status" class="flex flex-col space-y-1">
                            <span>Active Status</span>
                            <span class="text-muted-foreground text-xs font-normal">Make this FAQ available</span>
                        </x-ui.label>
                        <x-ui.switch
                            id="status"
                            name="status"
                            value="1"
                            :checked="old('status', $faq->status ?? true)"
                        />
                    </div>
                </x-ui.card-content>
                <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                    <x-ui.button variant="outline" href="{{ route('admin.faqs.index') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">{{ isset($faq) ? 'Update FAQ' : 'Save FAQ' }}</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-layouts.admin>
