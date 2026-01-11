<?php

namespace Scratchy\elements;

use Exception;
use Scratchy\TagType;

class option extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        parent::__construct(TagType::option, $this->id, $this->classes, $this->attributes, $this->content);
    }

    /**
     * @throws Exception
     */
    public function append(...$args): void
    {
        throw new Exception('You may not append an element to option tags');
    }
}