<?php

namespace App\Database;

interface Database
{
    public function save(array $data, string $table): void;

    public function update(array $data, string $table): void;

    public function findByField(string $field, string $value, string $table): array;
}
