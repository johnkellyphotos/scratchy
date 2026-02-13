<?php

namespace view\DatabaseController;

use core\Database\Schema;
use core\Database\SchemaComparator;
use Scratchy\component\PageContent;
use Scratchy\elements\a;
use Scratchy\elements\button;
use Scratchy\elements\div;
use Scratchy\elements\form;
use Scratchy\elements\h1;
use Scratchy\elements\h2;
use Scratchy\elements\input;
use Scratchy\elements\li;
use Scratchy\elements\p;
use Scratchy\elements\ul;
use Throwable;

class IndexView extends PageContent
{
    /**
     * @throws Throwable
     */
    public function __construct(?bool $executeBuild = false)
    {
        parent::__construct();
        $executeBuild ??= false;

        $this->appendHeader();

        if ($executeBuild == 1) {
            $this->renderBuildComplete();
            return;
        }

        $buildButton = $this->renderSchemaDifferences();
        if ($buildButton) {
            $this->appendBuildButton();
        }
    }

    private function appendHeader(): void
    {
        $this->append(new h1(content: 'Database update review', classes: ['primary-color']));
        $this->append(new h2(content: 'Review schema changes before rebuilding'));
        $this->append(new p(content: 'This page compares your current database schema with the codebase models. As your models change, the differences will appear here so you can review them before applying updates.'));
    }

    private function renderBuildComplete(): void
    {
        $this->append(new div(content: 'Update complete. The database has been rebuilt.'));
        $this->append(new a(content: 'Reload', attributes: ['href' => '/Database/']));
        Schema::buildDatabase();
    }

    private function renderSchemaDifferences(): bool
    {
        $schemaDifferences = Schema::getDifference();
        if (!count($schemaDifferences)) {
            $this->append(new div(content: 'Everything is in sync. No changes required.'));
            return false;
        }

        $this->append(new p(content: 'Below is a detailed change list by table. You can review each item and then rebuild the database to apply the updates.'));

        $list = new ul(id: 'top-level-list-container');
        $hasChanges = false;

        /* @var SchemaComparator $schemaDifference */
        foreach ($schemaDifferences as $schemaDifference) {
            if (!$schemaDifference->hasChanges()) {
                continue;
            }
            $hasChanges = true;
            $list->append($this->renderTableDifference($schemaDifference));
        }

        $wrap = new div();
        $wrap->append($list);
        $this->append($wrap);

        return $hasChanges;
    }

    private function renderTableDifference(SchemaComparator $schemaDifference): li
    {
        $changes = "Database table `<b>$schemaDifference->table</b>` has differences from codebase.";
        $li = new li(content: $changes);
        $li->cleanOutput(false);

        $innerUl = new ul();
        $li->append($innerUl);

        $changeFieldNames = [
            'missingColumns' => 'present in the codebase but missing from the database.',
            'extraColumns' => 'present in the database but missing from the codebase.',
            'changedColumns' => 'different in the codebase from the column definition in the database.',
            'missingIndexes' => 'have different indexes in the codebase and database.',
            'missingForeignKeys' => 'missing foreign key constraints in the database.',
            'changedForeignKeys' => 'foreign key constraints differ from the codebase.'
        ];

        foreach ($changeFieldNames as $changeFieldName => $message) {
            if (!$schemaDifference->{$changeFieldName}) {
                continue;
            }

            if ($changeFieldName === 'changedColumns') {
                $this->appendChangedColumns($innerUl, $schemaDifference->changedColumns);
                continue;
            }

            $this->appendColumnListMessage($innerUl, $schemaDifference->{$changeFieldName}, $message);
        }

        $innerUl->append($this->renderSqlList($schemaDifference->sql));

        return $li;
    }

    private function appendChangedColumns(ul $innerUl, array $changedColumns): void
    {
        foreach ($changedColumns as $columnName => $diffs) {
            $parts = [];
            foreach ($diffs as $diffKey => $diff) {
                $wantText = $this->formatDiffValue($diff['want'] ?? null);
                $haveText = $this->formatDiffValue($diff['have'] ?? null);
                $parts[] = "<b>$diffKey</b> (code: <code>$wantText</code>, db: <code>$haveText</code>)";
            }
            $details = implode('; ', $parts);
            $innerLi = new li(content: "Column `<b>$columnName</b>` differs: $details.");
            $innerLi->cleanOutput(false);
            $innerUl->append($innerLi);
        }
    }

    private function appendColumnListMessage(ul $innerUl, array $columns, string $message): void
    {
        $columnList = '';
        $number = count($columns);
        $s = $number != 1 ? 's' : '';
        $isAre = $number != 1 ? 'are' : 'is';
        foreach ($columns as $index => $difference) {
            $columnList .= "`<b>$difference</b>`";
            if ($index < ($number - 1)) {
                $columnList .= ', ';
            }
        }
        $innerLi = new li(content: "The column$s $columnList $isAre $message");
        $innerLi->cleanOutput(false);
        $innerUl->append($innerLi);
    }

    private function renderSqlList(array $sqlList): li
    {
        $subLi = new li(content: 'The following SQL may be run to resolve this:');
        $subUl = new ul();
        $subLi->append($subUl);

        foreach ($sqlList as $sql) {
            $codeLi = new li(content: "<code>$sql</code>.");
            $codeLi->cleanOutput(false);
            $subUl->append($codeLi);
        }

        return $subLi;
    }

    private function appendBuildButton(): void
    {
        $form = new form(attributes: ['action' => '/Database/', 'method' => 'POST']);
        $form->append(new input(name: 'execute_build', value: 1, attributes: ['type' => 'hidden',]));
        $form->append(new button(classes: ['btn', 'btn-primary'], content: 'Rebuild database'));
        $this->append($form);
    }

    private function formatDiffValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if ($value === null) {
            return 'null';
        }
        return (string)$value;
    }
}
