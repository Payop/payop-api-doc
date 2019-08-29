# Payment signature

Digital signature of the payment is necessary in order to check the immutability/correctness of the data in the process of transferring them over the network between the participants of the payment.

Signature encryption method - **sha256**

**The parameters that make up the digital signature (the order of the parameters does matter)**

**Parameters**

| **Parameter** | **Description** | **Type** | **Example** |
|---|---|---|---|
| order[id] | Payment ID | string | FF01; 354 |
| order[amount] | Amount of payment. 4 decimal places | string | 100.0000 |
| order[currency] | Character code of payment currency, which is supported by the selected payment method | string | USD; EUR |
| secretKey | Application secret key | string | rekrj1f8bc4werwer2343kl23dfasf |

**URL to generate signature**

Using below url you can test the signature generation.

**Content-Type: application/json**

**POST https://payop.com/api/v1.1/payments/signature**

### Signature generation example

**PHP**

```php
<?php
    // $order = ['id' => 'FF01', 'amount' => '100.0000', 'currency' => 'USD'];
    \ksort($order, SORT_STRING);
    $dataSet = \array_values($order);
    if ($status) {
        \array_push($dataSet, $status);
    }
    \array_push($dataSet, $secretKey);
    \hash('sha256', \implode(':', $dataSet));
```

**Python**

```python
import hashlib

# Create Payment
source = bytes("{}:{}:{}:{}".format(amount, currency, order_id, secret_key), "utf-8")
hashlib.sha256(source).hexdigest()

# Callback
source = bytes("{}:{}:{}:{}:{}".format(amount, currency, order_id, status, secret_key), "utf-8")
hashlib.sha256(source).hexdigest()
```

### Examples of signatures generated from real data

```
Amount: "1.2000"
Currency: "USD"
Order ID: "Test-Order-354"
Secret key: "supersecretkey"
Result: 3445000c1f55f447b853fe068529c23fc4188e36aa4984e37836538d95f8e015
```

```
Amount: "0.4500"
Currency: "EUR"
Order ID: "FK-288-SDC"
Secret key: "fantastic_supersecretkey"
Result: 15c4c6ee83285dd82e1d7d29984a718cc527f218b8a0bb7e9b951b08ea1f30cd
```

### CURL Request example


```
curl -X POST \
    https://payop.com/api/v1.1/payments/signature \
    -H 'Content-Type: application/json' \
    -H 'cache-control: no-cache' \
    -d '{"id":"FK-288-SDC", "amount":"0.4500", "currency":"EUR", "secretKey":"rekrj1f8bc4werwer2343kl23dfasf"}'
```
