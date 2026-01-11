<?php

namespace Scratchy\elements;

use Scratchy\elements\Element;
use Scratchy\TagType;

class nav extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::nav, $this->id, $this->classes, $this->attributes, $this->content);
    }
}