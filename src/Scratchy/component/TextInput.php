<?php

namespace Scratchy\component;

use Scratchy\InputType;
use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\input;
use Scratchy\elements\label;
use Scratchy\TagType;

class TextInput extends Element
{
    public function __construct(
        private ?string $label = null,
        private ?string $description = null,
        private ?string $id = null,
        private ?array  $classes = [],
        private ?array  $attributes = [],
    )
    {
        $inputName = null;

        if (isset($attributes['name'])) {
            $inputName = $attributes['name'];
            unset($attributes['name']);
        }

        $this->classes = ['form-outline', 'mb-4', ...$this->classes];
        parent::__construct(TagType::div, $this->id, $this->classes);

        $id = $this->createId();

        $input = new input(
            name: $inputName,
            id: $id,
            classes: ['form-control', 'border'],
            attributes: [...$this->attributes, 'type' => InputType::text->value,],
        );

        $label = new label(
            classes: ['form-label'],
            attributes: [
                'for' => $id,
            ],
            content: $this->label ?: 'Text',
        );

        $this->append($input);
        $this->append($label);

        if ($this->description) {
            $div = new div(
                content: $this->description,
            );
            $this->append($div);
        }
    }
}
