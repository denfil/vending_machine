<?php

declare(strict_types=1);

namespace VendingMachine\Entity;

class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $price;

    /**
     * @var int
     */
    private $quantity;

    public function __construct(int $id, string $name, int $price, int $quantity)
    {
        if ($id < 0) {
            throw new \InvalidArgumentException('Invalid ID value ' . $id);
        }
        if ($price <= 0) {
            throw new \InvalidArgumentException('Invalid price value ' . $price);
        }
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->setQuantity($quantity);
    }

    /**
     * Id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Price
     *
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Quantity
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Quantity
     *
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Invalid quantity value ' . $quantity);
        }
        $this->quantity = $quantity;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity
        ];
    }

    /**
     * @param array $product
     * @return Product
     */
    public static function fromArray(array $product): Product
    {
        if (isset($product['id']) && $product['id'] < 0) {
            throw new \InvalidArgumentException('Invalid ID value');
        }
        if (!isset($product['price']) || $product['price'] <= 0) {
            throw new \InvalidArgumentException('Invalid price value');
        }
        if (isset($product['quantity']) && $product['quantity'] < 0) {
            throw new \InvalidArgumentException('Invalid quantity value');
        }
        $attributes = [
            'id' => isset($product['id'])
                ? (int)$product['id']
                : 0,
            'name' => isset($product['name'])
                ? $product['name']
                : '',
            'price' => (int)$product['price'],
            'quantity' => isset($product['quantity'])
                ? (int)$product['quantity']
                : 0
        ];
        return new Product(
            $attributes['id'],
            $attributes['name'],
            $attributes['price'],
            $attributes['quantity']
        );
    }
}
