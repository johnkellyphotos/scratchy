<?php

namespace Scratchy\elements;

use Scratchy\TagType;

class header extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::header, $this->id, $this->classes, $this->attributes, $this->content);
    }
}
