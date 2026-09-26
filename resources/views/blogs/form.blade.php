<x-layouts.admin title="{{ isset($blog) ? 'Edit Blog Post' : 'Add Blog Post' }}">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.blogs.index') }}">Blog</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($blog) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-5xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">
                {{ isset($blog) ? 'Edit Blog Post' : 'Add New Blog Post' }}
            </h2>
        </div>

        <form
            action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($blog))
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
                                <x-ui.field-label for="title">Title</x-ui.field-label>
                                <x-ui.input id="title" name="title" value="{{ old('title', $blog->title ?? '') }}" />
                                <x-ui.field-error name="title" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="blog_category_id">Category</x-ui.field-label>
                                <x-ui.select name="blog_category_id">
                                    <x-ui.select-trigger>
                                        <x-ui.select-value placeholder="Select a category" />
                                    </x-ui.select-trigger>
                                    <x-ui.select-content>
                                        <x-ui.select-item value="">None</x-ui.select-item>
                                        @foreach ($categories as $category)
                                            <x-ui.select-item
                                                value="{{ $category->id }}"
                                                :selected="old('blog_category_id', $blog->blog_category_id ?? '') ==
                                                $category->id"
                                            >
                                                {{ $category->name }}
                                            </x-ui.select-item>
                                        @endforeach
                                    </x-ui.select-content>
                                </x-ui.select>
                                <x-ui.field-error name="blog_category_id" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="author_id">Author</x-ui.field-label>
                                <x-ui.select name="author_id">
                                    <x-ui.select-trigger>
                                        <x-ui.select-value placeholder="Select an author" />
                                    </x-ui.select-trigger>
                                    <x-ui.select-content>
                                        <x-ui.select-item value="">System</x-ui.select-item>
                                        @foreach ($authors as $author)
                                            <x-ui.select-item
                                                value="{{ $author->id }}"
                                                :selected="old('author_id', $blog->author_id ?? auth()->id()) ==
                                                $author->id"
                                            >
                                                {{ $author->name }}
                                            </x-ui.select-item>
                                        @endforeach
                                    </x-ui.select-content>
                                </x-ui.select>
                                <x-ui.field-error name="author_id" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="excerpt">Excerpt</x-ui.field-label>
                                <x-ui.textarea
                                    id="excerpt"
                                    name="excerpt"
                                    rows="3"
                                >
                                    {{ old('excerpt', $blog->excerpt ?? '') }}</x-ui.textarea>
                                <x-ui.field-error name="excerpt" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="content">Content</x-ui.field-label>
                                <x-ui.rich-text-editor name="content" :value="old('content', $blog->content ?? '')" />
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
                                :current="old('featured_image', $blog->featured_image ?? null)"
                            />
                            <x-ui.field-error name="featured_image" />
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Tags</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <x-admin-core::admin.tag-picker
                                name="tags"
                                :value="old('tags', $blogTags ?? '')"
                                placeholder="Search or create tags..."
                            />
                            <x-ui.field-error name="tags" />
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
                                    <span class="text-muted-foreground text-xs font-normal">Visible on the blog listing</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="status"
                                    name="status"
                                    value="1"
                                    :checked="old('status', $blog->status ?? false)"
                                />
                            </div>
                        </x-ui.card-content>
                        <x-ui.card-footer class="bg-muted/50 flex justify-end gap-2 border-t p-4">
                            <x-ui.button variant="outline" href="{{ route('admin.blogs.index') }}">Cancel</x-ui.button>
                            <x-ui.button type="submit">Save Blog Post</x-ui.button>
                        </x-ui.card-footer>
                    </x-ui.card>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>
