<?php

namespace Scratchy\component;

use core\Database\Model;
use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\input;
use Scratchy\elements\label;
use Scratchy\elements\button;
use Scratchy\elements\textarea;
use Scratchy\InputType;
use Scratchy\TagType;

class EditRecord extends Element
{
    public function __construct(Model $record)
    {
        parent::__construct(tagType: TagType::div, classes: ['div']);

        $modelName = substr($record::class, strrpos($record::class, '\\') + 1);

        $header = new div(classes: ['card-header', 'py-2']);
        $header->append(new Element(tagType: TagType::strong, content: c($modelName)));
        $this->append($header);

        $body = new div(classes: ['card-body']);
        $this->append($body);

        foreach (get_object_vars($record) as $key => $value) {
            if ($key === 'id') {
                $body->append(new input(
                    name: $key,
                    value: c($value),
                    attributes: [
                        'type' => 'hidden',
                    ]
                ));
                continue;
            }

            if ($key === 'label') {
                continue;
            }

            $inputType = $record->getInputTypeForColumn($key);
            if ($inputType === InputType::none) {
                continue;
            }

            $group = new div(classes: ['mb-3']);

            $elementId = Element::createId();

            $group->append(new label(
                attributes: ['class' => 'form-label', 'for' => c($elementId)],
                content: c((string)$key)
            ));

            if ($inputType === InputType::textarea) {
                $group->append(new textarea(
                    name: c($key),
                    value: (string)($value ?? ''),
                    id: $elementId,
                    classes: ['form-control'],
                    attributes: [
                        'type' => $inputType->value,
                    ]
                ));
            } else {
                $group->append(new input(
                    name: c($key),
                    value: c($value ?? ''),
                    id: $elementId,
                    classes: ['form-control'],
                    attributes: [
                        'type' => $inputType->value,
                    ]
                ));
            }

            $body->append($group);
        }
    }
}
