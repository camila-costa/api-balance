<?php

namespace App\Repositories;

use App\Database\Database;
use App\Models\Account;

class AccountRepository
{
    protected Database $database;
    private string $tableName;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tableName = 'account';
    }

    public function save(Account $account): void
    {
        $this->database->save($account->toArray(), $this->tableName);
    }

    public function update(Account $account): void
    {
        $data = $account->toArray();
        $this->database->update($data, $this->tableName);
    }

    public function find(string $id): ?Account
    {
        $result = $this->database->findByField('id', $id, $this->tableName);

        if (!empty($result) && !empty($result[0])) {
            return new Account($result[0]['id'], $result[0]['balance']);
        }

        return null;
    }
}