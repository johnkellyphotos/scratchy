<?php

namespace Scratchy\component;

use Scratchy\elements\body;
use Scratchy\elements\Element;
use Scratchy\elements\main;
use Scratchy\elements\script;
use Scratchy\elements\title;
use Scratchy\TagType;

class WebPage extends Element
{
    public title $title;

    public function __construct(...$elementList)
    {
        $elementList ??= [];
        parent::__construct(TagType::html);
        $this->title ??= new title();

        $this->append(new PageHeader($this->title));

        $body = new body(classes: ['p-0', 'm-0']);
        $body->append(new NavigationMenu());

        $main = new main(classes: ['p-2']);
        $body->append($main);

        foreach ($elementList as $element) {
            $main->append($element);
        }

        $this->append($body);

        $script = new script(src: 'https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js');
        $this->append($script);

        $script = new script(content: <<<HTML
            console.log("test");
        HTML
        );
        $this->append($script);
    }

    public function output(): void
    {
        echo '<!DOCTYPE html>' . "\n" . $this->render();
    }
}
