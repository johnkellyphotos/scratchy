<?php

namespace controller;

use core\Controller;
use core\Database\Model;
use Exception;
use model\_Model;
use Scratchy\component\EditRecord;
use Scratchy\component\Modal\Modal;
use Scratchy\component\Modal\ModalButtonType;
use Scratchy\component\Modal\ModalContent;
use Scratchy\component\ViewRecord;
use Scratchy\elements\span;
use Scratchy\InputType;
use Throwable;

/** @noinspection PhpUnused */
class ModalController extends Controller
{
    private ?string $modelName;
    private ?int $id;
    private ?string $modalButtonAction;
    private Model $model;
    private ?Model $record;
    private string $modelDisplayName;

    public function __construct()
    {
        $this->webPageTemplate = Modal::class;
        parent::__construct();

        $this->modelName = $this->data->get('model');
        $this->id = $this->data->get('id');
        $this->modalButtonAction = $this->data->post('modalButtonAction');
        $this->model = _Model::get($this->modelName);
        $this->record = $this->model::fromId($this->id);
        $this->modelDisplayName = _Model::displayNameFromModelName($this->model::class);
    }

    /** @noinspection PhpUnused */
    public function editRecord(): ModalContent
    {
        try {
            if ($this->record === null) {
                throw new Exception('Unable to find the referenced record');
            }

            switch ($this->modalButtonAction) {
                case ModalButtonType::SAVE->value:
                    $modalButtonList = [ModalButtonType::OKAY_AND_RELOAD];
                    $fieldsToSave = $this->data->post('modalInputData');

                    foreach ($fieldsToSave as $fieldNameToSave => $fieldValueToSave) {
                        if ($fieldNameToSave === 'id') {
                            continue;
                        }
                        $inputForField = $this->record::getInputTypeForColumn($fieldNameToSave);
                        if ($inputForField === InputType::none) {
                            throw new Exception('Attempting to edit non-editable field.');
                        }
                        $this->record->{$fieldNameToSave} = $fieldValueToSave ?: null;
                    }

                    $success = $this->record->save();
                    if ($success) {
                        $title = "The $this->modelDisplayName record for {$this->record->label()} was successfully updated.";
                        $content = new span(content: "You may now close the modal.");
                    } else {
                        $title = "The $this->modelDisplayName record for {$this->record->label()} could not be updated.";
                        $content = new span(content: "Refresh the page and try again.");
                    }
                    break;
                default:
                    $title = "Edit $this->modelDisplayName record for {$this->record->label()}";
                    $content = new EditRecord($this->record);
                    $modalButtonList = [ModalButtonType::CANCEL, ModalButtonType::SAVE];
                    break;
            }
        } catch (Throwable) {
            $title = 'You are unable to edit this record';
            $content = new span(content: 'Either the record does not exist or you do not have permission to edit.');
            $modalButtonList = [ModalButtonType::CANCEL];
        }
        return new ModalContent(title: $title, modalButtons: $modalButtonList, elementList: $content);
    }

    /** @noinspection PhpUnused */
    public function viewRecord(): ModalContent
    {
        try {
            if ($this->record === null) {
                throw new Exception('Unable to find the referenced record');
            }
            $title = "View $this->modelDisplayName record for {$this->record->label()}";
            $content = new ViewRecord($this->record);
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
            if ($this->record === null) {
                throw new Exception('Unable to find the referenced record');
            }
            switch ($this->modalButtonAction) {
                case ModalButtonType::YES->value:
                    $modalButtonList = [ModalButtonType::OKAY_AND_RELOAD];
                    $success = $this->record->remove();
                    if ($success) {
                        $title = "The $this->modelDisplayName record for {$this->record->label()} was deleted.";
                        $content = "You may now close the modal.";
                    } else {
                        $title = "The $this->modelDisplayName record for {$this->record->label()} could not be deleted.";
                        $content = "Refresh the page and try again.";
                    }
                    break;
                default:
                    $title = "Delete $this->modelDisplayName record";
                    $content = "Are you sure you want to delete the record for <b>{$this->record->label()}</b>?";
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