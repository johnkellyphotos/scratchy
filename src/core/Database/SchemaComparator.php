<?php

declare(strict_types=1);

namespace core\Database;

final class SchemaComparator
{
    public bool $exists;

    /** @var string[] */
    public array $missingColumns = [];

    /** @var string[] */
    public array $extraColumns = [];

    /** @var array<string, array<string, mixed>> */
    public array $changedColumns = [];

    /** @var string[] */
    public array $missingIndexes = [];

    /** @var string[] */
    public array $missingForeignKeys = [];

    /** @var string[] */
    public array $changedForeignKeys = [];

    /** @var string[] */
    public array $sql = [];

    public function __construct(public readonly string $table)
    {
    }

    public function hasChanges(): bool
    {
        return !empty($this->sql)
            || !empty($this->exists)
            || !empty($this->missingColumns)
            || !empty($this->extraColumns)
            || !empty($this->changedColumns)
            || !empty($this->missingIndexes)
            || !empty($this->missingForeignKeys)
            || !empty($this->changedForeignKeys);
    }
}
