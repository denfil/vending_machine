<?php

declare(strict_types=1);

namespace VendingMachine\Command;


use Aura\Sql\ExtendedPdoInterface;
use VendingMachine\DepositAccountInterface;

class WithdrawMoney
{
    /**
     * @var ExtendedPdoInterface
     */
    private $db;

    /**
     * @var DepositAccountInterface
     */
    private $depositAccount;

    public function __construct(ExtendedPdoInterface $db, DepositAccountInterface $depositAccount)
    {
        $this->db = $db;
        $this->depositAccount = $depositAccount;
    }

    public function withdraw()
    {
        $balance = $this->depositAccount->getBalance();
        if ($balance < 1) {
            return;
        }
        $machineCoins = $this->getAvailableMachineCoins();
        if (!$machineCoins) {
            throw new \DomainException('There is not money for withdrawals');
        }
        $coins = $this->calcCoinsForWithdrawal($balance, $machineCoins);
        if (!$coins) {
            throw new \DomainException('This are not enough coins for withdrawal');
        }
        $this->db->beginTransaction();
        try {
            foreach ($coins as $coin) {
                $query = 'UPDATE machine_wallet SET quantity = quantity - :quantity WHERE value = :value';
                $this->db->perform($query, $coin);

                $query = 'UPDATE customer_wallet SET quantity = quantity + :quantity WHERE value = :value';
                $this->db->perform($query, $coin);
            }
            $this->depositAccount->withdraw($balance);
        } catch (\Exception $ex) {
            $this->db->rollBack();
            throw $ex;
        }
        $this->db->commit();
    }

    private function getAvailableMachineCoins(): array
    {
        $query = 'SELECT * FROM machine_wallet WHERE quantity > 0';
        $coins = $this->db->fetchAll($query);
        return $coins ?: [];
    }

    private function calcCoinsForWithdrawal(int $withdrawal, array $coins)
    {
        $coinsMap = $this->toMap($coins);
        krsort($coinsMap);
        $resultCoinsMap = [];
        $current = 0;
        while ($current < $withdrawal) {
            $coinValue = key($coinsMap);
            $coinQuantity = current($coinsMap);
            if ($coinQuantity == 0 || $current + $coinValue > $withdrawal) {
                if (next($coinsMap) === false) {
                    return false;
                }
                continue;
            }
            $current += $coinValue;
            $coinsMap[$coinValue]--;
            if (!isset($result[$coinValue])) {
                $resultCoinsMap[$coinValue] = 0;
            }
            $resultCoinsMap[$coinValue]++;
        }
        $result = $this->toArray($resultCoinsMap);
        return $result;
    }

    private function toMap(array $coinsArray): array
    {
        $result = [];
        foreach ($coinsArray as $coin) {
            $result[$coin['value']] = $coin['quantity'];
        }
        return $result;
    }

    private function toArray(array $coinsMap): array
    {
        $result = [];
        foreach ($coinsMap as $value => $quantity) {
            $result[] = [
                'value' => $value,
                'quantity' => $quantity
            ];
        }
        return $result;
    }
}
