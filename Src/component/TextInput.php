<?php

namespace component;

use elements\div;
use elements\Element;
use elements\input;
use elements\label;
use InputType;
use TagType;

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
        $this->classes = ['form-outline', 'mb-4', ...$this->classes];
        parent::__construct(TagType::div, $this->id, $this->classes);

        $id = $this->createId();

        $input = new input(
            id: $id,
            classes: ['form-control', 'border'],
            attributes: [...$this->attributes, 'type' => InputType::text->value],
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
