<?php

namespace elements;

use Exception;
use TagType;

class head extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::head, $this->id, $this->classes, $this->attributes, $this->content);
    }
    }