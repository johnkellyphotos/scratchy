<?php

namespace Scratchy\component;

use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\h1;
use Scratchy\TagType;

class BlankSlide extends Element
{
    public function __construct(
        public array   $contentList = [],
        public ?string $title = null,
        public bool    $fullScreenContent = false,
    )
    {
        parent::__construct(
            TagType::div,
            classes: [
                'slide-frame',
                'h-100',
                'w-100',
                'd-flex',
                'align-items-center',
                'justify-content-center',
                'text-center'
            ]
        );

        if ($this->title !== null) {
            $this->append(
                new h1(
                    content: $this->title,
                    classes: [
                        'fw-bold',
                        'display-1',
                        'w-100'
                    ]
                )
            );
        }
    }
}
