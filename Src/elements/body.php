<?php

namespace elements;

use TagType;

class body extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::body, $this->id, $this->classes, $this->attributes, $this->content);
    }
}