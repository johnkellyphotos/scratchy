<?php

namespace component;

use elements\body;
use elements\Element;
use elements\head;
use elements\link;
use elements\meta;
use elements\script;
use elements\title;
use TagType;

class WebPage extends Element
{
    public function __construct(...$elementList)
    {
        parent::__construct(TagType::html);

        $head = new head();

        $meta = new meta(attributes: ['charset' => 'UTF-8']);
        $head->append($meta);

        $meta = new meta(attributes: ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
        $head->append($meta);

        $title = new title(content: 'This is a web page.');
        $head->append($title);

        $link = new link(attributes: ['rel' => 'stylesheet', 'href' => 'https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css']);
        $head->append($link);

        $body = new body(classes: ['p-3', 'm-0']);

        foreach ($elementList as $element) {
            $body->append($element);
        }

        $this->append($head);
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
