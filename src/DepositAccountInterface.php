<?php

declare(strict_types=1);

namespace VendingMachine;


interface DepositAccountInterface
{
    public function deposit(int $value);
    public function withdraw(int $value);
    public function getBalance(): int;
    public function close();
}