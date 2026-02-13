<?php

namespace lib;

enum DateTimeFormat: string
{
    case DATE_SHORT = 'M j, Y';
    case DATE_LONG = 'F j, Y';
    case DATE_ISO = 'Y-m-d';
    case DATETIME_SHORT = 'M j, Y g:i A';
    case DATETIME_LONG = 'F j, Y g:i A';
    case DATETIME_ISO = 'Y-m-d H:i:s';
    case DATETIME_RFC3339 = 'c';
    case DATETIME_SHORT_TZ = 'M j, Y g:i A T';
    case DATETIME_LONG_TZ = 'F j, Y g:i A T';
    case DATETIME_ISO_TZ = 'Y-m-d H:i:s T';
    case TIME_12 = 'g:i A';
    case TIME_24 = 'H:i';
}
