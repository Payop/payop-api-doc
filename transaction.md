## By ID

You can get transaction information using our internal identifier {ID}

You need to get [token](example.org) o create request. 

**URL for requests**
> Content-Type: application/json
>
> GET https://payop.com/v1/transactions/{ID}


#### Parameters
| Parameter | Example | Required |
| :---      | :--- |:---: | 
| token | eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEwMDAyIiwiYWNjZXNzVG9rZW4iOm51bGwsInRpbWUiOjE1NjY5MTk4NDJ9.jebGttoGUOGQORsPyr5smSbE01fEGDjFgUkBCF342sc  | * |

Example of response:

```json
{
    "data": {
        "identifier": "2172b62b-d4fc-48db-84dc-444535d5823a",
        "walletIdentifier": "289",
        "type": 7,
        "amount": 1,
        "currency": "RUB",
        "payAmount": 1.06,
        "payCurrency": "RUB",
        "state": 2,
        "commission": [
            {
                "identifier": "829",
                "type": 1,
                "percent": 6,
                "amount": 0,
                "totalValue": 0,
                "strategy": 1,
                "merchantPercent": 0,
                "payerPercent": 0,
                "merchantAmount": 0,
                "payerAmount": 0,
                "transactionIdentifier": "2172b62b-d4fc-48db-84dc-444535d5823a"
            }
        ],
        "exchange": [],
        "createdAt": 1566912915,
        "updatedAt": 1566913014,
        "orderId": "myOrderID",
        "description": "myGreatOrder",
        "productAmount": 1,
        "productCurrency": "RUB",
        "pageDetails": [],
        "language": "en",
        "paymentMethodIdentifier": "381",
        "payerInformation": [
            {
                "type": 1,
                "value": "John.McLein@payop.com"
            }
        ],
        "geoInformation": {
            "ip": "8.8.8.8",
            "city": {
                "id": 703448,
                "lat": 50.45466,
                "lon": 30.5238,
                "name_en": "Kiev",
                "name_ru": "Киев"
            },
            "region": {
                "id": 703447,
                "iso": "UA-30",
                "name_en": "Kyiv",
                "name_ru": "Киев"
            },
            "country": {
                "id": 222,
                "iso": "UA",
                "lat": 49,
                "lon": 32,
                "name_en": "Ukraine",
                "name_ru": "Украина"
            }
        },
        "resultUrl": "https://yourdomain.com/resulturl.php",
        "failUrl": "https://yourdomain.com/failurl.php",
        "pid": "1CpueFKQgFc",
        "application": {
            "identifier": "025",
            "name": "NameOfApp",
            "info": "TestInfo"
        }
    },
    "status": 1
}
```


**Possible transaction states:**

* 1 - NEW 
* 2 - ACCEPTED
* 3 - REJECTED
* 4 - PENDING
* 5 - FAILED
