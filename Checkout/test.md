```

## Checkout parameters:
* invoiceIdentifier - required
* payCurrency - required (can be taken from invoice)
* defaultCurrency - required (can be taken from invoice)
* customerData - required (can be taken from invoice)
* paymentMethod - required (can be taken from invoice)
* geoInformation - required (at least country required for several connectors). Maybe require to allow to add customer[country] to request
* customer - several fields required. Email required always
* cardToken - [required] if payment method from_type==cards

