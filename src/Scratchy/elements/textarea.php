<?php

namespace Scratchy\elements;

use Exception;
use Scratchy\TagType;

class textarea extends Element
{
    public function __construct(
        private ?string $name = null,
        private ?string $value = null,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        $this->attributes['name'] = $this->name ??= ($this->id ?? $this->makeUniqueName());
        $content = $this->value ?? $this->getInputDefault($this->name);
        unset($this->value);
        parent::__construct(TagType::textarea, $this->id, $this->classes, $this->attributes, $content);
    }

    /**
     * @throws Exception
     */
    public function append(...$args): void
    {
        throw new Exception('You may not append an element to input tags');
    }
}