<?php

declare(strict_types=1);

namespace VendingMachine\Entity;

class Wallet
{
    const TYPE_CUSTOMER = 1;

    const TYPE_MACHINE = 2;

    /**
     * @var Coin[]
     */
    private $coins = [];

    public function __construct(array $coins = [])
    {
        foreach ($coins as $coin) {
            if (!($coin instanceof Coin)) {
                throw new \InvalidArgumentException('Invalid coin type');
            }
            $value = (int)$coin->getValue();
            if (!isset($this->coins[$value])) {
                $this->coins[$value] = $coin;
                continue;
            }
            $quantity = $this->coins[$value]->getQuantity() + $coin->getQuantity();
            $this->coins[$value]->setQuantity($quantity);
        }
    }

    /**
     * @return Coin[]
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        $result = 0;
        foreach ($this->coins as $coin) {
            $result += $coin->getValue() * $coin->getQuantity();
        }
        return $result;
    }

    public static function fromArray(array $coins): Wallet
    {
        $coins = array_map(
            function (array $coin) {
                return new Coin((int)$coin['value'], (int)$coin['quantity']);
            },
            $coins
        );
        return new Wallet($coins);
    }
}
