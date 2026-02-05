<?php

namespace Scratchy\component;

use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\img;
use Scratchy\elements\h1;
use Scratchy\TagType;

class ParticipantsSlide extends Element
{
    public function __construct(
        public array $profiles = [],
    )
    {
        parent::__construct(
            TagType::div,
            classes: [
                'slide-frame',
                'h-100',
                'w-100',
                'd-flex',
                'flex-column',
                'align-items-center',
                'justify-content-center',
                'text-center'
            ]
        );

        $this->append(
            new h1(
                content: "6 People who did not die at John's birthday party",
                classes: ['fw-bold', 'display-1', 'mb-5']
            )
        );

        $grid = new div(classes: [
            'd-flex',
            'flex-wrap',
            'justify-content-center',
            'gap-5'
        ]);

        foreach ($this->profiles as $profile) {
            $item = new div(classes: [
                'd-flex',
                'flex-column',
                'align-items-center'
            ]);

            $item->append(
                new img(
                    classes: ['rounded-circle', 'mb-3'],
                    attributes: [
                        'style' => 'width:160px;height:160px;object-fit:cover;',
                        'src' => $profile['url'],
                    ]
                )
            );

            $item->append(
                new div(
                    classes: ['fw-semibold'],
                    content: $profile['name']
                )
            );

            $grid->append($item);
        }

        $this->append($grid);
    }
}
