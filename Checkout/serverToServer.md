# Server-ToServer integration

This type of integration allow interact with Payop API 
and control almost each step of the payment.                                                       
                                                                                                
It is suitable for those who have enough resources for development                       
and at the same time want have more controls of the payment.

# Checkout Flow

1. [Create invoice](#create-invoice)      
1. [Make decision on the next step](#make-decision-on-the-next-step)      
1. [Create bank card token](#create-bank-card-token)      
1. [Create checkout transaction](#create-bank-card-token)      
1. [Check transaction status and decide where to go](#check-transaction-status-and-decide-where-to-go)      
1. [Receive IPN](#receive-ipn)      

## Create Invoice

It can be useful for you to get [Merchant payment methods](getMerchantPaymentMethods.md) on this step.

Follow the link to see how [create Invoice](../Invoice/createInvoice.md)


## Make decision on the next step

While was created invoice with selected payment method, you can decide what to do next.

There available a two cases, depends on selected payment method.

1. if **paymentMethod.formType is "cards"** - to create transaction, request require Card Token.
 So next step is - show card form, prepare and send request to [Card Token generation](createCardToken.md)
1. if **paymentMethod.formType is not "cards"** - you can make 
request to [create transaction](../Transaction/createCheckoutTransaction.md).

## Create Bank Card token

Follow the link to see how [Create Bank Card token](createCardToken.md)

## Create checkout transaction

Follow the link to see how [Create checkout transaction](../Transaction/createCheckoutTransaction.md)

## Check transaction status and decide where to go

Follow the link to see how [Check transaction status and decide where to go](checkTransactionStatus.md)

## Receive IPN

Follow the link to see how [receive and process IPN](checkout.md#ipn)