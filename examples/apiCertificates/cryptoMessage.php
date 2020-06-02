<?php

$msg = '[{"method": 7, "amount": 0.2, "currency": "USD", "type": "1", "additionalData": {"walletNumber": "41001560683733", "direction": "Test payout"}},{"method": 7, "amount": 0.2, "currency": "USD", "type": "1", "additionalData": {"walletNumber": "51001560683733", "direction": "Test payout 2"}}]';
$pk = 'TDlp2n8REswWij2WywDSzxF494psFKHsYdVKU6kvwBI=';

echo base64_encode(sodium_crypto_box_seal($msg, base64_decode($pk)));
