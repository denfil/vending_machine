<?php

$container = $app->getContainer();

$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['database'] = function ($c) {
    $settings = $c->get('settings')['database'];
    return new Aura\Sql\ExtendedPdo($settings['dsn']);
};

$container['deposite_account'] = function ($c) {
    return new \VendingMachine\DepositAccount();
};

$container['get_state_cmd'] = function ($c) {
    return new \VendingMachine\Command\GetState(
        $c->get('database'),
        $c->get('deposite_account')
    );
};

$container['reset_cmd'] = function ($c) {
    return new \VendingMachine\Command\ResetApplication(
        $c->get('database'),
        $c->get('deposite_account')
    );
};

$container['deposit_cmd'] = function ($c) {
    return new \VendingMachine\Command\DepositCoin(
        $c->get('database'),
        $c->get('deposite_account')
    );
};

$container['buy_cmd'] = function ($c) {
    return new \VendingMachine\Command\BuyProduct(
        $c->get('database'),
        $c->get('deposite_account')
    );
};

$container['withdraw_cmd'] = function ($c) {
    return new \VendingMachine\Command\WithdrawMoney(
        $c->get('database'),
        $c->get('deposite_account')
    );
};

$container[VendingMachine\Action\Home::class] = function($c) {
    return new VendingMachine\Action\Home(
        $c->get('renderer'),
        $c->get('get_state_cmd')
    );
};

$container[VendingMachine\Action\Reset::class] = function($c) {
    return new VendingMachine\Action\Reset(
        $c->get('reset_cmd')
    );
};

$container[VendingMachine\Action\Deposit::class] = function($c) {
    return new VendingMachine\Action\Deposit(
        $c->get('deposit_cmd')
    );
};

$container[VendingMachine\Action\Buy::class] = function($c) {
    return new VendingMachine\Action\Buy(
        $c->get('buy_cmd')
    );
};

$container[VendingMachine\Action\Withdraw::class] = function($c) {
    return new VendingMachine\Action\Withdraw(
        $c->get('withdraw_cmd')
    );
};