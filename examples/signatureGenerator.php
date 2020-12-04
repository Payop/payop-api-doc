<?php

$amount = readline('Enter order amount (integer or numeric string, exactly as in request payload, e.g 100.00, 99.99 etc): ');
$currency = strtoupper(readline('Enter order currency (e.g. USD): '));
$id = readline('Enter your system order ID (e.g. order12345, 12345): ');
$secretKey = readline('Enter secret key of your project: ');

$data = [$amount, $currency, $id, $secretKey];

echo 'Signature: ', PHP_EOL, hash('sha256', implode(':', $data)), PHP_EOL;