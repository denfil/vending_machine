<?php

declare(strict_types=1);

namespace VendingMachine\Command;


use Aura\Sql\ExtendedPdoInterface;
use VendingMachine\DepositAccountInterface;
use VendingMachine\Entity\Product;
use VendingMachine\Entity\Wallet;

class GetState
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

    public function getState()
    {
        return [
            'products' => $this->createProductList(),
            'machineWallet' => $this->createWallet(Wallet::TYPE_MACHINE),
            'customerWallet' => $this->createWallet(Wallet::TYPE_CUSTOMER),
            'depositAccount' => $this->depositAccount
        ];
    }

    public function createProductList(): array
    {
        $query = 'SELECT * FROM products WHERE quantity > 0';
        $products = $this->db->fetchAll($query);
        if (!$products) {
            return [];
        }
        $result = array_map(
            function (array $product) {
                return Product::fromArray($product);
            },
            $products
        );
        return $result;
    }

    public function createWallet(int $walletType): Wallet
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
        $query = 'SELECT * FROM ' . $table . ' WHERE quantity > 0';
        $coins = $this->db->fetchAll($query);
        return Wallet::fromArray($coins);
    }
}
