<?php

namespace App\Models\Contracts;

use Exception;
use Medoo\Medoo;

class MysqlBaseModel extends BaseModel
{
    public function __construct($id = null)
    {
        try {
            // $this->connection = new \PDO("mysql:dbname={$_ENV['DB_NAME']};host={$_ENV['DB_HOST']}", $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            // $this->connection->exec("set names utf8;");

            $this->connection = new Medoo([
                // [required]
                'type' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],

                // [optional]
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'port' => 3306,

                // [optional] Table prefix, all table names will be prefixed as PREFIX_table.
                'prefix' => '',

                // [optional] Enable logging, it is disabled by default for better performance.
                'logging' => true,

                // [optional]
                // Error mode
                // Error handling strategies when error is occurred.
                // PDO::ERRMODE_SILENT (default) | PDO::ERRMODE_WARNING | PDO::ERRMODE_EXCEPTION
                // Read more from https://www.php.net/manual/en/pdo.error-handling.php.
                'error' => \PDO::ERRMODE_EXCEPTION,


                // [optional] Medoo will execute those commands after connected to the database.
                'command' => [
                    'SET SQL_MODE=ANSI_QUOTES'
                ]
            ]);
        } catch (Exception $e) {
            echo "Connection Failed: " . $e->getMessage();
        }

        if (!is_null($id)) {
            return $this->find($id);
        }
    }

    public function remove(): int
    {
        $recordId = $this->{$this->primaryKey};
        return $this->delete([$this->primaryKey => $recordId]);
    }

    public function save(): object
    {
        $recordId = $this->{$this->primaryKey};
        $this->update($this->attributes, [$this->primaryKey => $recordId]);
        return $this->find($recordId);
    }

    public function create(array $data): int
    {
        $this->connection->insert($this->table, $data);
        return $this->connection->id();
    }

    public function find($id): object
    {
        $record = $this->connection->get($this->table, '*', [$this->primaryKey => $id]);

        if (is_null($record))
            return new \stdClass;

        foreach ($record as $col => $val)
            $this->attributes[$col] = $val;
        return $this;
    }

    public function getAll(): array
    {
        return $this->connection->select($this->table, '*');
    }

    public function get(array $columns, array $where): array
    {
        return $this->connection->select($this->table, $columns, $where);
    }

    public function update(array $data, array $where): int
    {
        $result = $this->connection->update($this->table, $data, $where);
        return $result->rowCount();
    }

    public function delete(array $where): int
    {
        $result = $this->connection->delete($this->table, $where);
        return $result->rowCount();
    }

    public function count(array $where): int
    {
        return $this->connection->count($this->table, $where);
    }

    public function sum($column, array $where): int
    {
        return $this->connection->sum($this->table, $column, $where);
    }

}
