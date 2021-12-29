 * [Back to contents](../Readme.md#contents)

# Server-To-Server integration

This type of integration allows to interact with Payop API 
and control almost each step of the payment.                                                       
                                                                                                
It is suitable for those who have enough resources for development                       
and at the same time want have more controls of the payment.

----
**Notes:** 
* You can see example of Server-To-Server 
integration in the [Checkout Demo App repository](https://github.com/Payop/checkout-demo-app).
* Server-To-Server integration can only be used for card methods with ID 381, 480, 481. You can view the list of available methods for the project using the request [getPaymentMethod](../Invoice/getPaymentMethods.md).
----

## Checkout Flow

1. [Create invoice](../Invoice/createInvoice.md) 

1. **Make decision on the next step**      

    When invoice was created, you can decide what to do next.
    
    Here are two cases possible, depending on the payment method, specified when creating invoice:
    
    * if **`paymentMethod.formType` is `cards`** - you need to [create card token](../Checkout/createCardToken.md) firstly.
    * if **`paymentMethod.formType` is not `cards`** - you can [create checkout transaction](../Checkout/createCheckoutTransaction.md).

1. [Check invoice status](../Checkout/checkInvoiceStatus.md) and decide what to do next.     
1. [Receive IPN](../Checkout/ipn.md).      
