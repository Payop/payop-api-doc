* [Capture transaction](#capture-transaction)
* [Void transaction](#void-transaction)
    
# Capture transaction

### URL for requests

`Content-Type: application/json`

`POST https://payop.com/v1/checkout/capture`

**Parameters**

Parameter             |        Type      |                 Description                                                                             |  Required |
----------------------|------------------|---------------------------------------------------------------------------------------------------------|-----------|
invoiceIdentifier     | string           | Invoice identifier                                                                                      |     *     |

### Request example

```shell script
curl -X POST \
  https://payop.com/v1/checkout/capture \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "e61dfa44-4987-400a-b58e-cd550aae9613"
}'
```

### Successful response example
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

### Errors and failed responses

**415 Unsupported Media Type**
```json
{
  "message": "Unsupported media type. Only json allowed"
}
```

# Void transaction

### URL for requests

`Content-Type: application/json`

`POST https://payop.com/v1/checkout/void`

**Parameters**

Parameter             |        Type      |                 Description                                                                             |  Required |
----------------------|------------------|---------------------------------------------------------------------------------------------------------|-----------|
invoiceIdentifier     | string           | Invoice identifier                                                                                      |     *     |

### Request example

```shell script
curl -X POST \
  https://payop.com/v1/checkout/void \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "e61dfa44-4987-400a-b58e-cd550aae9613"
}'
```

### Successful response example
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

### Errors and failed responses

**415 Unsupported Media Type**
```json
{
  "message": "Unsupported media type. Only json allowed"
}
```
