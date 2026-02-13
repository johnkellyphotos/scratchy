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
use Scratchy\elements\input;
use Scratchy\elements\li;
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

        $this->append(
            new h1(content: 'Configure database', classes: ['primary-color']),
        );

        $buildButton = null;

        if ($executeBuild != 1) {
            $schemaDifferences = Schema::getDifference();
            if (count($schemaDifferences)) {
                $ul = new ul(id: 'top-level-list-container');
                /* @var SchemaComparator $schemaDifference */
                foreach ($schemaDifferences as $schemaDifference) {
                    if ($schemaDifference->hasChanges()) {
                        $buildButton = true;
                        $changes = "Database table `<b>$schemaDifference->table</b>` has differences from codebase.";
                        $li = new li(content: $changes);
                        $li->cleanOutput(false);
                        $ul->append($li);
                        $innerUl = new ul();
                        $li->append($innerUl);

                        $changeFieldNames = [
                            'missingColumns' => 'present in the codebase but missing from the database.',
                            'extraColumns' => 'present in the database but missing from the codebase.',
                            'changedColumns' => 'different in the codebase from the column definition in the database.',
                            'missingIndexes' => 'have different indexes in the codebase and database.'
                        ];

                        foreach ($changeFieldNames as $changeFieldName => $message) {
                            if ($schemaDifference->{$changeFieldName}) {
                                $columns = "";
                                $number = count($schemaDifference->{$changeFieldName});
                                $s = $number != 1 ? 's' : '';
                                $isAre = $number != 1 ? 'are' : 'is';
                                foreach ($schemaDifference->{$changeFieldName} as $index => $difference) {
                                    $columns .= "`<b>$difference</b>`";
                                    if ($index < ($number - 1)) {
                                        $columns .= ', ';
                                    }
                                }
                                $innerLi = new li(content: "The column$s $columns $isAre $message");
                                $innerLi->cleanOutput(false);
                                $innerUl->append($innerLi);

                            }
                        }
                        $subLi = new li(content: "The following SQL may be run to resolve this:");
                        $innerUl->append($subLi);
                        $subUl = new ul();
                        $subLi->append($subUl);
                        foreach ($schemaDifference->sql as $sql) {
                            $codeLi = new li(content: "<code>$sql</code>.");
                            $codeLi->cleanOutput(false);
                            $subUl->append($codeLi);
                        }
                    }
                }
                $div = new div();
                $div->append($ul);
                $this->append($div);
            } else {
                $this->append(new div(content: 'Database schema matches model schema. No changes required.'));
            }

        } else {
            $this->append(new div(content: 'The database has been rebuilt.'));
            $this->append(new a(content: 'Reload', attributes: ['href' => '/Database/']));
            Schema::buildDatabase();
        }

        if ($buildButton) {
            $form = new form(attributes: ['action' => '/Database/', 'method' => 'POST']);
            $form->append(new input(name: 'execute_build', value: 1, attributes: ['type' => 'hidden',]));
            $form->append(new button(classes: ['btn', 'btn-primary'], content: 'Rebuild database'));
            $this->append($form);
        }
    }
}
