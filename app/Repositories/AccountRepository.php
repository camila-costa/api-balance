<?php

namespace App\Repositories;

use App\Database\Database;
use App\Models\Account;

class AccountRepository
{
    protected Database $database;
    private string $table;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->table = 'accounts';
    }

    public function save(Account $account): void
    {
        $sql = "insert into $this->table (id, balance) values (:id, :balance)";
        $params = [
            'id' => $account->getId('id'),
            'balance' => $account->getBalance('balance'),
        ];
        try {
            $this->database->executar($sql, $params);
        } catch (\Exception $exception) {
            die("Error during save");
        }
    }

    public function update(Account $account): void
    {
        $sql = "update $this->table set balance = :balance where id = :id";
        $params = [
            'id' => $account->getId('id'),
            'balance' => $account->getBalance('balance')
        ];
        try {
            $this->database->executar($sql, $params);
        } catch (\Exception $exception) {
            die("Error during update");
        }
    }

    public function find(string $id): ?Account
    {
        $sql = "select * from $this->table where id = :id";
        try {
            $accounts = $this->toObject($this->database->consultar($sql, ['id' => $id]));

            if (!empty($accounts) && !empty($accounts[0]))
                return new Account($accounts[0]->getId(), $accounts[0]->getBalance());

            return null;
        } catch (\Exception $exception) {
            die("Error during find");
        }
    }

    private function toObject(array $result): array
    {
        $accounts = [];
        foreach ($result as $linha) {
            $account = new Account($linha['id'], $linha['balance']);
            array_push($accounts, $account);
        }
        return $accounts;
    }
}