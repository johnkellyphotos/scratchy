<?php

namespace Scratchy\component;

use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\h1;
use Scratchy\TagType;

class PowerPointSlide extends Element
{
    public function __construct(
        public array   $contentList = [],
        public ?string $title = null,
        public bool    $fullScreenContent = false,
    )
    {
        parent::__construct(tagType::div, classes: ['slide-frame', 'h-100']);

        if ($contentList == [] && !$this->title) {
            $contentList[] = new div(classes: ['text-muted', 'd-flex', 'align-items-center', 'justify-content-center', 'h-100'], content: '');
        }

        /* Fullscreen content mode */
        if ($fullScreenContent === true) {
            foreach ($contentList as $element) {
                $this->append($element);
            }
            return;
        }

        /* Title (optional) */
        if ($title !== null) {
            $titleDiv = new div(classes: ['mb-4']);
            $titleDiv->append(new h1(content: $title, classes: ['fw-bold', 'text-center']));
            $this->append($titleDiv);
        }

        /* Content container */
        $contentWrapper = new div(classes: ['w-100', 'h-100']);
        foreach ($contentList as $element) {
            $contentWrapper->append($element);
        }


        $this->append($contentWrapper);
    }
}
