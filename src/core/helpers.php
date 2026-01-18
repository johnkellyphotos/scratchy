<?php

function c(?string $value = null): string
{
    if (is_null($value)) {
        return '';
    }

    return htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
        'UTF-8'
    );
}