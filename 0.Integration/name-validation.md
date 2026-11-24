* [Back to contents](../Readme.md#contents)

# Name validation rules

The same validation rules apply to the three request fields below. Check the value before sending it to the API.

| Request field | Request | Related documentation |
|---------------|---------|-----------------------|
| `payer.name` | `POST /v1/invoices/create` | [Create invoice](../1.Invoice/invoice.md#1-create-invoice) · [S2S](s2s.md#2-create-invoice) · [Direct integration](integrationApiTypes.md#3-create-invoice) |
| `holderName` | `POST /v1/payment-tools/card-token/create` | [Create bank card token](s2s.md#3-create-bank-card-token) |
| `customer.name` | `POST /v1/checkout/create` | [Create checkout](../2.Checkout/checkout.md#1-create-checkout) · [S2S](s2s.md#4-create-checkout-transaction) · [Direct integration](integrationApiTypes.md#4-create-checkout-transaction) |

## Validation rules

1. **Value length:** from **2 to 26 characters inclusive**.
2. **First character:** must be a basic Latin letter (`A–Z`, `a–z`).
3. **Permitted characters:**
   * Basic Latin letters (`A–Z`, `a–z`).
   * Space.
   * Dot (`.`).
   * Apostrophe (`'`).
   * Hyphen (`-`).
4. **Not permitted:** numbers; extended Latin letters with diacritics (such as ć, ń, ó) and other diacritical marks; Cyrillic characters; any other special characters.
5. A value **cannot begin** with a space, dot, apostrophe or hyphen.

## Examples

| Valid value | Why it is valid |
|-------------|-----------------|
| `John Doe` | Basic Latin letters and a space. |
| `Anne-Marie` | A hyphen is permitted after the first character. |
| `O'Connor` | The basic apostrophe is permitted after the first character. |
| `J. Smith` | A dot and spaces are permitted after the first character. |

| Invalid value | Reason |
|---------------|--------|
| `J` | Fewer than 2 characters. |
| `ABCDEFGHIJKLMNOPQRSTUVWXYZQ` | 27 characters; the maximum is 26. |
| ` John Doe` | Begins with a space. |
| `-John` | Begins with a hyphen. |
| `José` | Contains a letter with a diacritic. |
| `Іван` | Contains Cyrillic letters. |
| `John2` | Contains a number. |
| `John_Doe` | Contains an underscore. |
| `O’Connor` | Contains a typographic apostrophe (`’`), rather than the permitted basic apostrophe (`'`). |

> **Conversion recommendation**
>
> We recommend that you take these validation rules into account, as they may have a positive impact on your conversion rate.

## Related integration steps

The validation rules are the same for all three fields. See the relevant request documentation for the other parameters and whether a field is required for your payment flow.

* [S2S: Create Invoice](s2s.md#2-create-invoice)
* [S2S: Create Bank Card Token](s2s.md#3-create-bank-card-token)
* [S2S: Create Checkout Transaction](s2s.md#4-create-checkout-transaction)
* [Invoice: Create Invoice](../1.Invoice/invoice.md#1-create-invoice)
* [Checkout: Create Checkout](../2.Checkout/checkout.md#1-create-checkout)
* [Direct Integration: Create Invoice](integrationApiTypes.md#3-create-invoice)
* [Direct Integration: Create Checkout Transaction](integrationApiTypes.md#4-create-checkout-transaction)
