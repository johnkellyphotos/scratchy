<?php

namespace elements;

use Exception;
use TagType;

class style extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::style, $this->id, $this->classes, $this->attributes, $this->content);
    }
}