<?php

declare(strict_types=1);

namespace VendingMachine\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;
use VendingMachine\Command\GetState;

class Home
{
    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var GetState
     */
    private $command;

    public function __construct(PhpRenderer $renderer, GetState $command)
    {
        $this->renderer = $renderer;
        $this->command = $command;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $state = $this->command->getState();
        return $this->renderer->render($response, 'index.phtml', $state);
    }
}
