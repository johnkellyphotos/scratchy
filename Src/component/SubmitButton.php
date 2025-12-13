<?php

namespace component;

use elements\Element;
use TagType;

class SubmitButton extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        $this->classes[] = 'btn';
        $this->classes[] = 'btn-primary';
        $this->attributes['type'] = 'submit';

        parent::__construct(TagType::button, $this->id, $this->classes, $this->attributes, $this->content);
    }
}
