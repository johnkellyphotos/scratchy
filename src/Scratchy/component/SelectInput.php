<?php

namespace Scratchy\component;

use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\label;
use Scratchy\elements\option;
use Scratchy\elements\select;
use Scratchy\TagType;

class SelectInput extends Element
{
    public function __construct(
        private ?string $label = null,
        private ?string $description = null,
        private ?array  $options = [],
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
        parent::__construct(TagType::div, $this->id, $this->classes, $this->attributes);

        $id = $this->createId();

        $select = new select(
            name: $inputName,
            id: $id,
            classes: ['form-control', 'border'],
        );

        foreach ($this->options as $name => $value) {
            $select->append(new option(
                attributes: ['value' => $value],
                content: $name,
            ));
        }

        $label = new label(
            classes: ['form-label'],
            attributes: [
                'for' => $id,
            ],
            content: $this->label ?: 'Select',
        );

        $this->append($select);
        $this->append($label);

        if ($this->description) {
            $div = new div(
                classes: ['form-helper'],
                content: $this->description,
            );
            $this->append($div);
        }
    }
}
