<?php

namespace Scratchy\component;

use Scratchy\elements\Element;
use Scratchy\elements\tbody;
use Scratchy\elements\td;
use Scratchy\elements\th;
use Scratchy\elements\thead;
use Scratchy\elements\tr;
use Scratchy\TagType;

class SmartTable extends Element
{
    public function __construct($tableRows)
    {
        parent::__construct(tagType: TagType::table, classes: ['table', 'table-striped', 'table-hover']);

        $firstEntry = $tableRows[0] ?? null;
        if (!$firstEntry) {
            return;
        }
        $firstEntry = (array)$firstEntry;
        $tableColumnNames = array_keys($firstEntry);

        $tableHeader = new thead(classes: ['table-dark']);
        $this->append($tableHeader);
        $tr = new tr();
        $tableHeader->append($tr);
        foreach ($tableColumnNames as $tableColumnName) {
            $tr->append(new th(content: __($tableColumnName)));
        }

        $tbody = new tbody();
        $this->append($tbody);
        foreach ($tableRows as $tableRow) {
            $tr = new tr();
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
