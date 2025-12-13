<?php

namespace elements;

use TagType;

class form extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::form, $this->id, $this->classes, $this->attributes, $this->content);
    }
}