<?php

namespace core;

enum RowActionType: string
{
    case DELETE = 'Delete';
    case EDIT = 'Edit';
    case VIEW = 'View';
}