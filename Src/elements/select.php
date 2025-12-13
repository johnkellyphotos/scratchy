<?php

namespace elements;

use TagType;

class select extends Element
{
    public function __construct(
        private ?string $name = null,
        private ?string $value = null,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        $this->name && $this->attributes['name'] = $this->name;
        $this->value && $this->attributes['value'] = $this->value;
        parent::__construct(TagType::select, $this->id, $this->classes, $this->attributes, $this->content);
    }
}