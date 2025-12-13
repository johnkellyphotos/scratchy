<?php

namespace elements;

use TagType;

class script extends Element
{
    public function __construct(
        private ?string $src = null,
        private ?string $content = null,
    )
    {
        $this->src && $attributes = [
            'src' => $this->src,
        ];
        parent::__construct(TagType::script, null, null, $attributes ?? null, $this->content);
    }
}