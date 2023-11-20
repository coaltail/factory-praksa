<?php

namespace App\Models;

use App\Connection\Connection;
use PDO;
use ReflectionClass;

class Model
{
    protected static ?string $tableName = null;
    protected static ?string $primaryKey = null;
    protected static ?Connection $connection = null;


    public function __construct(private array $attrs = [], private $primaryKeyValue = null)
    {

        self::init();
    }

    public static function init(): void
    {
        if (self::$tableName === null) {
            self::setTableName();
        }

        if (self::$primaryKey === null) {
            self::fetchPrimaryKey();
        }

        if (self::$connection === null) {
            self::$connection = Connection::getInstance();
        }
    }

    private static function setTableName(): void
    {
        if (self::$tableName === null) {
            $reflection = new ReflectionClass(__CLASS__);
            $className = $reflection->getShortName();
            self::$tableName = strtolower($className . 's');
        }
    }

    private static function fetchPrimaryKey(): void
    {
        $result = self::$connection->fetchAssoc(
            self::$connection->select("SHOW KEYS FROM " . self::$tableName . " WHERE key_name = 'PRIMARY'")
        );

        self::$primaryKey = $result['Column_name'] ?? 'id';
    }

    public function __set($name, $value)
    {
        $this->attrs[$name] = $value;
    }

    public function __get($name)
    {
        return $this->attrs[$name] ?? null;
    }

    public function save(): void
    {
        self::$primaryKey = self::$connection->insert(self::$tableName, $this->attrs);
    }

    public function update(): void
    {
        if (!isset(self::$primaryKey)) {
            return;
        }

        $condition = [self::$primaryKey => $this->primaryKeyValue];

        self::$connection->update(self::$tableName, $this->attrs, $condition);
    }

    public static function find($primaryKey): ?static
    {
        self::init();
        $query = self::$connection->select("SELECT * FROM " . self::$tableName . " WHERE " . self::$primaryKey . " = ?", [$primaryKey]);
        $attrs = self::$connection->fetchAssoc($query);
        if ($attrs === false) {
            return null;
        }
        unset($attrs[self::$primaryKey]);
        return new static($attrs, $primaryKey);
    }

    public function toArray(): array
    {
        return $this->attrs;
    }
}
