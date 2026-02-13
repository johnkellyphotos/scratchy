<?php

namespace view\DatabaseController;

use core\Database\DatabaseColumn;
use core\Database\Model;
use core\Database\Schema;
use ReflectionClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Scratchy\component\PageContent;
use Scratchy\elements\h1;
use Scratchy\elements\h3;
use Scratchy\elements\li;
use Scratchy\elements\p;
use Scratchy\elements\ul;

class TablesView extends PageContent
{
    public function __construct()
    {
        parent::__construct();

        $this->append(new h1(content: 'Database tables', classes: ['primary-color']));
        $this->append(new p(content: 'Schema is derived from your models. Tables below are what the code expects.'));

        $models = $this->getModels();
        if (count($models) === 0) {
            $this->append(new p(content: 'No models found.'));
            return;
        }

        foreach ($models as $model) {
            $tableName = $model::getTableName();
            $this->append(new h3(content: $tableName, classes: ['mt-4']));

            $columns = Schema::getDefinition($model);
            $ul = new ul();

            /** @var DatabaseColumn $column */
            foreach ($columns as $column) {
                $parts = [
                    $column->name,
                    $column->type->value,
                ];

                if ($column->isPrimaryKey) {
                    $parts[] = 'primary key';
                }
                if ($column->autoIncrement) {
                    $parts[] = 'auto increment';
                }
                if ($column->unique) {
                    $parts[] = 'unique';
                }
                if ($column->nullable) {
                    $parts[] = 'nullable';
                }
                if ($column->default !== null) {
                    $parts[] = 'default: ' . $column->default;
                }
                if ($column->isLabel) {
                    $parts[] = 'label';
                }

                $ul->append(new li(content: implode(' • ', $parts)));
            }

            $this->append($ul);
        }
    }

    /**
     * @return class-string<Model>[]
     */
    private function getModels(): array
    {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(MODEL_DIRECTORY)
        );

        foreach ($it as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            require_once $file->getPathname();
        }

        $models = [];
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, Model::class)) {
                $ref = new ReflectionClass($class);
                if (!$ref->isAbstract()) {
                    $models[] = $class;
                }
            }
        }

        return $models;
    }
}
