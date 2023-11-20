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

    private ?PDOStatement $query = null;
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
        $this->query = self::$instance->prepare($query);
        if (!empty($values) && $this->is_assoc_array($values)) {
            foreach ($values as $key => $val) {
                $this->query->bindParam(":$key", $val);
            }
        } else {

            foreach ($values as $index => $val) {
                $this->query->bindParam($index + 1, $val);
            }
        }

        $this->query->execute();


        return $this->query;
    }

    public function fetchAssoc()
    {
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAssocAll(): bool|array
    {
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
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
            $query = "INSERT INTO $tableName ($columns) VALUES ($values)";

            // Prepare and execute the query
            $this->query = self::$instance->prepare($query);

            foreach ($data as $key => $value) {
                $this->query->bindValue(':' . $key, $value);
            }

            $result = $this->query->execute();

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
            $values[] = "($placeholders)";
        }

        $query = "INSERT INTO $tableName ($columns) VALUES " . implode(', ', $values);
        $this->query = self::$instance->prepare($query);

        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $this->query->bindValue(':' . $key, $value);
            }
            $this->query->execute();
        }
    }

    public function update(string $tableName, array $values, array $condition): void
    {

        $setValues = array_map(function ($key) {
            return $key . " = :" . $key;
        }, array_keys($values));
        $setClause = implode(", ", $setValues);


        $whereValues = array_map(function ($key) {
            return $key . " = :" . $key;
        }, array_keys($condition));
        $whereClause = implode(" AND ", $whereValues);

        $query = "UPDATE " . $tableName . " SET " . $setClause . " WHERE " . $whereClause;

        $this->query = self::$instance->prepare($query);

        foreach ($values as $key => $value) {
            $this->query->bindValue(":" . $key, $value);
        }

        foreach ($condition as $key => $value) {
            $this->query->bindValue(":" . $key, $value);
        }

        // Execute the query
        $this->query->execute();


    }

    private function is_assoc_array(array $values): bool
    {
        return count(array_filter(array_keys($values), 'is_string')) > 0;
    }


}