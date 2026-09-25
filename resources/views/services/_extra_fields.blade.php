{{--
    Renders extra site-specific fields defined via AdminCoreConfiguration::serviceFormFields().

    Variables:
      $fields  ServiceFormField[]
      $model   Service|null
--}}
<x-ui.card>
    <x-ui.card-header>
        <x-ui.card-title>Additional Details</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content class="space-y-4">
        @foreach ($fields as $field)
            <x-ui.field>
                <x-ui.field-label :for="$field->name">
                    {{ $field->label }}
                    @if ($field->required)
                        <span class="text-destructive ml-0.5">*</span>
                    @endif
                </x-ui.field-label>

                @switch($field->type)

                    @case('textarea')
                        <x-ui.textarea
                            :id="$field->name"
                            :name="$field->name"
                            :placeholder="$field->placeholder"
                            rows="3"
                            v-bind="$field->attributes"
                        >{{ old($field->name, $model?->{$field->name} ?? '') }}</x-ui.textarea>
                        @break

                    @case('richtext')
                        <x-ui.rich-text-editor
                            :name="$field->name"
                            :value="old($field->name, $model?->{$field->name} ?? '')"
                        />
                        @break

                    @case('select')
                        <x-ui.select :name="$field->name">
                            <x-ui.select-trigger>
                                <x-ui.select-value :placeholder="$field->placeholder ?? 'Select…'" />
                            </x-ui.select-trigger>
                            <x-ui.select-content>
                                <x-ui.select-item value="">— None —</x-ui.select-item>
                                @foreach ($field->options as $value => $optionLabel)
                                    <x-ui.select-item
                                        :value="$value"
                                        :selected="old($field->name, $model?->{$field->name} ?? '') == $value"
                                    >{{ $optionLabel }}</x-ui.select-item>
                                @endforeach
                            </x-ui.select-content>
                        </x-ui.select>
                        @break

                    @case('checkbox')
                        <x-ui.switch
                            :id="$field->name"
                            :name="$field->name"
                            value="1"
                            :checked="old($field->name, $model?->{$field->name} ?? false)"
                        />
                        @break

                    @case('file')
                        <x-ui.file-upload
                            :name="$field->name"
                            :current="old($field->name, $model?->{$field->name} ?? null)"
                        />
                        @break

                    @case('number')
                        <x-ui.input
                            type="number"
                            :id="$field->name"
                            :name="$field->name"
                            :value="old($field->name, $model?->{$field->name} ?? '')"
                            :placeholder="$field->placeholder"
                        />
                        @break

                    @default
                        {{-- text, email, url, date, etc. --}}
                        <x-ui.input
                            :type="$field->type"
                            :id="$field->name"
                            :name="$field->name"
                            :value="old($field->name, $model?->{$field->name} ?? '')"
                            :placeholder="$field->placeholder"
                        />

                @endswitch

                <x-ui.field-error :name="$field->name" />
            </x-ui.field>
        @endforeach
    </x-ui.card-content>
</x-ui.card>
