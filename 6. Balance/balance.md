# Wallet Balances

**The Wallet Balances API allows merchants to retrieve a real-time snapshot of their wallet balances across all supported currencies. It is designed to provide a clear, consistent view of funds availability before performing operations such as withdrawals, exchanges, or payouts.**

**Wallet model:** each authenticated user has one unified wallet, and all balances are returned within a single request.

| # | Endpoint | Method | Auth Required | Purpose |
|---|----------|--------|--------------|---------|
| 1 | [`/v1/wallets/get-balances`](#1-get-wallet-balances) | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge) | ✅ Yes | Retrieve current wallet balances grouped by currency. |

---

## Balance Types

For each currency, the API returns the following balance components:

<table>
  <tr>
    <td><b>Balance Type</b></td>
    <td><b>Description</b></td>
  </tr>
  <tr>
    <td><code>available</code></td>
    <td>Funds fully available for withdrawals and payments.</td>
  </tr>
  <tr>
    <td><code>referral</code></td>
    <td>Referral or partner earnings.</td>
  </tr>
  <tr>
    <td><code>pending</code></td>
    <td>Funds temporarily on hold (verification, processing, checks).</td>
  </tr>
  <tr>
    <td><code>reserve</code></td>
    <td>Locked or restricted funds (risk, compliance, reserve policies).</td>
  </tr>
  <tr>
    <td><code>total</code></td>
    <td>Aggregated balance (optional; returned only if <code>includeTotal=1</code>).</td>
  </tr>
</table>

**Notes:**
- Negative values are allowed for any balance type (e.g., chargebacks, adjustments).
- <code>total</code> is optional and returned only when <code>includeTotal=1</code>.

---

### **1. Get Wallet Balances**

### **Purpose:**

**Retrieve a real-time snapshot of wallet balances across all currencies available for the authenticated user. The response reflects the wallet state at the moment the request is processed.**

### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
https://api.payop.com/v1/wallets/get-balances
```

![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)

```shell
Authorization: Bearer YOUR_JWT_TOKEN
Accept: application/json
```

### **Query Parameters:**

| Parameter | Type | Required | Description |
|----------|------|----------|-------------|
| includeTotal | boolean | No | Includes the calculated <code>total</code> balance for each currency. |
| currency | string | No | Optional (if enabled). Filter balances only for selected currencies (e.g. <code>EUR,USD</code>). |

### **Behavior:**

- <code>includeTotal=1</code> → the <code>total</code> field is included.
- <code>includeTotal=0</code> or omitted → <code>total</code> is excluded.
- Optional currency filtering (if enabled): <code>currency=EUR,USD</code> returns balances only for selected currencies.
- Wallet-type filtering is not supported because the wallet is unified.

### **Example Requests:**

```shell
/v1/wallets/get-balances
/v1/wallets/get-balances?includeTotal=1
/v1/wallets/get-balances?currency=EUR,USD&includeTotal=1
```

![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)

```shell
curl -X GET "https://api.payop.com/v1/wallets/get-balances?includeTotal=1"  -H "Accept: application/json"  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
  "snapshotAt": "2025-09-15T12:34:56Z",
  "currencies": [
    {
      "code": "EUR",
      "balance": {
        "available": "1000.50",
        "referral": "15.00",
        "pending": "50.00",
        "reserve": "200.00",
        "total": "1265.50"
      }
    },
    {
      "code": "USD",
      "balance": {
        "available": "8000.50",
        "referral": "0.00",
        "pending": "0.00",
        "reserve": "0.00",
        "total": "8000.50"
      }
    }
  ]
}
```

### **Response Fields Explanation:**

| Field | Description |
|------|-------------|
| snapshotAt | UTC timestamp of the balance snapshot. |
| currencies | List of wallet balances grouped by currency. |
| code | ISO-4217 currency code. |
| balance | Balance values returned as strings to prevent precision loss. |

### **Data Guarantees:**

- Point-in-time snapshot: all balances are calculated within one transaction context.
- Consistency: values inside the response are internally consistent.
- UTC timestamps are always used.
- Negative balances are valid and expected in certain scenarios.

---

## Error Handling

### 400 Bad Request
Returned when query parameters are invalid.

```json
{
  "message": {
    "currencies [0]": [
      "This value is not a valid currency."
    ]
  }
}
```

### 401 Unauthorized
Returned when the access token is missing, invalid, or expired.

```json
{
  "message": "Unable find authenticated user"
}
```

### 429 Too Many Requests
Returned when rate limits are exceeded.

```json
{
  "message": "Too many requests from ip"
}
```

### 500 Internal Server Error
Returned for unexpected server-side errors.

```json
{
  "message": [
    {
      "message": "cURL error 7: Failed to connect to engine-service port 80 after 1 ms: Could not connect to server"
    }
  ]
}
```

---

## Rate Limiting

- Maximum **10 requests per minute per IP**.
- Exceeding the limit results in **HTTP 429**.

---

## Typical Usage Flow

1. Request wallet balances.
2. Check the <code>available</code> balance for the required currency.
3. Decide whether an operation is possible (withdrawal, exchange, payout).
4. Proceed with the transactional API (e.g. Withdrawal API).
