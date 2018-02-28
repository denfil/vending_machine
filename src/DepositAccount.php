<?php

declare(strict_types=1);

namespace VendingMachine;

class DepositAccount implements DepositAccountInterface
{
    public function deposit(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Invalid money value ' . $value);
        }
        if (!isset($_SESSION['deposit'])) {
            $_SESSION['deposit'] = 0;
        }
        $_SESSION['deposit'] += $value;
    }

    public function withdraw(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Invalid money value ' . $value);
        }
        if (isset($_SESSION['deposit']) && $_SESSION['deposit'] < $value) {
            throw new \DomainException('Deposited money is not enough to withdraw ' . $value);
        }
        $_SESSION['deposit'] -= $value;
    }

    public function getBalance(): int
    {
        return $_SESSION['deposit'] ?? 0;
    }

    public function close()
    {
        unset($_SESSION['deposit']);
    }
}
