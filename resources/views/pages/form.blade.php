<x-layouts.admin title="{{ isset($page) ? 'Edit Page' : 'Add Page' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.pages.index') }}">Pages</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($page) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-5xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">
                {{ isset($page) ? 'Edit Page' : 'Add New Page' }}
            </h2>
        </div>

        <x-ui.tabs value="general">
            <x-ui.tabs-list class="mb-6">
                <x-ui.tabs-trigger value="general">
                    <x-lucide-layout-dashboard class="size-4 mr-2" />
                    General
                </x-ui.tabs-trigger>
                @if(isset($page))
                <x-ui.tabs-trigger value="seo">
                    <x-lucide-search class="size-4 mr-2" />
                    SEO Metadata
                </x-ui.tabs-trigger>
                @endif
            </x-ui.tabs-list>

            <x-ui.tabs-content value="general">
                <form
                    action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @if (isset($page))
                        @method('PUT')
                    @endif

                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="space-y-6 md:col-span-2">
                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Content</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <x-ui.field>
                                        <x-ui.field-label for="title" required>Title</x-ui.field-label>
                                        <x-ui.input id="title" name="title" value="{{ old('title', $page->title ?? '') }}" />
                                        <x-ui.field-error name="title" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="sub_title">Sub Page Title</x-ui.field-label>
                                        <x-ui.input id="sub_title" name="sub_title" value="{{ old('sub_title', $page->sub_title ?? '') }}" />
                                        <x-ui.field-error name="sub_title" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="sub_text">Sub Page Text</x-ui.field-label>
                                        <x-ui.textarea id="sub_text" name="sub_text" rows="3">{{ old('sub_text', $page->sub_text ?? '') }}</x-ui.textarea>
                                        <x-ui.field-error name="sub_text" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="excerpt">Excerpt</x-ui.field-label>
                                        <x-ui.textarea
                                            id="excerpt"
                                            name="excerpt"
                                            rows="3"
                                        >
                                            {{ old('excerpt', $page->excerpt ?? '') }}</x-ui.textarea>
                                        <x-ui.field-error name="excerpt" />
                                    </x-ui.field>

                                    <x-ui.field>
                                        <x-ui.field-label for="content" required>Content</x-ui.field-label>
                                        <x-ui.rich-text-editor name="content" :value="old('content', $page->content ?? '')" />
                                        <x-ui.field-error name="content" />
                                    </x-ui.field>
                                </x-ui.card-content>
                            </x-ui.card>
                        </div>

                        <div class="space-y-6">
                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Featured Image</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content>
                                    <x-ui.file-upload
                                        name="featured_image"
                                        accept="image/jpeg,image/png,image/gif,image/webp"
                                        :current="old('featured_image', $page->featured_image ?? null)"
                                    />
                                    <x-ui.field-error name="featured_image" />
                                </x-ui.card-content>
                            </x-ui.card>

                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>Publication</x-ui.card-title>
                                </x-ui.card-header>
                                <x-ui.card-content class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <x-ui.label for="status" class="flex flex-col space-y-1">
                                            <span>Published</span>
                                            <span class="text-muted-foreground text-xs font-normal">Visible on the website</span>
                                        </x-ui.label>
                                        <x-ui.switch
                                            id="status"
                                            name="status"
                                            value="1"
                                            :checked="old('status', $page->status ?? false)"
                                        />
                                    </div>
                                </x-ui.card-content>
                                <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                                    <x-ui.button variant="outline" href="{{ route('admin.pages.index') }}">Cancel</x-ui.button>
                                    <x-ui.button type="submit">Save Page</x-ui.button>
                                </x-ui.card-footer>
                            </x-ui.card>
                        </div>
                    </div>
                </form>
            </x-ui.tabs-content>

            @if(isset($page))
            <x-ui.tabs-content value="seo">
                <form action="{{ route('admin.pages.seo.update', $page) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <x-admin::seo-fields :model="$page" />
                    <div class="mt-6 flex justify-end gap-2">
                        <x-ui.button type="submit">Save SEO</x-ui.button>
                    </div>
                </form>
            </x-ui.tabs-content>
            @endif
        </x-ui.tabs>
    </div>
</x-layouts.admin>
