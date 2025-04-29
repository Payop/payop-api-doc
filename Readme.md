# Payop REST-like API Reference

Payop API is organized around [REST](http://en.wikipedia.org/wiki/Representational_State_Transfer).

Payop API has predictable resource-oriented URLs, accepts [JSON](http://www.json.org/) request bodies,
returns [JSON](http://www.json.org/) responses and uses standard HTTPS response codes.

Each request to Payop API should have a `Content-Type` HTTPS header with `application/json` value.
    
## Contents

### 0. Integration

* Integration via API 
  * [API Integration Types](0.Integration/integrationApiTypes.md)  
   * Pre-setup integration plugins  
      * [Woocommerce](https://github.com/Payop/woocommerce-plugin)  
          
* [Response examples](0.Integration/responses.md)  
* [Signature generation](0.Integration/signatureGenerator.md)  

### 1. Invoice

An invoice is the core element of any payment. Every payment is made against an invoice, and checkout transactions can only be initiated for existing invoices.

* [Invoice](1.Invoice/invoice.md)

   
### 2. Checkout    

 Handling Payments Through the Checkout Flow

 * [Checkout](2.Checkout/checkout.md)


### 3. Withdrawal

Steps to prepare and request a withdrawal.

* [Encrypt withdrawal data](3.Withdrawal/withdrawalEncrypt.md)
* [Send withdrawal](3.Withdrawal/withdrawal.md)
   
### 4. Refund
    
How to process and handle refunds.

* [Refund](4.Refund/refund.md)


### 5. IPN (Instant Payment Notification)

Learn to receive and handle Instant Payment Notifications. 

* [IPN](5.IPN/ipn.md)
