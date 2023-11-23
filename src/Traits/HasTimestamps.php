<?php

namespace App\Traits;

use DateTime;

trait HasTimestamps
{
    protected static string $createdAtColumn = 'created_at';
    protected static string $updatedAtColumn = 'updated_at';

    public static function bootHasTimestamps(): void
    {
        self::init();
        self::$connection->addColumn(self::$tableName, array("created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"));
    }

    protected function getCurrentTimestamp(): string
    {
        return (new DateTime())->format('Y-m-d H:i:s');
    }
}