<x-layouts.admin title="{{ isset($paymentMethod) ? 'Edit' : 'Add' }} Payment Method">
    <x-slot:header>
        <x-ui.breadcrumb>
            <x-ui.breadcrumb-list>
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-link href="{{ route('admin.payment-methods.index') }}">We Accept</x-ui.breadcrumb-link>
                </x-ui.breadcrumb-item>
                <x-ui.breadcrumb-separator />
                <x-ui.breadcrumb-item>
                    <x-ui.breadcrumb-page>{{ isset($paymentMethod) ? 'Edit' : 'Add' }}</x-ui.breadcrumb-page>
                </x-ui.breadcrumb-item>
            </x-ui.breadcrumb-list>
        </x-ui.breadcrumb>
    </x-slot:header>

    <div class="max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">{{ isset($paymentMethod) ? 'Edit' : 'Add' }} Payment Method</h2>
                <p class="text-muted-foreground mt-1 text-sm">Configure accepted payment card/method badges and optional customer details.</p>
            </div>
        </div>

        <form
            action="{{ isset($paymentMethod) ? route('admin.payment-methods.update', $paymentMethod) : route('admin.payment-methods.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @if (isset($paymentMethod)) @method('PUT') @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-2">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Payment Details</x-ui.card-title>
                            <x-ui.card-description>Specify the name and information for this payment method.</x-ui.card-description>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <x-ui.field>
                                <x-ui.field-label for="name" required>Method / Card Name</x-ui.field-label>
                                <x-ui.input
                                    id="name"
                                    name="name"
                                    placeholder="e.g. Dollar Card, Master Card, Visa Card"
                                    value="{{ old('name', $paymentMethod->name ?? '') }}"
                                />
                                <x-ui.field-error name="name" />
                            </x-ui.field>

                            <x-ui.field>
                                <x-ui.field-label for="details">Configure Details / Instructions</x-ui.field-label>
                                <x-ui.rich-text-editor
                                    name="details"
                                    :value="old('details', $paymentMethod->details ?? '')"
                                    placeholder="Enter surcharge info, currency specifications, accepted banks, or payment instructions..."
                                />
                                <x-ui.field-description>
                                    Optional. When customers click or view this payment badge on the site, these configured details will be displayed in an informative modal popup.
                                </x-ui.field-description>
                                <x-ui.field-error name="details" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>

                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Badge / Logo</x-ui.card-title>
                            <x-ui.card-description>Card or gateway icon.</x-ui.card-description>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <x-ui.field>
                                <x-ui.field-label for="logo">Logo File</x-ui.field-label>
                                <x-ui.file-upload
                                    name="logo"
                                    accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                                    :current="old('logo', $paymentMethod->logo ?? null)"
                                />
                                <x-ui.field-description>Recommended: PNG or SVG with transparent background.</x-ui.field-description>
                                <x-ui.field-error name="logo" />
                            </x-ui.field>
                        </x-ui.card-content>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Visibility</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div class="flex items-center justify-between">
                                <x-ui.label for="status" class="flex flex-col space-y-1">
                                    <span>Active (Visible)</span>
                                    <span class="text-muted-foreground text-xs font-normal">Show in footer payment badges. Uncheck to hide.</span>
                                </x-ui.label>
                                <x-ui.switch
                                    id="status"
                                    name="status"
                                    value="1"
                                    :checked="old('status', $paymentMethod->status ?? true)"
                                />
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-ui.button variant="outline" href="{{ route('admin.payment-methods.index') }}">Cancel</x-ui.button>
                <x-ui.button type="submit">{{ isset($paymentMethod) ? 'Update Payment Method' : 'Save Payment Method' }}</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
