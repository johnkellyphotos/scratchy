<?php

namespace view\ModalController;

use core\Database\Model;
use Exception;
use Scratchy\component\Modal\ModalButtonType;
use Scratchy\component\Modal\ModalContent;
use Scratchy\component\ViewRecord;
use Scratchy\elements\Element;
use Scratchy\elements\span;
use Scratchy\TagType;
use Throwable;

class ViewRecordView extends Element
{
    public function __construct(
        ?Model  $record,
        string  $modelDisplayName,
    )
    {
        parent::__construct(TagType::div);
        try {
            if ($record === null) {
                throw new Exception('Unable to find the referenced record');
            }
            $title = "View $modelDisplayName record for {$record->label()}";
            $content = new ViewRecord($record);
            $modalButtonList = [ModalButtonType::OKAY];
        } catch (Throwable) {
            $title = 'You are unable to delete this record';
            $content = new span(content: 'Either the record does not exist or you do not have permission to delete.');
            $modalButtonList = [ModalButtonType::OKAY];
        }

        $this->append(new ModalContent(title: $title, modalButtons: $modalButtonList, elementList: $content));
    }
}