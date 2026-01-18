<?php

namespace core;

readonly class RowAction
{
    public string $icon;
    public string $label;
    public string $action;

    public function __construct(
        private RowActionType $rowActionType,
    )
    {
        $this->icon = match ($this->rowActionType) {
            RowActionType::DELETE => '&#128465;',
            RowActionType::EDIT => '&#x270E;',
            RowActionType::VIEW => '&#128065;',
        };

        $this->label = match ($this->rowActionType) {
            RowActionType::DELETE => 'Delete',
            RowActionType::EDIT => 'Edit',
            RowActionType::VIEW => 'View',
        };

        $this->action = match ($this->rowActionType) {
            RowActionType::DELETE => '/Modal/delete-record/',
            RowActionType::EDIT => '/Modal/edit-record/',
            RowActionType::VIEW => '/Modal/view-record/',
        };
    }

    public function action(): string
    {
        return $this->action;
    }
}