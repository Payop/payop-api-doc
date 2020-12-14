# Payop REST-like API Reference

Payop API is organized around [REST](http://en.wikipedia.org/wiki/Representational_State_Transfer).

Payop API has predictable resource-oriented URLs, accepts [JSON](http://www.json.org/) request bodies,
 returns [JSON](http://www.json.org/) responses, and uses standard HTTP response codes.

Each request to Payop API should have **Content-Type HTTP header** with `application/json` value.

## Contents

1. **API Response examples**

    Description of API's responses format, with examples.
    
    * [Successful response](Response/successResponse.md)
    * [Failed responses](Response/failResponse.md)
    
1. **Authentication**

    Authentication is required to get access to protected API actions.
    
    * [Bearer authentication](Authentication/bearerAuthentication.md)
    
1. **Integration types**

    There are currently 2 options for using the API.
    
    * [Hosted page](Integration/hostedPage.md) - very simple integration with showing Payop pages.
    * [Server-To-Server](Integration/serverToServer.md) - more hard integration using Payop API.
     
1. **Invoice**

    Invoice is a basic entity in each payment. When you start payment, you pay the invoice.
    Checkout transaction can be created only for invoice. 
    
    * [Payment methods](Invoice/getPaymentMethods.md)
    * [Create invoice](Invoice/createInvoice.md)
    * [Get invoice](Invoice/getInvoice.md)
   
1. **Checkout**    

    How to make payments.
   
    * [Card tokenization](Checkout/createCardToken.md)
    * [Create checkout transaction](Checkout/createCheckoutTransaction.md)
    * [Check payment status](Checkout/checkInvoiceStatus.md)
    * [Payment Routing](Checkout/paymentRouting.md)
    * [Capture Transaction](Checkout/captureTransaction.md)
    * [Void Transaction](Checkout/voidTransaction.md)
    * [IPN (instant payment notification)](Checkout/ipn.md)
    * [Get transaction](Checkout/getTransaction.md)
    
1. **Withdrawal**

    How to request a withdrawal.
    
    * [Prepare a request](Withdrawal/withdrawal.md)
    * [Payment methods supporting withdrawal](Withdrawal/paymentMethods.md)
    * [Create withdrawal request](Withdrawal/massWithdrawal.md)
    * [Get merchant's withdrawals](Withdrawal/getWithdrawalsList.md)
    * [Get concrete withdrawal details](Withdrawal/getWithdrawal.md)
    * [IPN (instant payment notification)](Withdrawal/withdrawalIpn.md)
   
1. **Refund**
    
    How to make refunds.
    
    * [Create refund](Refund/createRefund.md)
    * [Get merchant's refunds](Refund/getRefundsList.md)
    * [Get concrete refund details](Refund/getRefund.md)
    * [IPN (instant payment notification)](Refund/refundIpn.md)
