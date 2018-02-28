<?php

declare(strict_types=1);

namespace VendingMachine\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use VendingMachine\Command\BuyProduct;

class Buy
{
    /**
     * @var BuyProduct
     */
    private $command;

    public function __construct(BuyProduct $command)
    {
        $this->command = $command;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        try {
            $this->command->buy((int)$args['product_id']);
            $result = ['success' => true];
        } catch (\Exception $ex) {
            $result = ['error' => $ex->getMessage()];
        }
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json;charset=utf-8');
    }
}
