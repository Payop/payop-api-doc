 * [Back to contents](../Readme.md#contents)

# Void transaction

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)

### Endpoint description

**Endpoint:**

![POST](https://img.shields.io/badge/-POST-green?style=for-the-badge)

```shell
https://api.payop.com/v1/checkout/void
```

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
```

**Parameters:**

Parameter             |        Type      |                 Description       |  Required |
----------------------|------------------|-----------------------------------|-----------|
invoiceIdentifier     | string           | Invoice identifier                |     *     |

### Request example

```shell
curl -X POST \
  https://api.payop.com/v1/checkout/void \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "{INVOICE_IDENTIFIER}"
}'
```

### Successful response example

![200](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
    "data": {
        "isSuccess": true,
        "message": "",
        "txid": "e6c8ba69-b961-4e93-a083-2097f30dfbd9"
    },
    "status": 1
}
```