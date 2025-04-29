* [Back to contents](../Readme.md#contents)

# **How to Use the Signature Generator (PHP)**

This PHP script helps generate a **SHA-256 signature** for securing your payment requests. Follow the steps below to generate a signature.


#### **PHP script:**


```shell
<?php

$amount = readline('Enter order amount (integer or numeric string, exactly as in request payload, e.g 100.00, 99.99 etc): ');
$currency = strtoupper(readline('Enter order currency (e.g. USD): '));
$id = readline('Enter your system order ID (e.g. order12345, 12345): ');
$secretKey = readline('Enter secret key of your project: ');
$data = [$amount, $currency, $id, $secretKey];
echo 'Signature: ', PHP_EOL, hash('sha256', implode(':', $data)), PHP_EOL;

```

#### **Steps to Use:**

1. **Save the script** as a `.php` file, e.g., `generate_signature.php`.
2. **Run the script** in a terminal using the command:
3. `php generate_signature.php`
4. **Enter the required values** when prompted:
    * **Order Amount** (e.g., `100.00`, `99.99`)
    * **Order Currency** (e.g., `USD`, `EUR`)
    * **Order ID** (e.g., `order12345`, `12345`)
    * **Secret Key** (your project’s secret key)
5. **Get the generated signature** printed in the terminal.

#### **Example Usage:**

```shell
php generate_signature.php
```

**Example Input:**


```shell
Enter order amount: 99.99
Enter order currency: USD
Enter your system order ID: order12345
Enter secret key of your project: mySuperSecretKey
```

**Example Output:**

```shell
Signature: e4b6a9d1a8c84f1eab21b29399d5f5a9f91a56cfa02a41d0e4c7b8c24caa5b12
```

