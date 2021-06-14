* [Back to contents](../Readme.md#contents)

# Hosted page

This is quite simple type of integration.

It is suitable for those who do not have enough resources for development.
The control over almost the entire process will be on the side of Payop.

## Minimal Checkout Flow

1. [Create Invoice](../Invoice/createInvoice.md)

1. **Redirect payer to invoice preprocessing page**
    
    In order to make checkout you should firstly redirect payer to the invoice preprocessing page.
    This page has builtin functionality to make decision about next processes.
    
    Invoice preprocessing page url: `https://checkout.payop.com/{{locale}}/payment/invoice-preprocessing/{{invoiceId}}`
    
    **Parameters**
    
    Parameter        |  Type   |                 Description     |
    -----------------|---------|---------------------------------|
    {{locale}}       | string  | Invoice language                |
    {{invoiceId}}    | string  | Invoice identifier              |
    
    If all the required fields are completed, an attempt will be made to pay by the payment method specified when creating the invoice.
    
    If you missed any of the required fields specified in the properties of the payment method,
    or did not specify a payment method, then payer will be redirected to checkout form.
    
    Also, if payer needs to enter a card number or fill out a 3DS form to confirm cards payment, he/she will be redirected to the appropriate forms.
    
    In case of successful payment, payer will be redirected to the URL
    specified in `resultUrl` when creating the invoice. 
    In case of error or inability to make payment payer will be redirected to `failPath` accordingly.

1. [Receive IPN (instant payment notification)](../Checkout/ipn.md) - in case if transaction was successfully created.
