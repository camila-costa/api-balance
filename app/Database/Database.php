<?php

namespace App\Database;

interface Database
{
    public function save(array $data, string $table): void;

    public function update(array $data, string $table): void;

    public function find(string $id, string $table): array;

    public function findByField(string $field, string $value, string $table): array;
}
