<?php

namespace controller;

use core\Controller;
use model\PowerPointControlsModel;
use Scratchy\component\Json;
use Scratchy\elements\Element;
use Throwable;
use view\PowerpointRemoteController\IndexView;

/** @noinspection PhpUnused */
class
ApiController extends Controller
{
    public function __construct()
    {
        $this->webPageTemplate = Json::class;
        parent::__construct();
    }

    /**
     * @throws Throwable
     */
    /** @noinspection PhpUnused */
    public function remote(): array
    {
        $value = $this->data->post('button');

        $field = [
            'prev' => 'previous',
            'next' => 'next',
            'start' => 'start',
            'restart' => 'restart',
            'pause' => 'pause',
        ][$value] ?? null;

        if (empty($field)) {
            $message = $this->data->post('message');
            if (!empty($message)) {
                $PowerPointController = PowerPointControlsModel::findOne();
                $value = substr($message, 0, 255);
                $PowerPointController->message = $value;
                if ($PowerPointController->save()) {
                    return [
                        'success' => true,
                        'button' => $value,
                    ];
                }
            }
        } else {
            $PowerPointController = PowerPointControlsModel::findOne();
            if ($PowerPointController) {
                $PowerPointController->{$field} = true;
                if ($PowerPointController->save()) {
                    return [
                        'success' => true,
                        'button' => $value,
                    ];
                }
            }
        }

        return [
            'success' => false,
            'message' => 'Unable to register button press.',
        ];
    }

    /** @noinspection PhpUnused */
    public function getControls(): array
    {
        $PowerPointController = PowerPointControlsModel::findOne();
        $data = (array)$PowerPointController;

        if (
            $PowerPointController->next ||
            $PowerPointController->previous ||
            $PowerPointController->start ||
            $PowerPointController->restart ||
            $PowerPointController->pause ||
            $PowerPointController->message
        ) {
            $PowerPointController->next = false;
            $PowerPointController->previous = false;
            $PowerPointController->start = false;
            $PowerPointController->restart = false;
            $PowerPointController->pause = false;
            $PowerPointController->message = '';
            $PowerPointController->save();
        }

        return $data;
    }
}