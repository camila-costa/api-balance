<?php

namespace App\Database;

use App\Database\Database;

class InMemory implements Database
{
    private static InMemory $instance;

    public static function getInstance(): InMemory
    {
        if (!isset(self::$instance)) {
            session_start();
            self::$instance = new InMemory();
        }

        return self::$instance;
    }

    public function save(array $data, string $table): void
    {
        if(!isset($_SESSION[$table]) || !in_array($data, $_SESSION[$table]))
            $_SESSION[$table][] = $data;
    }

    public function update(array $data, string $table): void
    {
        foreach ($_SESSION[$table] as $key => $row) {
            if ($row['id'] == $data['id']) {
                $_SESSION[$table][$key] = $data;
            }
        }
    }

    public function findByField(string $field, string $value, string $table): array
    {
        $result = [];

        if (!isset($_SESSION[$table])) {
            return $result;
        }

        foreach ($_SESSION[$table] as $data) {
            if ($data[$field] == $value) {
                array_push($result, $data);
            }
        }

        return $result;
    }
}
