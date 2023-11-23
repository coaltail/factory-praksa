<?php

namespace App\Traits;

trait HasDeletes {
    protected static string $deletedAtColumn = 'deleted_at';

    public function delete(): void
    {
        // Implement this method to delete the object from the database
        self::$connection->delete($this->table,  [[self::$primaryKeyName, "=", $this->primaryKeyValue]]);
    }

    public function softDelete(): void
    {
        $deletedAtExists = self::$connection->checkColumn(self::$tableName, self::$deletedAtColumn);

        if (!$deletedAtExists) {
            return;
        }

        $currentValue = self::$connection->select(
            "SELECT " . self::$deletedAtColumn . " FROM " . self::$tableName .
            " WHERE " . self::$primaryKeyName . " = ?",
            [$this->primaryKeyValue]
        )->fetchAssoc();

        if ($currentValue[self::$deletedAtColumn]) {
            return;
        }

        self::$connection->update(
            self::$tableName,
            [self::$deletedAtColumn => $this->getCurrentTimestamp()],
            [[self::$primaryKeyName, '=', $this->primaryKeyValue]]
        );
    }

}