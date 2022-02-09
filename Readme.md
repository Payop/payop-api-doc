# PayOp REST-like API Reference

PayOp API is organized around [REST](http://en.wikipedia.org/wiki/Representational_State_Transfer).

PayOp API has predictable resource-oriented URLs, accepts [JSON](http://www.json.org/) request bodies,
returns [JSON](http://www.json.org/) responses and uses standard HTTPS response codes.

Each request to PayOp API should have a `Content-Type` HTTPS header with `application/json` value.

## PayOp integration options
   * [Hosted page](https://github.com/Payop/payop-api-doc/blob/master/Integration/hostedPage.md) – a very simple integration option using default PayOp checkout pages
   * [Direct integration](https://github.com/Payop/payop-api-doc/blob/master/Integration/direct.md) – bypassing the PayOp hosted page
   * [Server-To-Server](https://github.com/Payop/payop-api-doc/blob/master/Integration/serverToServer.md) – integration using PayOp API for users having a PCI DSS certificate
   * Pre-setup integration plugins
      * [Woocommerce](https://github.com/Payop/woocommerce-plugin)

You can learn more about the difference between the above options here: [Difference description](https://github.com/Payop/payop-api-doc/blob/master/Integration/difference.md).

## Contents

### 1. Payment and withdrawal methods
   
With the help of these requests, you can get the methods available for payments and fund [withdrawals](Withdrawal/withdrawal.md).

* [Get available payment methods](Invoice/getPaymentMethods.md)
* [Get available withdrawal methods](Withdrawal/paymentMethods.md)
    
### 2. Authentication

Authentication is required to get access to the protected API actions.

* [Bearer authentication](Authentication/bearerAuthentication.md)
      
### 3. API Response examples
   
Description of API's response format with examples.
   
* [Successful response](Response/successResponse.md)
* [Failed responses](Response/failResponse.md)

### 4. Invoice

An invoice is a basic entity in each payment. When you make a payment, you pay an invoice. Checkout transactions can be created only for invoices. 
    
* [Payment methods](Invoice/getPaymentMethods.md)
* [Create invoice](Invoice/createInvoice.md)
* [Get invoice info](Invoice/getInvoice.md)
   
### 5. Checkout    

 How to make payments.

 * [Card tokenization](Checkout/createCardToken.md)
 * [Create checkout transaction](Checkout/createCheckoutTransaction.md)
 * [Check invoice status](Checkout/checkInvoiceStatus.md)
 * [Payment routing](Checkout/paymentRouting.md)
 * [Capture transaction](Checkout/captureTransaction.md)
 * [Void transaction](Checkout/voidTransaction.md)
 * [IPN (instant payment notification)](Checkout/ipn.md)
 * [Get transaction info](Checkout/getTransaction.md)

### 6. Withdrawal

How to request a withdrawal.

* [Prepare a request](Withdrawal/withdrawal.md)
* [Payment methods that support withdrawals](Withdrawal/paymentMethods.md)
* [Create a withdrawal request](Withdrawal/massWithdrawal.md)
* [Get merchant's withdrawals](Withdrawal/getWithdrawalsList.md)
* [Get withdrawal details](Withdrawal/getWithdrawal.md)
* [IPN (instant payment notification)](Withdrawal/withdrawalIpn.md)
   
### 7. Refund
    
How to make refunds.

* [Create refund](Refund/createRefund.md)
* [Get merchant's refunds](Refund/getRefundsList.md)
* [Get refund details](Refund/getRefund.md)
* [IPN (instant payment notification)](Refund/refundIpn.md)