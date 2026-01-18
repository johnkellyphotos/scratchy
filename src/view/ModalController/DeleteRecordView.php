<?php

namespace view\ModalController;

use core\Database\Model;
use Exception;
use Scratchy\component\Modal\ModalButtonType;
use Scratchy\component\Modal\ModalContent;
use Scratchy\elements\Element;
use Scratchy\elements\span;
use Scratchy\TagType;
use Throwable;

class DeleteRecordView extends Element
{
    public function __construct(
        ?Model  $record,
        string  $modelDisplayName,
        ?string $modalButtonAction = null,
    )
    {
        parent::__construct(TagType::div);
        try {
            if ($record === null) {
                throw new Exception('Unable to find the referenced record');
            }
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

        $this->append(new ModalContent(title: $title, modalButtons: $modalButtonList, elementList: new span(content: $content)));
    }
}