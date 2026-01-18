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
        parent::__construct(tagType: TagType::table, classes: ['table', 'table-striped', 'table-hover', 'mt-2', 'mb-2']);

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
            if ($tableColumnName === 'id') {
                continue;
            }
            $tr->append(new th(content: c(ucfirst($tableColumnName))));
        }

        $tbody = new tbody();
        $this->append($tbody);
        foreach ($tableRows as $tableRow) {
            $tr = new tr();
            if (count($rowActions)) {
                $td = new td();
                foreach ($rowActions as $rowAction) {
                    $modelName = substr($tableRow::class, strrpos($tableRow::class, '\\') + 1);

                    $button = new button(
                        classes: [
                            'btn',
                            'btn-secondary'
                        ],
                        attributes: [
                            'style' => 'font-size: 1.3rem; padding: 0.25rem;',
                            'title' => $rowAction->label,
                            'data-app-row-action' => $rowAction->action(),
                            'data-app-model' => $modelName,
                            'data-app-id' => $tableRow?->id,
                        ],
                        content: $rowAction->icon
                    );
                    $button->cleanOutput(false); // so the icon can render as HTML
                    $td->append($button);
                }
                $tr->append($td);
            }
            foreach ($tableRow as $key => $tableCell) {
                if ($key === 'id') {
                    continue;
                }
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
