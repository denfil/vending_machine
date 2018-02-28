<?php

$app->get('/', VendingMachine\Action\Home::class);
$app->get('/reset', VendingMachine\Action\Reset::class);
$app->get('/deposit/{coin_value}', VendingMachine\Action\Deposit::class);
$app->get('/buy/{product_id}', VendingMachine\Action\Buy::class);
$app->get('/withdraw', VendingMachine\Action\Withdraw::class);
