<?php

declare(strict_types=1);

namespace VendingMachine\Command;


use Aura\Sql\ExtendedPdoInterface;
use VendingMachine\DepositAccountInterface;
use VendingMachine\Entity\Wallet;

class DepositCoin
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

    public function deposit(int $coinValue)
    {
        if (!$this->isCoinAvailable($coinValue, Wallet::TYPE_CUSTOMER)) {
            throw new \DomainException('Customer have not coins of value ' . $coinValue);
        }
        if (!$this->isCoinAvailable($coinValue, Wallet::TYPE_MACHINE)) {
            throw new \DomainException('Machine does not support coins of value ' . $coinValue);
        }
        $this->db->beginTransaction();
        try {
            $this->depositAccount->deposit($coinValue);

            $query = 'UPDATE customer_wallet SET quantity = quantity - 1 WHERE value = :value';
            $this->db->perform($query, ['value' => $coinValue]);

            $query ='UPDATE machine_wallet SET quantity = quantity + 1 WHERE value = :value';
            $this->db->perform($query, ['value' => $coinValue]);
        } catch (\Exception $ex) {
            $this->db->rollBack();
            throw $ex;
        }
        $this->db->commit();
    }

    private function isCoinAvailable(int $coinValue, int $walletType)
    {
        switch ($walletType) {
            case Wallet::TYPE_CUSTOMER:
                $table = 'customer_wallet';
                break;
            case Wallet::TYPE_MACHINE:
                $table = 'machine_wallet';
                break;
            default:
                throw new \InvalidArgumentException('Unsupported wallet type ' . $walletType);
        }
        $query = 'SELECT quantity FROM ' . $table . ' WHERE value = :value';
        $coinQuantity = $this->db->fetchValue($query, ['value' => $coinValue]);
        return $coinQuantity >= 1;
    }
}
