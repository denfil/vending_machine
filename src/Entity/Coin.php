<?php

declare(strict_types=1);

namespace VendingMachine\Entity;


class Coin
{
    /**
     * @var int
     */
    private $value;

    /**
     * @var int
     */
    private $quantity;

    public function __construct(int $value, int $quantity = 0)
    {
        if ($value < 1) {
            throw new \InvalidArgumentException('Invalid coin value ' . $value);
        }
        $this->value = $value;
        $this->setQuantity($quantity);
    }

    /**
     * Value
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
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
            throw new \InvalidArgumentException('Invalid quantity ' . $quantity);
        }
        $this->quantity = $quantity;
    }
}
