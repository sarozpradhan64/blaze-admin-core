<x-layouts.admin title="{{ isset($teamMember) ? 'Edit' : 'Add' }} Team Member">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.team-members.index') }}">Team Members</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($teamMember) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold tracking-tight">
                {{ isset($teamMember) ? 'Edit' : 'Add' }} Team Member
            </h2>
        </div>

        <form
            action="{{ isset($teamMember) ? route('admin.team-members.update', $teamMember) : route('admin.team-members.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if(isset($teamMember)) @method('PUT') @endif

            <div class="grid gap-6 md:grid-cols-3">

                {{-- Left: Main Details --}}
                <div class="md:col-span-2 space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Personal Details</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label for="name">Name</x-ui.field-label>
                                    <x-ui.input id="name" name="name" value="{{ old('name', $teamMember->name ?? '') }}" />
                                    <x-ui.field-error name="name" />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label for="role">Role / Position</x-ui.field-label>
                                    <x-ui.input id="role" name="role" value="{{ old('role', $teamMember->role ?? '') }}" />
                                    <x-ui.field-error name="role" />
                                </x-ui.field>
                            </div>

                            <x-ui.field>
                                <x-ui.field-label for="bio">Bio</x-ui.field-label>
                                <x-ui.rich-text-editor name="bio">{!! old('bio', $teamMember->bio ?? '') !!}</x-ui.rich-text-editor>
                                <x-ui.field-error name="bio" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Social Links</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label for="linkedin_url">LinkedIn URL</x-ui.field-label>
                                    <x-ui.input id="linkedin_url" name="linkedin_url" type="url" placeholder="https://linkedin.com/in/..." value="{{ old('linkedin_url', $teamMember->linkedin_url ?? '') }}" />
                                    <x-ui.field-error name="linkedin_url" />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label for="twitter_url">Twitter / X URL</x-ui.field-label>
                                    <x-ui.input id="twitter_url" name="twitter_url" type="url" placeholder="https://twitter.com/..." value="{{ old('twitter_url', $teamMember->twitter_url ?? '') }}" />
                                    <x-ui.field-error name="twitter_url" />
                                </x-ui.field>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-ui.field>
                                    <x-ui.field-label for="facebook_url">Facebook URL</x-ui.field-label>
                                    <x-ui.input id="facebook_url" name="facebook_url" type="url" placeholder="https://facebook.com/..." value="{{ old('facebook_url', $teamMember->facebook_url ?? '') }}" />
                                    <x-ui.field-error name="facebook_url" />
                                </x-ui.field>
                                <x-ui.field>
                                    <x-ui.field-label for="instagram_url">Instagram URL</x-ui.field-label>
                                    <x-ui.input id="instagram_url" name="instagram_url" type="url" placeholder="https://instagram.com/..." value="{{ old('instagram_url', $teamMember->instagram_url ?? '') }}" />
                                    <x-ui.field-error name="instagram_url" />
                                </x-ui.field>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                {{-- Right: Sidebar --}}
                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Photo</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <x-ui.file-upload
                                name="image"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                :current="old('image', $teamMember->image ?? null)"
                            />
                            <x-ui.field-error name="image" />
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Settings</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-ui.label for="is_active" class="flex flex-col space-y-1">
                                    <span>Active</span>
                                    <span class="font-normal text-xs text-muted-foreground">Show on website</span>
                                </x-ui.label>
                                <x-ui.switch id="is_active" name="is_active" value="1" :checked="old('is_active', $teamMember->is_active ?? true)" />
                            </div>


                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.team-members.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Member</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
