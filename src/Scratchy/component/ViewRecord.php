<?php

namespace Scratchy\component;

use core\Database\Model;
use Scratchy\elements\div;
use Scratchy\elements\Element;
use Scratchy\elements\tbody;
use Scratchy\elements\td;
use Scratchy\elements\th;
use Scratchy\elements\tr;
use Scratchy\TagType;

class ViewRecord extends Element
{
    public function __construct(Model $record)
    {
        parent::__construct(tagType: TagType::div, classes: ['div']);

        $cardBody = new div(classes: ['card-body', 'p-2']);
        $this->append($cardBody);

        $table = new Element(tagType: TagType::table, classes: ['table', 'table-sm', 'table-borderless', 'mb-0']);
        $cardBody->append($table);

        $tbody = new tbody();
        $table->append($tbody);

        foreach (get_object_vars($record) as $key => $value) {
            $row = new tr();

            $row->append(new th(
                classes: ['text-muted'],
                attributes: ['style' => 'width: 30%; white-space: nowrap;'],
                content: c((string)$key)
            ));

            $display = $value;
            if (is_bool($value)) {
                $display = $value ? 'true' : 'false';
            } elseif (is_array($value) || is_object($value)) {
                $display = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif ($value === null) {
                $display = '';
            }

            $row->append(new td(content: c((string)$display)));

            $tbody->append($row);
        }
    }
}
