<?php

namespace elements;

use TagType;

class h1 extends Element
{
    public function __construct(
        private ?string $content = null,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        parent::__construct(TagType::h1, $this->id, $this->classes, $this->attributes, $this->content);
    }
}