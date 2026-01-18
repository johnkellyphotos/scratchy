<?php

namespace Scratchy\component\Modal;

use Scratchy\elements\Element;
use Scratchy\elements\span;
use Scratchy\TagType;

class ModalButton extends Element
{
    public function __construct(ModalButtonType $ModalButtonType, ?string $class = null, ?string $text = null, ?string $icon = null, ?string $action = null)
    {
        $reload = 0;

        if ($ModalButtonType == ModalButtonType::OKAY_AND_RELOAD) {
            $ModalButtonType = ModalButtonType::OKAY;
            $reload = 1;
        }

        $class = match ($ModalButtonType) {
            ModalButtonType::OKAY => 'btn btn-primary',
            ModalButtonType::YES, ModalButtonType::SAVE => 'btn-success',
            ModalButtonType::NO, ModalButtonType::CANCEL => 'btn-danger',
            default => $class,
        };

        $action ??= $ModalButtonType !== ModalButtonType::CUSTOM ? $ModalButtonType->value : $action;

        $text ??= $ModalButtonType !== ModalButtonType::CUSTOM ? $ModalButtonType->value : $text;

        parent::__construct(tagType: TagType::button, classes: ['btn', c($class)], attributes: ['data-app-modal-action' => c($action), 'data-app-modal-reload' => $reload]);

        $span = new span(content: c($text));
        $this->append($span);
    }
}