<?php

namespace Scratchy;

enum InputType: string
{
    case none = 'none';
    case text = 'text';
    case password = 'password';
    case select = 'select';
    case datetime = 'datetime-local';
    case date = 'date';
    case time = 'time';
}
