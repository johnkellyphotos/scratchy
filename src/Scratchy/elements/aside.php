<?php

namespace Scratchy\elements;

use Scratchy\TagType;

class aside extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::aside, $this->id, $this->classes, $this->attributes, $this->content);
    }
}
