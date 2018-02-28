<?php

declare(strict_types=1);

namespace VendingMachine\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use VendingMachine\Command\ResetApplication;

class Reset
{
    /**
     * @var Reset
     */
    private $command;

    public function __construct(ResetApplication $command)
    {
        $this->command = $command;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        try {
            $this->command->reset();
            $result = ['success' => true];
        } catch (\Exception $ex) {
            $result = ['error' => $ex->getMessage()];
        }
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json;charset=utf-8');
    }
}
