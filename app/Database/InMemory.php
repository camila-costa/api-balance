<?php

namespace App\Database;

use App\Database\Database;

class InMemory implements Database
{
    private static InMemory $instance;
    private static array $data = [];

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): InMemory
    {
        if (!isset(self::$instance)) {
            self::$instance = new InMemory();
        }

        return self::$instance;
    }

    public function save(array $data, string $table): void
    {
        self::$data[$table][] = $data;
    }

    public function update(array $data, string $table): void
    {
        $id = $data['id'];
        unset($data['id']);
        self::$data[$table][$id] = $data;
    }

    public function find(string $id, string $table): array
    {
        return self::$data[$table][$id] ?? [];
    }

    public function findByField(string $field, string $value, string $table): array
    {
        $result = [];

        if (!isset(self::$data[$table])) {
            return $result;
        }

        foreach (self::$data[$table] as $data) {
            if ($data[$field] == $value) {
                array_push($result, $data);
            }
        }

        return $result;
    }

    /* public function clear(): void
    {
        self::$data = [];
    } */
}
