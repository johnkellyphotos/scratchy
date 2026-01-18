<?php

namespace controller;

use core\Controller;
use Exception;
use model\_Model;
use Scratchy\component\Modal\Modal;
use Scratchy\component\Modal\ModalButtonType;
use Scratchy\component\Modal\ModalContent;
use Scratchy\component\ViewRecord;
use Scratchy\elements\span;
use Throwable;

/** @noinspection PhpUnused */
class ModalController extends Controller
{
    public function __construct()
    {
        $this->webPageTemplate = Modal::class;
        parent::__construct();
    }

    /** @noinspection PhpUnused */
    public function editRecord(): ModalContent
    {
        try {
            $modelName = $this->data->get('model');
            $id = $this->data->get('id');

            $model = _Model::get($modelName);
            $record = $model::fromId($id);
            if ($record === null) {
                throw new Exception('Unable to find the referenced record');
            }

            $modelDisplayName = _Model::displayNameFromModelName($model::class);
            $title = "Edit $modelDisplayName record";
            $content = "Edit the record for <b>{$record->label()}</b>.";
        } catch (Throwable) {
            $title = 'You are unable to edit this record';
            $content = 'Either the record does not exist or you do not have permission to edit.';
        }
        return new ModalContent(title: $title, modalButtons: [ModalButtonType::CANCEL], elementList: new span(content: $content));
    }

    public function viewRecord(): ModalContent
    {
        try {
            $modelName = $this->data->get('model');
            $id = $this->data->get('id');
            $modalButtonAction = $this->data->get('modalButtonAction');

            $model = _Model::get($modelName);
            $record = $model::fromId($id);
            if ($record === null) {
                throw new Exception('Unable to find the referenced record');
            }

            $modelDisplayName = _Model::displayNameFromModelName($model::class);

            $title = "View $modelDisplayName record for {$record->label()}";
            $content = new ViewRecord($record);
            $modalButtonList = [ModalButtonType::OKAY];
        } catch (Throwable) {
            $title = 'You are unable to delete this record';
            $content = new span(content: 'Either the record does not exist or you do not have permission to delete.');
            $modalButtonList = [ModalButtonType::OKAY];
        }
        return new ModalContent(title: $title, modalButtons: $modalButtonList, elementList: $content);
    }

    /** @noinspection PhpUnused */
    public function deleteRecord(): ModalContent
    {
        try {
            $modelName = $this->data->get('model');
            $id = $this->data->get('id');
            $modalButtonAction = $this->data->get('modalButtonAction');

            $model = _Model::get($modelName);
            $record = $model::fromId($id);
            if ($record === null) {
                throw new Exception('Unable to find the referenced record');
            }

            $modelDisplayName = _Model::displayNameFromModelName($model::class);

            switch ($modalButtonAction) {
                case ModalButtonType::YES->value:
                    $modalButtonList = [ModalButtonType::OKAY_AND_RELOAD];
                    $success = $record->remove();
                    if ($success) {
                        $title = "The $modelDisplayName record for {$record->label()} was deleted.";
                        $content = "You may now close the modal.";
                    } else {
                        $title = "The $modelDisplayName record for {$record->label()} could not be deleted.";
                        $content = "Refresh the page and try again.";
                    }
                    break;
                default:
                    $title = "Delete $modelDisplayName record";
                    $content = "Are you sure you want to delete the record for <b>{$record->label()}</b>?";
                    $modalButtonList = [ModalButtonType::NO, ModalButtonType::YES];
                    break;
            }
        } catch (Throwable) {
            $title = 'You are unable to delete this record';
            $content = 'Either the record does not exist or you do not have permission to delete.';
            $modalButtonList = [ModalButtonType::OKAY];
        }
        return new ModalContent(title: $title, modalButtons: $modalButtonList, elementList: new span(content: $content));
    }
}