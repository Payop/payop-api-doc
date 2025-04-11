* [Back to contents](../Readme.md#contents)

# Get withdrawal details

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Withdrawal statuses](#withdrawal-statuses)

## Endpoint description

You can see detailed information on the withdrawal by specifying the withdrawal ID.

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint:**

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```shell
https://api.payop.com/v1/withdrawals/user-withdrawals?query[identifier]={payopWithdrawalId}
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

**Parameters:**

`payopWithdrawalId` - identifier of withdrawal

## Request example

```shell
curl -X GET \
  https://api.payop.com/v1/withdrawals/user-withdrawals?query[identifier]=7ede204f-0d2a-55c4-9b84-8877720c9f6a \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer YOUR_JWT_TOKEN'
```

## Successful response example

![200](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "data": [
    {
      "identifier": "7ede204f-0d2a-55c4-9b84-8877720c9f6a",
      "groupIdentifier": null,
      "userIdentifier": "10043",
      "type": 1,
      "currency": "USD",
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
        "cardHolderName": "HOLDER NAME"
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
