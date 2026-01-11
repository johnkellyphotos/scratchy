<?php

namespace Scratchy\elements;

use Scratchy\TagType;

class a extends Element
{
    public function __construct(
        private ?string $content = null,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        parent::__construct(TagType::a, $this->id, $this->classes, $this->attributes, $this->content);
    }
}