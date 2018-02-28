<?php

declare(strict_types=1);

namespace VendingMachine\Command;

use Aura\Sql\ExtendedPdoInterface;
use VendingMachine\DepositAccountInterface;
use VendingMachine\Entity\Product;

class BuyProduct
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

    public function buy(int $productId)
    {
        $product = $this->getProduct($productId);
        if ($product->getQuantity() < 1) {
            throw new \DomainException('Product "' . $product->getName() . '" is out of stock');
        }
        $balance = $this->depositAccount->getBalance();
        if ($product->getPrice() > $balance) {
            throw new \DomainException('Deposited money is not enough to buy "' . $product->getName() . '"');
        }
        $this->db->beginTransaction();
        try {
            $this->depositAccount->withdraw($product->getPrice());

            $query = 'UPDATE products SET quantity = quantity - 1 WHERE id = :id';
            $this->db->perform($query, ['id' => $product->getId()]);
        } catch (\Exception $ex) {
            $this->db->rollBack();
            throw $ex;
        }
        $this->db->commit();
    }

    private function getProduct(int $productId): Product
    {
        $query = 'SELECT * FROM products WHERE id = :id';
        $product = $this->db->fetchOne($query, ['id' => $productId]);
        if (!$product) {
            throw new \DomainException('Product with ID ' . $product . ' is not found');
        }
        return Product::fromArray($product);
    }
}
