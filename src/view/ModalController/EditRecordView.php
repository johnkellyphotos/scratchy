<?php

namespace view\ModalController;

use core\Database\Model;
use Exception;
use Scratchy\component\EditRecord;
use Scratchy\component\Modal\ModalButtonType;
use Scratchy\component\Modal\ModalContent;
use Scratchy\elements\Element;
use Scratchy\elements\span;
use Scratchy\InputType;
use Scratchy\TagType;
use Throwable;

class EditRecordView extends Element
{
    public function __construct(
        ?Model  $record,
        string  $modelDisplayName,
        ?string $modalButtonAction = null,
        ?array  $fieldsToSave = [],
    )
    {
        parent::__construct(TagType::div);
        try {
            if ($record === null) {
                throw new Exception('Unable to find the referenced record');
            }

            switch ($modalButtonAction) {
                case ModalButtonType::SAVE->value:
                    $modalButtonList = [ModalButtonType::OKAY_AND_RELOAD];
                    $fieldsToSave ??= [];

                    foreach ($fieldsToSave as $fieldNameToSave => $fieldValueToSave) {
                        if ($fieldNameToSave === 'id') {
                            continue;
                        }
                        $inputForField = $record::getInputTypeForColumn($fieldNameToSave);
                        if ($inputForField === InputType::none) {
                            throw new Exception('Attempting to edit non-editable field.');
                        }
                        $record->{$fieldNameToSave} = $fieldValueToSave ?: null;
                    }
                    $success = $record->save();

                    if ($success) {
                        $title = "The $modelDisplayName record for {$record->label()} was successfully updated.";
                        $content = new span(content: "You may now close the modal.");
                    } else {
                        $title = "The $modelDisplayName record for {$record->label()} could not be updated.";
                        $content = new span(content: "Refresh the page and try again.");
                    }
                    break;
                default:
                    $title = "Edit $modelDisplayName record for {$record->label()}";
                    $content = new EditRecord($record);
                    $modalButtonList = [ModalButtonType::CANCEL, ModalButtonType::SAVE];
                    break;
            }
        } catch (Throwable) {
            $title = 'You are unable to edit this record';
            $content = new span(content: 'Either the record does not exist or you do not have permission to edit.');
            $modalButtonList = [ModalButtonType::CANCEL];
        }

        $this->append(new ModalContent(title: $title, modalButtons: $modalButtonList, elementList: $content));
    }
}