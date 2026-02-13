<?php

namespace Scratchy\elements;

use Exception;
use Scratchy\TagType;

class img extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        parent::__construct(TagType::img, $this->id, $this->classes, $this->attributes, null, false);
    }

    /**
     * @throws Exception
     */
    public function append(...$args): void
    {
        throw new Exception('You may not append an element to img tags');
    }
}
