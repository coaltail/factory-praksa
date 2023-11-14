<?php

namespace App\Connection;

use PDO;
use PDOStatement;
use PDOException;
use RuntimeException;

class Connection
{
    protected static ?PDO $instance = null;
    private static ?Connection $connection = null;

    protected function __construct()
    {
        // Private constructor to prevent instantiation
    }

    public static function getInstance(): Connection
    {
        if (empty(self::$instance)) {
            $db_info = array(
                "db_host" => $_ENV['MYSQL_HOST'],
                "db_user" => $_ENV['MYSQL_USER'],
                "db_pass" => $_ENV['MYSQL_PASSWORD'],
                "db_name" => $_ENV['MYSQL_DATABASE'],
                "db_port" => '3306',
                "db_charset" => "UTF-8"
            );

            try {
                self::$instance = new PDO(
                    "mysql:host=" . $db_info['db_host'] . ';port=' . $db_info['db_port'] . ';dbname=' . $db_info['db_name'],
                    $db_info['db_user'],
                    $db_info['db_pass']
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->query('SET NAMES utf8');
                self::$instance->query('SET CHARACTER SET utf8');
            } catch (PDOException $error) {
                // Log the error or throw an exception
                throw new RuntimeException('Database connection error: ' . $error->getMessage());
            }

            self::$connection = new self();
        }

        return self::$connection;
    }

    public function select(string $query, array $values = []): PDOStatement
    {
        $selectQuery = self::$instance->prepare($query);
        if (!empty($values) && $this->is_assoc_array($values)) {
            foreach ($values as $key => $val) {
                $selectQuery->bindParam(":$key", $val);
            }
        } else {

            foreach ($values as $index => $val) {
                $selectQuery->bindParam($index + 1, $val);
            }
        }


        return $selectQuery;
    }

    public function fetchAssoc(PDOStatement $statement)
    {
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAssocAll(PDOStatement $statement)
    {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $tableName, array $data): ?int
    {
        if (empty($data)) {
            return null; // Indicate failure if data is empty
        }

        try {
            // Construct the SQL query
            $columns = implode(', ', array_keys($data));
            $values = ':' . implode(', :', array_keys($data));
            $query = "INSERT INTO {$tableName} ({$columns}) VALUES ({$values})";

            // Prepare and execute the query
            $statement = self::$instance->prepare($query);

            foreach ($data as $key => $value) {
                $statement->bindValue(':' . $key, $value);
            }

            $result = $statement->execute();

            if ($result) {
                // Return the last inserted ID
                return self::$instance->lastInsertId();
            }

            return null; // Return null if the insert was unsuccessful

        } catch (PDOException $e) {
            // Log the error or handle it as needed
            throw new RuntimeException('Error during database insertion: ' . $e->getMessage());
        }
    }

    private function batchInsert(string $tableName, array $data): void
    {
        if (empty($data)) {
            // Handle empty data array, if needed
            return;
        }

        $columns = implode(', ', array_keys($data[0]));
        $values = [];

        foreach ($data as $row) {
            $placeholders = ':' . implode(', :', array_keys($row));
            $values[] = "({$placeholders})";
        }

        $query = "INSERT INTO {$tableName} ({$columns}) VALUES " . implode(', ', $values);
        $statement = self::$instance->prepare($query);

        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $statement->bindValue(':' . $key, $value);
            }
            $statement->execute();
        }
    }

    private function is_assoc_array(array $values): bool
    {
        return count(array_filter(array_keys($values), 'is_string')) > 0;
    }


}