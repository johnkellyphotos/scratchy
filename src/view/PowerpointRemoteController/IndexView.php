<?php

namespace view\PowerpointRemoteController;

use Scratchy\component\PageContent;
use Scratchy\elements\button;
use Scratchy\elements\div;
use Scratchy\elements\h4;
use Scratchy\elements\input;
use Scratchy\elements\script;
use Throwable;

class IndexView extends PageContent
{
    /**
     * @throws Throwable
     */
    public function __construct(?bool $executeBuild = false)
    {
        parent::__construct();

        /* Fullscreen stage */
        $stage = new div(
            id: 'stage',
            classes: ['d-flex', 'align-items-center', 'justify-content-center'],
        );

        /* Panel */
        $panelWrap = new div(classes: ['w-100', 'h-100', 'p-3']);
        $panel = new div(classes: ['card', 'shadow-2-strong', 'h-100']);
        $panelBody = new div(classes: ['card-body', 'd-flex', 'flex-column', 'gap-3']);

        $header = new div(classes: ['d-flex', 'align-items-center', 'justify-content-between']);
        $header->append(new h4(content: 'Remote Control', classes: ['m-0', 'fw-bold']));
        $header->append(new div(id: 'remote-status', classes: ['text-muted', 'small'], content: 'Disconnected'));
        $panelBody->append($header);

        /* Buttons */
        $grid = new div(classes: ['row', 'g-2']);

        $div = new div(classes: ['col-6']);
        $div->append(new button(
            classes: ['btn', 'btn-outline-primary', 'w-100'],
            attributes: ['type' => 'button', 'data-remote-button' => 'prev'],
            content: 'Previous'
        ));
        $grid->append($div);

        $div = new div(classes: ['col-6']);
        $div->append(new button(
            classes: ['btn', 'btn-primary', 'w-100'],
            attributes: ['type' => 'button', 'data-remote-button' => 'next'],
            content: 'Next'
        ));
        $grid->append($div);

        $div = new div(classes: ['col-6']);
        $div->append(new button(
            classes: ['btn', 'btn-success', 'w-100'],
            attributes: ['type' => 'button', 'data-remote-button' => 'start'],
            content: 'Start'
        ));
        $grid->append($div);

        $div = new div(classes: ['col-6']);
        $div->append(new button(
            classes: ['btn', 'btn-warning', 'w-100'],
            attributes: ['type' => 'button', 'data-remote-button' => 'restart'],
            content: 'Restart'
        ));
        $grid->append($div);

        $div = new div(classes: ['col-12']);
        $div->append(new button(
            classes: ['btn', 'btn-danger', 'w-100'],
            attributes: ['type' => 'button', 'data-remote-button' => 'pause'],
            content: 'Pause'
        ));
        $grid->append($div);

        $panelBody->append($grid);

        /* Message input */
        $msgWrap = new div(classes: ['mt-3']);
        $msgWrap->append(new div(classes: ['fw-bold', 'mb-2'], content: 'Message'));

        $msgRow = new div(classes: ['d-flex', 'gap-2']);
        $msgRow->append(new input(
            id: 'message_input',
            classes: ['form-control'],
            attributes: [
                'type' => 'text',
                'placeholder' => 'Type message to display'
            ]
        ));
        $msgRow->append(new button(
            id: 'btn-send-message',
            classes: ['btn', 'btn-outline-dark'],
            attributes: ['type' => 'button'],
            content: 'Send'
        ));

        $msgWrap->append($msgRow);
        $panelBody->append($msgWrap);

        $panel->append($panelBody);
        $panelWrap->append($panel);
        $stage->append($panelWrap);

        $this->append($stage);
        $this->append(new script('/scripts/remote.js'));
    }
}