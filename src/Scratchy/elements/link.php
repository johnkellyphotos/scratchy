<?php

namespace Scratchy\elements;

use Scratchy\TagType;

class link extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        parent::__construct(TagType::link, $this->id, $this->classes, $this->attributes, null, false);
    }
}