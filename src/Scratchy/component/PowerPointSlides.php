<?php

namespace Scratchy\component;

use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\TagType;

class PowerPointSlides extends Element
{
    public int $currentSlide = 0;

    public function __construct(...$powerPointSlides)
    {
        parent::__construct(
            tagType: TagType::div,
            id: 'stage',
            classes: ['position-relative', 'w-100', 'h-100', 'overflow-hidden'],
            attributes: ['style' => 'width:100%; height:100%;background: #000; color: #fff;']
        );

        foreach ($powerPointSlides as $index => $powerPointSlide) {
            $isCurrent = ($index === $this->currentSlide);

            $classes = ['ppt-slide', 'position-absolute', 'top-0', 'start-0', 'w-100', 'h-100'];
            if (!$powerPointSlide->fullScreenContent) {
                $classes[] = 'p-4';
            }

            $attrs = [
                'data-slide-index' => (string)$index,
                'style' => $isCurrent
                    ? 'opacity:1; visibility:visible; z-index:2; transition:opacity 400ms ease;'
                    : 'opacity:0; visibility:hidden; z-index:1; transition:opacity 400ms ease;'
            ];
            if ($isCurrent) {
                $attrs['is-current-slide'] = true;
            }

            $wrap = new div(id: 'slide-' . $index, classes: $classes, attributes: $attrs);
            $wrap->append($powerPointSlide);
            $this->append($wrap);
        }
    }
}