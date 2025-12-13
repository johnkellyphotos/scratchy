<?php

namespace elements;

use TagType;

class i extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::i, $this->id, $this->classes, $this->attributes, $this->content);
    }
}