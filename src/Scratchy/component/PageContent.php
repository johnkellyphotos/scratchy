<?php

namespace Scratchy\component;

use Scratchy\elements\Element;
use Scratchy\elements\title;
use Scratchy\TagType;

class PageContent extends Element
{
    public function __construct(...$elementList)
    {
        parent::__construct(TagType::div);

        foreach ($elementList as $element) {
            $this->append($element);
        }

    }
}
