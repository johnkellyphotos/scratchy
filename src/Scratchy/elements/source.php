<?php

namespace Scratchy\elements;

use Exception;
use Scratchy\TagType;

class source extends Element
{
    public function __construct(
        private ?string $name = null,
        private ?string $value = null,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
        private ?string $content = null,
    )
    {
        $this->attributes['name'] = $this->name ??= ($this->id ?? $this->makeUniqueName());
        $this->attributes['value'] = $this->value ?? $this->getInputDefault($this->name);
        parent::__construct(TagType::source, $this->id, $this->classes, $this->attributes, $this->content, false);
    }

    /**
     * @throws Exception
     */
    public function append(...$args): void
    {
        throw new Exception('You may not append an element to input tags');
    }
}