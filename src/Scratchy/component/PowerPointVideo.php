<?php

namespace Scratchy\component;

use Scratchy\elements\Element;
use Scratchy\elements\source;
use Scratchy\elements\video;
use Scratchy\TagType;

class PowerPointVideo extends Element
{
    public function __construct(string $src)
    {
        parent::__construct(tagType: TagType::div, classes: ['ratio', 'ratio-16x9', 'h-100', 'w-100']);
        $video = new video(classes: ['w-100', 'h-100'], attributes: ['data-autoplay-video' => '', 'autoplay' => '', 'playsinline' => '', 'style' => 'object-fit: cover;']);
        $this->append($video);
        $video->append(new source(attributes: ['src' => $src, 'type' => 'video/mp4']));
    }
}
