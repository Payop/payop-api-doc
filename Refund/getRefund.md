### Get concrete refund details

**Endpoint:**

`GET https://payop.com/v1/refunds/user-refunds?query[identifier]={payopRefundId}`

**Headers:**

    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

**Request example:**

```shell script
curl -X GET \
  https://payop.com/v1/refunds/user-refunds?query[identifier]={payopRefundId} \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV...
```
