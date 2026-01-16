<?php

namespace core;

enum RowActionType: string
{
    case EDIT = 'Edit';
    case DELETE = 'Delete';
}