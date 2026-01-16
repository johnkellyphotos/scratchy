<?php

namespace Scratchy\component;

use core\RowAction;
use Scratchy\elements\button;
use Scratchy\elements\Element;
use Scratchy\elements\tbody;
use Scratchy\elements\td;
use Scratchy\elements\th;
use Scratchy\elements\thead;
use Scratchy\elements\tr;
use Scratchy\TagType;

class SmartTable extends Element
{
    public function __construct(array $tableRows, array $actions = [])
    {
        parent::__construct(tagType: TagType::table, classes: ['table', 'table-striped', 'table-hover']);

        $firstEntry = $tableRows[0] ?? null;
        if (!$firstEntry) {
            return;
        }
        $firstEntry = (array)$firstEntry;

        $rowActions = [];
        $tableColumnNames = array_keys($firstEntry);
        if (!empty($actions)) {

            foreach ($actions as $action) {
                $rowActions[] = new RowAction($action);
            }
            $tableColumnNames = ['Actions', ...$tableColumnNames];
        }

        $tableHeader = new thead(classes: ['table-dark']);
        $this->append($tableHeader);
        $tr = new tr();
        $tableHeader->append($tr);
        foreach ($tableColumnNames as $tableColumnName) {
            $tr->append(new th(content: c($tableColumnName)));
        }

        $tbody = new tbody();
        $this->append($tbody);
        foreach ($tableRows as $tableRow) {
            $tr = new tr();
            if (count($rowActions)) {
                $td = new td();
                foreach ($rowActions as $rowAction) {
                    $td->append(new button(classes: ['btn', 'btn-secondary'], attributes: ['style' => 'font-size: 1.3rem; padding: 0.25rem;', 'title' => $rowAction->label], content: $rowAction->icon));
                }
                $tr->append($td);
            }
            foreach ($tableRow as $tableCell) {
                $tr->append(new td(content: $tableCell));
            }
            $tbody->append($tr);
        }
    }

    public function output(): void
    {
        echo '<!DOCTYPE html>' . "\n" . $this->render();
    }
}
