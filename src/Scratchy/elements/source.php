<?php

namespace Scratchy\elements;

use Exception;
use Scratchy\TagType;

class source extends Element
{
    public function __construct(
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        parent::__construct(TagType::source, $this->id, $this->classes, $this->attributes, null, false);
    }

    /**
     * @throws Exception
     */
    public function append(...$args): void
    {
        throw new Exception('You may not append an element to source tags');
    }
}
