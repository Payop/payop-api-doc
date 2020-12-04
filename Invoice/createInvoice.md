* [Back to contents](../Readme.md#contents)

# Create invoice

* [Endpoint description](#endpoint-description)
    * [Payer](#payer)
    * [Template expressions](#template-expressions)
    * [Signature](#signature)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)

Actually, you can create an example request in the personal merchants account in the section Projects > REST.

## Endpoint description

**Endpoint:**

    POST https://payop.com/v1/invoices/create

**Headers:**

    Content-Type: application/json

**Parameters:**

Parameter                     | Type            | Description                                                                                              | Required |
------------------------------|-----------------|----------------------------------------------------------------------------------------------------------|----------|
publicKey                     | string          | Public key issued in the project                                                                         | *        |
**order**                     | **JSON object** | Order info                                                                                               | *        |
&emsp;&emsp;order.id          | string          | Payment ID                                                                                               | *        |
&emsp;&emsp;order.amount      | string          | Amount of the payment                                                                                    | *        |
&emsp;&emsp;order.currency    | string          | The character code of the payment currency, which is supported by the selected payment method            | *        |
&emsp;&emsp;order.description | string          | Description of payment                                                                                   |          |
&emsp;&emsp;order.items       | json array      | Products or services included in the order. An array containing arbitrary data. Can be empty array       | *        |
**payer**                     | **JSON object** | [Payer](#payer) object                                                                                   | *        |
&emsp;&emsp;payer.email       | string          | Payer email                                                                                              | *        |
&emsp;&emsp;payer.name        | string          | Payer name                                                                                               |          |
&emsp;&emsp;payer.phone       | string          | Payer phone                                                                                              |          |
&emsp;&emsp;payer.extraFields | **JSON object** | payer extra info (fieldName:fieldValue)                                                                  |          |
language                      | string          | Language  (en, ru)                                                                                       | *        |
resultUrl                     | string          | Successful payment link. Allowed to use [template expression](#template-expressions).                    | *        |
failPath                      | string          | Unsuccessful payment link. Allowed to use [template expression](#template-expressions).                  | *        |
signature                     | string          | [Signature](#signature)                                                                                  | *        |
paymentMethod                 | string          | Payment method id selected for this invoice. One of [merchant's payment methods](getPaymentMethods.md)   |          |
metadata                      | **JSON object** | Arbitrary structure object to store any additional merchant data. Result JSON should be less than 800 kB |          |

### Payer

Payer is a structure with a specific set of fields such as: `email`, `name`, `phone`, `extraFields`.
Field `email` **required**. Other fields depends on selected payment method.

It's not necessary to fill this fields on this stage, because you can provide payer data when create transaction.
But if you save payer data with invoice, later this data will be merged into transaction. 

----
**Note:** To avoid rigid binding to the structure, which does not give the entire possible list of fields 
to save all possible data, you can use `extraFields` field to save payer extra fields.

----

**Payer object example:**
```json
{
    "email": "test.email@address.com",
    "extraFields": {
        "nationalid": "5579844567",
        "name": "Human"
    }   
}
```

### Template expressions

Template expressions are useful when you need to make some replacements in the strings.
For now only below parameters supports template expressions. 

Parameter      |        Patterns          |
---------------|--------------------------| 
resultUrl      | {{invoiceId}},  {{txid}} |
failPath       | {{invoiceId}},  {{txid}} |


Pattern        |        Replacement
---------------|-------------------------------------| 
{{invoiceId}}  | Replaced with Payop invoice id      |
{{txid}}       | Replaced with Payop transaction id  |

**Template expressions examples:**
```
    # Template
    https://your.site/result-page/?invoiceId={{invoiceId}}&txid={{txid}}
    # Result
    https://your.site/result-page/?invoiceId=b8bf37ab-fc69-44df-bfeb-b9a879ce20b7&txid=1eeda2f2-d3e1-4edd-853e-3d897bc629b2

    # Template
    https://your.site/result-page/{{txid}}/
    # Result
    https://your.site/result-page/1eeda2f2-d3e1-4edd-853e-3d897bc629b2/
```

### Signature

Digital signature of the payment is necessary in order to check the immutability/correctness of the data
in the process of transferring them over the network between the participants of the payment.

Signature required only on invoice creation.

Signature encryption method - **sha256**

**The parameters that make up the digital signature (the order of the parameters does matter)**

**Parameters**

Parameter         | Description                                                                           | Type   | Example           |
------------------|---------------------------------------------------------------------------------------|--------|-------------------|
order.id          | Order ID in your system                                                               | string | FF01; 354         |
order.amount      | Amount of payment                                                                     | string | 100.0000          |
order.currency    | Character code of payment currency, which is supported by the selected payment method | string | USD; EUR          |
secretKey         | Project secret key                                                                    | string | rekrj1f8bc4werwer |

Signature is generated by making **sha-256** hash of such string: "**_order.amount:order.currency:order.id:secretKey_**" (values separated by **":"**, ordering and case matters).

**Signature generation example (PHP)**

```php
<?php
    // $order = ['id' => 'FF01', 'amount' => '100.0000', 'currency' => 'USD'];
    ksort($order, SORT_STRING);
    $dataSet = array_values($order);
    $dataSet[] = $secretKey;
    hash('sha256', implode(':', $dataSet));
```

You can use a PHP script placed [here](../examples/signatureGenerator.php) to generate signature interactively. 
Copy script to your computer and execute `php path/to/script.php` in terminal.
Then provide needed values (exactly as in request payload) and copy generated signature.

**Examples of signatures generated from real data**

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

## Request example

```shell script
curl -X POST \
  https://payop.com/v1/invoices/create \
  -H 'Content-Type: application/json' \
  -d '{
    "publicKey": "application-3b60feb1",
    "order": {
        "id": "test-order",
        "amount": "3",
        "currency": "RUB",
        "items": [
            {
                "id": "487",
                "name": "Item 1",
                "price": "0.8999999999999999"
            },
            {
                "id": "358",
                "name": "Item 2",
                "price": "2.0999999999999996"
            }
        ],
        "description": ""
    },
    "signature": "1ab0dec9b3e6458c5ec76041e5299",
    "payer": {
        "email": "test.user@payop.com",
        "phone": "",
        "name": "",
        "extraFields": []
    },
    "paymentMethod": 261,
    "language": "en",
    "resultUrl": "https://your.site/result",
    "failPath": "https://your.site/fail"
}'
```

### Successful response example

In case of successful response you can get refund identifier from header `identifier`

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

----
**Note:** Don't use identifier from response body, it's will be removed in the future API releases.

----

Body
```json
{
    "data": "",
    "status": 1
}
```