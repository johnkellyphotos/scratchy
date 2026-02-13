<?php

namespace controller;

use core\Controller;
use Scratchy\component\PageContent;

use Scratchy\elements\Element;
use Scratchy\elements\h1;
use Scratchy\elements\h2;
use Scratchy\elements\h3;
use Scratchy\elements\li;
use Scratchy\elements\p;
use Scratchy\elements\ul;

/** @noinspection PhpUnused */
class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function index(): ?Element
    {
        $features = new ul(classes: ['mb-4']);
        $features->append(new li(content: 'Pages from reusable elements with clean, consistent markup.'));
        $features->append(new li(content: 'Components for navigation, forms, and modals you can extend.'));
        $features->append(new li(content: 'Controllers that keep logic separate from presentation.'));
        $features->append(new li(content: 'A simple path to add models and persistent data.'));

        return new PageContent(
            new h1(content: 'Scratchy Website Template', classes: ['primary-color']),
            new h2(content: 'Build websites quickly and cleanly.'),
            new p(
                content: 'Scratchy is a playful, component-first template that lets you assemble pages from reusable elements, keep layout consistent, and ship new screens fast. Add components, wire controllers to views, and scale from a simple landing page to a full site without rewriting your markup.',
                classes: ['lead']
            ),
            new h3(content: 'What you can build', classes: ['mt-4']),
            $features,
            new p(
                content: 'Think landing pages, dashboards, marketing sites, internal tools, or event microsites. Scratchy is flexible enough to start small and sturdy enough to grow with you.',
            ),
            new h3(content: 'How data is handled', classes: ['mt-4']),
            new p(
                content: 'Controllers can pull data from models, sanitize inputs, and pass clean values into elements. Elements escape output by default to prevent unsafe HTML, while script and style content can be explicitly rendered raw when needed.',
            ),
            new p(
                content: 'Forms can map to inputs with sensible defaults, making it easy to capture and validate user input while keeping templates readable.',
            ),
            new h3(content: 'Why it feels fun', classes: ['mt-4']),
            new p(
                content: 'Because building a site should feel like snapping blocks together. Build a new section in minutes, refactor safely, and keep your pages expressive without a huge framework.',
            ),
        );
    }
}
