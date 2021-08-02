 * [Back to contents](../Readme.md#contents)

# Get concrete withdrawal details

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Withdrawal statuses](#withdrawal-statuses)


## Endpoint description

**Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint:**

    GET https://payop.com/v1/withdrawals/user-withdrawals?query[identifier]={payopWithdrawalId}

**Headers:**
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...
    
**Parameters:**

**payopWithdrawalId** - identifier of concrete withdrawal.

## Request example

```shell script
curl -X GET \
  https://payop.com/v1/withdrawals/user-withdrawals?query[identifier]=7ede204f-0d2a-55c4-9b84-8877720c9f6a \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV...'
```

## Successful response example

```json
{
    "data": [
        {
            "identifier": "7ede204f-0d2a-55c4-9b84-8877720c9f6a",
            "groupIdentifier": null,
            "userIdentifier": "10043",
            "type": 1,
            "currency": "RUB",
            "amount": 100,
            "payAmount": 111.11,
            "transactionIdentifier": "0000000-0000-0000-0000-00000001",
            "status": 1,
            "method": 4,
            "createdAt": 1568112855,
            "updatedAt": null,
            "comment": null,
            "additionalData": {
                "cardNumber": "4444444444444444",
                "cardHolderName": "Ivan Ivanov"
            },
            "metadata": {
                "internal merchant id": "example"
            }
        }
    ],
    "status": 1
}
```

## Withdrawal statuses

Status | Type     | Description                       |
-------|----------|-----------------------------------|
1, 4   | pending  | Pending withdrawal                |
2      | accepted | Accepted withdrawal               |
3      | rejected | Rejected withdrawal               |
