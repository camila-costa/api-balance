<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Repositories\AccountRepository;
use App\Models\Account;

class AccountService
{
    private AccountRepository $repository;

    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getBalanceById(string $id): float
    {
        $account = $this->repository->find($id);

        if(!$account instanceOf Account)
            throw new NotFoundException();

        return $account->getBalance();
    }

    public function save(Account $account): void
    {
        $this->repository->save($account);
    }
}