<?php

namespace Blaze\AdminCore\Support;

/**
 * Describes a single extra field injected into the service form by the host project.
 *
 * @property-read string                $name       HTML input name / DB column name
 * @property-read string                $type       Field type: text|textarea|number|select|checkbox|file
 * @property-read string                $label      Human-readable label
 * @property-read string|null           $placeholder
 * @property-read bool                  $required
 * @property-read string|null           $validationRule  Laravel validation rule string (e.g. 'nullable|string|max:255')
 * @property-read array<string, string> $options    For 'select' type: ['value' => 'Label', ...]
 * @property-read array<string, mixed>  $attributes Extra HTML attributes passed to the input component
 */
final class ServiceFormField
{
    /**
     * @param  array<string, string>  $options
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly string $label,
        public readonly ?string $placeholder = null,
        public readonly bool $required = false,
        public readonly ?string $validationRule = null,
        public readonly array $options = [],
        public readonly array $attributes = [],
    ) {}

    /**
     * Named constructor for fluent definition in project configuration classes.
     *
     * @param  array<string, string>  $options
     * @param  array<string, mixed>  $attributes
     */
    public static function make(
        string $name,
        string $type,
        string $label,
        ?string $placeholder = null,
        bool $required = false,
        ?string $validationRule = null,
        array $options = [],
        array $attributes = [],
    ): self {
        return new self($name, $type, $label, $placeholder, $required, $validationRule, $options, $attributes);
    }
}
