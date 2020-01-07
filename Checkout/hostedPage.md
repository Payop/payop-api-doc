* [Hosted page](#hosted-page)
    * [Minimal Checkout Flow](#minimal-checkout-flow)
    * [Redirect user to invoice preprocessing page](#redirect-user-to-invoice-preprocessing-page)

# Hosted page

This is quite simple type of integration.

It is suitable for those who do not have enough resources for development
and at the same time the control over almost the entire process will be on the side of Payop.

### Minimal Checkout Flow

1. [Create Invoice](../Invoice/createInvoice.md)
1. [Redirect user to invoice preprocessing page](#redirect-user-to-invoice-preprocessing-page)
1. [Receive IPN (instant payment notification)](checkout.md#ipn) - in case of transaction was successfully created.

### Redirect user to invoice preprocessing page

After getting invoice id you can redirect user to the invoice preprocessing page.
This page has builtin functionality to make decision about next processes.

**Invoice preprocessing page url**:  https://payop.com/{{locale}}/payment/invoice-preprocessing/{{invoiceId}}

**Parameters**

Parameter        |  Type   |                 Description     |
-----------------|---------|---------------------------------|
{{locale}}     | string  | Invoice language                |
{{invoiceId}}     | string  | Invoice identifier              |


If all the required fields are completed, an attempt will be made to pay by the payment method specified when creating the account.

If you missed any of the required fields specified in the properties of the payment method,
or did not specify a payment method, then you will be redirected to checkout form.

Also, if you need to enter a card number or fill out a 3DS form to confirm cards payment, you will be redirected to the appropriate forms.

In case of successful payment, you will be redirected to the URL
specified in **resultUrl** when creating the invoice. 
In case of error or inability to make payment to **failPath** accordingly.


