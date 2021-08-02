 * [Back to contents](../Readme.md#contents)

# Merchant's withdrawal transactions

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)


## Endpoint description

**Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).
    
**Endpoint:**

    GET https://payop.com/v1/withdrawals/user-withdrawals

**Headers:**
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

## Request example:

```shell script
curl -X GET \
  https://payop.com/v1/withdrawals/user-withdrawals \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer eyJ0eXAiOiJKV...'
```

## Successful response example:

```json
{
    "data": [
        {
            "identifier": "0000000-0000-0000-0000-00000001",
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
                "direction": "Dm PENDING test withdrawal",
                "cardNumber": "4444444444444444",
                "cardHolderName": "Ivan Ivanov"
            },
            "metadata": {
                "internal merchant id": "example"
            }
        },
        {
            "identifier": "0000000-0000-0000-0000-00000001",
            "groupIdentifier": null,
            "userIdentifier": "10043",
            "type": 1,
            "currency": "RUB",
            "amount": 100,
            "payAmount": 111.11,
            "transactionIdentifier": "0000000-0000-0000-0000-00000001",
            "status": 4,
            "method": 4,
            "createdAt": 1568112855,
            "updatedAt": null,
            "comment": null,
            "additionalData": {
                "direction": "Dm PENDING test withdrawal",
                "cardNumber": "4444444444444444",
                "cardHolderName": "Ivan Ivanov"
            },
            "metadata": {
                "internal merchant id": "example"
            }
        },
        {
            "identifier": "0000000-0000-0000-0000-00000002",
            "groupIdentifier": null,
            "userIdentifier": "10043",
            "type": 1,
            "currency": "RUB",
            "amount": 65,
            "payAmount": 70,
            "transactionIdentifier": "0000000-0000-0000-0000-00000002",
            "status": 2,
            "method": 6,
            "comment": null,
            "createdAt": 1579502520,
            "updatedAt": 1579502818,
            "metadata": [],
            "additionalData": {
                "direction": "Dm ACCEPTED test withdrawal",
                "country": "UA",
                "walletNumber": "+3800000000"
            }
        },
        {
            "identifier": "0000000-0000-0000-0000-00000003",
            "groupIdentifier": null,
            "userIdentifier": "10043",
            "type": 1,
            "currency": "RUB",
            "amount": 65,
            "payAmount": 70,
            "transactionIdentifier": "0000000-0000-0000-0000-00000003",
            "status": 3,
            "method": 6,
            "comment": null,
            "createdAt": 1579502520,
            "updatedAt": 1579502818,
            "metadata": [],
            "additionalData": {
                "direction": "Dm FAILED test withdrawal",
                "country": "UA",
                "walletNumber": "+3800000000"
            }
        }
    ],
    "status": 1
}
```

You can see list of possible withdrawal statuses [here](getWithdrawal.md#withdrawal-statuses).