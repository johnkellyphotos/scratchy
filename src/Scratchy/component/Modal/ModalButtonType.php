<?php

namespace Scratchy\component\Modal;

enum ModalButtonType: string
{
    case CANCEL = 'Cancel';
    case CUSTOM = 'Custom';
    case OKAY = 'Okay';
    case OKAY_AND_RELOAD = 'Okay_and_reload';
    case YES = 'Yes';
    case SAVE = 'Save';
    case NO = 'No';
}
