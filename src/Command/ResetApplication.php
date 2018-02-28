<?php

declare(strict_types=1);

namespace VendingMachine\Command;


use Aura\Sql\ExtendedPdoInterface;
use VendingMachine\DepositAccountInterface;

class ResetApplication
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

    public function reset()
    {
        $this->db->beginTransaction();
        try {
            $this->resetProducts();
            $this->resetMachineWallet();
            $this->resetCustomerWallet();
            $this->depositAccount->close();
        } catch (\Exception $ex) {
            $this->db->rollBack();
            throw $ex;
        }
        $this->db->commit();
    }

    private function resetProducts()
    {
        $this->db->exec('DELETE FROM products');
        $products = [
            '(1, "Tea", 13, 10)',
            '(2, "Сoffee", 18, 20)',
            '(3, "Сoffee with milk", 21, 20)',
            '(4, "Juice", 35, 15)'
        ];
        $query = 'INSERT INTO products (id, name, price, quantity) VALUES '
            . implode(', ', $products);
        $this->db->exec($query);
    }

    private function resetMachineWallet()
    {
        $this->db->exec('DELETE FROM machine_wallet');
        $coins = [
            '(1, 100)',
            '(2, 100)',
            '(5, 100)',
            '(10, 100)'
        ];
        $query = 'INSERT INTO machine_wallet (value, quantity) VALUES '
            . implode(', ', $coins);
        $this->db->exec($query);
    }

    private function resetCustomerWallet()
    {
        $this->db->exec('DELETE FROM customer_wallet');
        $coins = [
            '(1, 10)',
            '(2, 30)',
            '(5, 20)',
            '(10, 15)'
        ];
        $query = 'INSERT INTO customer_wallet (value, quantity) VALUES '
            . implode(', ', $coins);
        $this->db->exec($query);
    }
}
