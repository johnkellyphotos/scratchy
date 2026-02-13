<?php

namespace Scratchy\component;

use Scratchy\elements\Element;
use Scratchy\elements\link;
use Scratchy\elements\meta;
use Scratchy\elements\style;
use Scratchy\TagType;

class PageHeader extends element
{
    public function __construct(private $title)
    {
        parent::__construct(TagType::head);

        $meta = new meta(attributes: ['charset' => 'UTF-8']);
        $this->append($meta);

        $meta = new meta(attributes: ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
        $this->append($meta);

        $this->append($this->title);

        $link = new link(attributes: ['rel' => 'stylesheet', 'href' => 'https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css']);
        $this->append($link);
        $styles = [
            ':root' =>[
                '--color-primary' => APP_PRIMARY_COLOR,
                '--color-secondary' => APP_SECONDARY_COLOR,
                '--color-tertiary' => APP_TERTIARY_COLOR,
            ],
            '.primary-color' => [
                'color' => 'var(--color-primary)',
                'border-color' => 'var(--color-primary)',
                'background' => 'var(--color-tertiary)',
            ],
            '.secondary-color' => [
                'color' => 'var(--color-tertiary)',
                'border-color' => 'var(--color-secondary)',
                'background' => 'var(--color-secondary)',
            ]
        ];

        $styleContentHtml = '';
        foreach ($styles as $styleReference => $styleContents) {
            $styleContentHtml .= "$styleReference{";
            foreach ($styleContents as $style => $value) {
                $styleContentHtml .= "$style:$value;";
            }
            $styleContentHtml .= "}";
        }
        $style = new style(content: $styleContentHtml);
        $this->append($style);
    }
}
