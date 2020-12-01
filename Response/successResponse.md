* [Back to contents](../Readme.md#contents)

# Success response example

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
Authorization: Bearer eyJ0eXAiO...
```
Body
```json
{
    "data": {
        "id": "423131",
        "status": 1,
        "dateTime": {
            "createdAt": 1566543694,
            "updatedAt": null
        }
    },
    "status": 1
}
```
Each successful response is a JSON object with keys:   

Key       | Type                              | Description                                                        |
----------|-----------------------------------|--------------------------------------------------------------------|
data      | **JSON object(s)** OR **string**  | Response data. Arbitrary object or array of objects.               |
status    | **Number**                        | Don't care about it. It's required for internal purposes.          |

----
***Note:** "string" type for **data** is not a feature, this is a bug.
  We are unable to change this in current api version, because of some integrations rely on it.
  But we will fix this in next API versions.*

----
