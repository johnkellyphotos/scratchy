<?php

namespace Scratchy\component\Modal;

use Scratchy\elements\button;
use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\h5;
use Scratchy\TagType;

class ModalContent extends Element
{
    public function __construct(string $title, array $modalButtons = [], ...$elementList)
    {
        parent::__construct(tagType: TagType::div);

        $header = new div(classes: ['modal-header', 'border-0']);
        $this->append($header);

        $title = new h5(content: c($title), classes: ['modal-title']);
        $header->append($title);

        $closeButton = new button(
            classes: ['btn-close'],
            attributes: [
                'type' => 'button',
                'data-mdb-dismiss' => 'modal',
                'aria-label' => 'Close',
            ]
        );
        $header->append($closeButton);

        $body = new div(classes: ['modal-body']);
        $this->append($body);

        $footer = new div(classes: ['modal-footer', 'border-0']);
        $this->append($footer);

        foreach ($modalButtons as $modalButton) {
            $footer->append(new ModalButton($modalButton));
        }

        foreach ($elementList as $element) {
            $body->append($element);
        }
    }
}