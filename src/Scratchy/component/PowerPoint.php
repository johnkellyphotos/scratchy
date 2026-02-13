<?php

namespace Scratchy\component;

use Scratchy\elements\body;
use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\main;
use Scratchy\elements\script;
use Scratchy\elements\title;
use Scratchy\TagType;

class PowerPoint extends Element
{
    public title $title;

    public function __construct(...$powerPointSlides)
    {
        parent::__construct(TagType::html);

        $possiblePowerPointSlide = $powerPointSlides[0] ?? null;
        if ($possiblePowerPointSlide instanceof PowerPointSlides) {
            $powerPointSlides = $possiblePowerPointSlide;
        } else {
            $powerPointSlides = new PowerPointSlides(new PowerPointSlide());
        }

        $this->title ??= new title('Web Slides');
        $this->append(new PageHeader($this->title));

        $body = new body(classes: ['p-0', 'm-0', 'bg-light']);
        $div = new div(id: "topBannerHost");

        /* Main */
        $main = new main(classes: ['p-0', 'm-0', 'd-block'], attributes: ['style' => 'position:fixed; left:0; top:0; width:100%; height:100%;']);
        $body->append($main);

        $main->append($powerPointSlides);

        $this->append($body);

        /* Scripts */
        $this->append(new script(src: 'https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js'));
        $this->append(new script(src: '/scripts/slides.js'));
    }


    public function output(): void
    {
        echo '<!DOCTYPE html>' . "\n" . $this->render();
    }
}
