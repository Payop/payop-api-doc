* [Back to contents](../Readme.md#contents)

# How to Create and Encrypt Withdrawal Payload

**This guide explains how to prepare, encrypt, and submit a withdrawal request to the Payop API using your personal certificate and libsodium encryption.**


### **🧩 Step 1: Generate and Download a Certificate**



1. **Log in to your[ Payop Merchant Dashboard](https://payop.com/).**
2. **Navigate to <code>Settings</code> → <code>Certificates</code>.**
3. **Click Generate Certificate.**
4. **Enter the 6-digit 2FA code received on your registered email or app.**
5. **Download the <code>x25519.pub</code> certificate file.**
6. **Save the file to your working directory (e.g., <code>/project/x25519.pub</code>).**

> 🔐 This certificate is used to encrypt the payload so only Payop can decrypt it.



### **🧾 Step 2: Prepare the Withdrawal Payload (Raw JSON)**

**Prepare the JSON structure you want to send.**


```json
[
 {
   "method": 14,
   "type": 1,
   "amount": 1000,
   "currency": "EUR",
   "additionalData": {
     "direction": "Test Payout",
     "email": "recipient@example.com"
   },
   "metadata": {
     "internalOrderId": "ORDER-2024-001"
   }
 }
]
```

* **<code>method</code>: Withdrawal method ID (e.g., <code>14</code> = Volet, <code>15</code> = PayDo)**
* **<code>type</code>: Commission type (1 - from wallet, 2 - from withdrawal amount)**
* **<code>amount</code>: Amount to be withdrawn**
* **<code>currency</code>: Payout currency (EUR, USD, etc.)**
* **<code>additionalData</code>: Method-specific fields (check required fields per method)**
* **<code>metadata</code>: Optional custom data (for internal tracking)**


---


### **🛠️ Step 3: Encrypt the Payload Using PHP and Sodium**


```shell
<?php
// Step 1: Load the certificate (binary file)

$certFilePath = '/project/x25519.pub'; // Update with actual path
$certificate = file_get_contents($certFilePath);

// Step 2: Prepare the withdrawal payload (same structure as in Step 2)

$data = [
   [
       'method' => 14,
       'type' => 1,
       'amount' => 1000,
       'currency' => 'EUR',
       'additionalData' => [
           'direction' => 'Affiliate Payout',
           'email' => 'recipient@example.com'
       ],
       'metadata' => [
           'internalOrderId' => 'ORDER-2024-001'
       ]
   ]
];

// Step 3: Encrypt using Sodium

$encrypted = sodium_crypto_box_seal(json_encode($data), $certificate);

// Step 4: Base64 encode

$base64Payload = base64_encode($encrypted);
echo "Encrypted payload:\n" . $base64Payload;
```


> ⚠️ PHP 7.2+ includes Sodium natively. No extra dependencies needed.


---


### **🧪 Step 4: Submit the Payload to Payop API**

**Use <code>curl</code>, Postman, or your backend to send the request:**


```shell
curl -X POST https://api.payop.com/v1/withdrawals/create-mass \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN" \
 -H "idempotency-key: UNIQUE-UUID" \
 -d '{
   "data": "YOUR_BASE64_ENCRYPTED_PAYLOAD_HERE"
}'
```

> Replace <code>"YOUR_BASE64_ENCRYPTED_PAYLOAD_HERE"</code> with the output from the PHP script above.


### **✅ Example Encrypted Payload**


```
{"data":"YwukuaG2G0whJfHWpdIXrMay76o1\/VuSmUr8cpSUGCDhqEnry4FsFOTJpmccoQ6w\/Z2VmQKgkvJ\/Hz7v8VrvYSfoTsnX4cKvoUasgC2xOwgPdYzcmx5zIq4SlEHx418OwM\/oqAHb5cEO\/IFwBlMHzL1IAc7yFCwOjSUMg+8SNlawtTLGRNtIb8V6\/gqZRZXoyQHuXoclRE1tR\/2GZjjiD6aEM0JQPNg3NssPxuQuRiRAgzhMPrnCS53FQIjZrR9sVDcb4iJxhpXORHpSZ8vLgT9Ya6RSdsNUWrpaTUBr1MACULgN8Oib1G8U7PK3YoNb8APfibnAklTLuo7HDkvFB7FQ+xIOvYfg4LgK5vbQIRensLzGVz8ktIOyLpwOw2wBgf6HrUI2HiooCg+o9hR0qqRoZCSi\/psRgQU5Ry3fSSWsw+Q39pNCm9sbQvYQZ6akti7KrcDYdLAjKFKu3DdB2lX38shjErM\/QMxjBegeOsl50DouknCq1BImSCR5HYJhJgRP1sZo8S5RfiBjSzXbgcddaSG6kIkRirCt"}
```

### **📖	 User Guides**
---

<details>
   <summary> 👉 Guide: Run PHP Script to Encrypt Withdrawal Payload Using Docker</summary> 
 
### **✅ Prerequisites**

* Make sure **Docker** is installed on your machine.  

👉[You can download Docker Desktop here:](https://www.docker.com/products/docker-desktop)

### **Step 1: Prepare Your Project Folder**

1. Create a folder on your local machine:


```shell
mkdir payop-withdrawal-encrypt 
cd payop-withdrawal-encrypt

```



2. Create the `encrypt.php` script:


```shell
touch encrypt.php
```



3. Paste the following example script into `encrypt.php`:


```shell
<?php
$certFilePath = __DIR__ . '/c9f5753e-587f-41fa-9b2a-b7ab998d1bcc'; // Ensure your cert is here
$certificate = file_get_contents($certFilePath);
$data = [
   [
       'method' => 14,
       'type' => 1,
       'amount' => 1000,
       'currency' => 'EUR',
       'additionalData' => [
           'direction' => 'Test payout',
           'email' => 'recipient@example.com'
       ]
   ]
];
$encryptedPayload = sodium_crypto_box_seal(json_encode($data), $certificate);
$base64Payload = base64_encode($encryptedPayload);
echo "Encrypted Base64 Payload:\n\n" . $base64Payload . PHP_EOL;
```



4. Add your `с9f5753e-587f-41fa-9b2a-b7ab998d1bcc` certificate file to the same folder.

You should now have:


```shell
payop-withdrawal-encrypt/
├── encrypt.php
└── с9f5753e-587f-41fa-9b2a-b7ab998d1bcc
```



### **Step 2: Run Docker PHP Container**

Use this one-line Docker command to run your script inside a PHP container with Sodium installed:


```shell
docker run --rm -v "$PWD":/app -w /app php:8.2-cli php encrypt.php
```


If you are using **Windows CMD**, replace `$PWD` with `%cd%`:


```shell
docker run --rm -v %cd%:/app -w /app php:8.2-cli php encrypt.php
```



### **🧾 Expected Output**

You’ll get a response like this:


```shell
Encrypted Base64 Payload:  9kQ7v9nXLHjeOyIqi+hIJfEKuOCQZ2C5WWVcnmfPHUxh1EbK5g=
```



### **📌 Notes**



* PHP 7.2+ is required because Sodium is built-in from PHP 7.2 and later.
* If you want to run the script multiple times, just run the Docker command again.
* You **don’t need to install PHP locally** — Docker handles everything inside the container.
</details>


<details>
  <summary> 👉 Guide: Run Python Encryption Script for Payop Withdrawal Using Docker</summary>  

 ### **✅ Prerequisites**


👉[**Docker** must be installed](https://www.docker.com/products/docker-desktop)


### **Step 1: Prepare the Project Folder**



1. Create a folder and enter it:


```shell
mkdir payop-python-encrypt 
cd payop-python-encrypt
```



2. Create the encryption script:


```shell
touch encrypt.py
```



3. Paste the following code into `encrypt.py`:


```shell
# encrypt.py
import libnacl
import base64
# Withdrawal data payload
message = '[{"method": 7, "amount": 0.2, "currency": "USD", "type": "1", "additionalData": {"walletNumber": "41001560683733", "direction": "Test payout"}},{"method": 7, "amount": 0.2, "currency": "USD", "type": "1", "additionalData": {"walletNumber": "51001560683733", "direction": "Test payout 2"}}]'
# Your certificate (Base64-encoded x25519 public key)
publicKey = 'ZyC4u8gs6ivyu3FxPUuIJJqq560Xt5pGdnBgI8S11nk='
# Encrypt and encode
box = libnacl.crypto_box_seal(message.encode(), base64.b64decode(publicKey))
print("\nEncrypted Base64 Payload:\n")
print(base64.b64encode(box).decode())
```


 


### **Step 2: Create a Dockerfile**


```shell
touch Dockerfile
```


Paste this into the `Dockerfile`:


```shell
FROM python:3.10-slim
RUN pip install libnacl
WORKDIR /app
COPY encrypt.py .
CMD ["python", "encrypt.py"]
```



### **Step 3: Build and Run the Docker Container**



1. Build the Docker image:


```shell
docker build -t payop-encrypt .
```



2. Run the container:


```shell
docker run --rm payop-encrypt
```



### **✅ Output**

You should see similar result:


```shell
Encrypted Base64 Payload:  9kQ7v9nXLHjeOyIqi+hIJfEKuOCQZ2C5WWVcnmfPHUxh1EbK5g=
```

</details>
