* [Back to contents](../Readme.md#contents)

# Success response example

![200](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)
```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-Body-blueviolet?style=for-the-badge)
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


## Successful creation

![201](https://img.shields.io/badge/201-Created-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 201 Created
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-Body-blueviolet?style=for-the-badge)

```json
{
  "data": {
    "token": "HR5qDwg9B09dJSr5SjJ/u6oVBcq6TkOjnAUR0875IcYO8nQUxRSO3KpDVN",
    "expired_at": 1644301111
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

**Note:** *"string" type for **data** is not a feature, it’s a bug.
We are unable to change this in the current API version because some integrations rely on it.
However, we will fix this in the next API versions.*

----