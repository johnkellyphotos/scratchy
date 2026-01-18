<?php

namespace Scratchy\component\Modal;

use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\TagType;

class Modal extends Element
{
    public function __construct(...$elementList)
    {
        parent::__construct(
            tagType: TagType::div,
            id: Element::createId(),
            classes: ['modal', 'fade'],
            attributes: [
                'tabindex' => '-1',
                'aria-hidden' => 'true',
            ]
        );

        $wrapper = new div(classes: ['modal-dialog', 'modal-dialog-centered', 'modal-dialog-scrollable', 'modal-top']);
        $this->append($wrapper);

        $content = new div(classes: ['modal-content']);
        $wrapper->append($content);

        foreach ($elementList as $element) {
            $content->append($element);
        }
    }
}
