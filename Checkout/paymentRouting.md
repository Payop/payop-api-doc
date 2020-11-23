* [Payment Routing](#payment-routing)
    * [Enable payment routing](#enable-payment-routing)
    * [Redirect user to invoice preprocessing page](#redirect-user-to-invoice-preprocessing-page)



# Payment Routing

**Payment routing** - it's a simple way to decrease the number of declined transactions
and at the same time increase transactions revenue.

**How does it work?**

**Payment routing** using its internal algorithms, trying to find the most efficient channel to make transaction successful.
 
Intelligent logic implementation takes into account the previously known facts and based on it
tries to make the best decision about where to send the payment, to be settled quickly and successfully.

## Enable payment routing

To enable payment routing for you application, please concat [Payop support](https://payop.com/en/contact-us)

## Using payment routing

If your application integrated using [Hosted Page](hostedPage.md) integration,
you just need to [enable payment routing](#enable-payment-routing) and it will start working automatically,
right after that. Nothing special implementation doesn't require for this.

If your application integrated using [Server-To-Server](serverToServer.md) integration, then you need:

1. [Enable payment routing](#enable-payment-routing)
2. Make integration follow the documentation described on the [corresponding page](serverToServer.md).

