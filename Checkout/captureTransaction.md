 * [Back to contents](../Readme.md#contents)

# Capture Transaction

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)

----
***Note:** The longest period between auth and capture operations is 72 hours. Therefore, if no capture request comes within 72 hours since auth is received, it will be performed anyway.*

----

## Endpoint description

**Endpoint:**

    POST https://payop.com/v1/checkout/capture

**Headers:**

    Content-Type: application/json

**Parameters:**

Parameter             |        Type      |                 Description                    |  Required |
----------------------|------------------|------------------------------------------------|-----------|
invoiceIdentifier     | string           | Invoice identifier                             |     *     |

## Request example

```shell script
curl -X POST \
  https://payop.com/v1/checkout/capture \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "e61dfa44-4987-400a-b58e-cd550aae9613"
}'
```

## Successful response example
Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

Body
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