<?php

namespace elements;

use TagType;

class p extends Element
{
    public function __construct(
        private ?string $content = null,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        parent::__construct(TagType::p, $this->id, $this->classes, $this->attributes, $this->content);
    }
}