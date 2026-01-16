<?php

namespace core;

readonly class RowAction
{
    public string $icon;
    public string $label;

    public function __construct(
        private RowActionType $rowActionType,
    )
    {
        $this->icon = match ($this->rowActionType) {
            RowActionType::EDIT => '&#x270E;',
            RowActionType::DELETE => '&#128465;',
        };

        $this->label = match ($this->rowActionType) {
            RowActionType::EDIT => 'Edit',
            RowActionType::DELETE => 'Delete',
        };
    }
}