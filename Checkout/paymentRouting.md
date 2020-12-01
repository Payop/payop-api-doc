 * [Back to contents](../Readme.md#contents)

# Payment Routing

* [Intro](#intro)
* [Enable payment routing](#enable-payment-routing)
* [Payment routing usage](#payment-routing-usage)

## Intro

**Payment routing** - it's a simple way to decrease the number of declined transactions
and at the same time increase transactions revenue.

**How does it work?**

**Payment routing** uses its internal algorithms and tries to find the most efficient channel to make transaction successful.
 
Intelligent logic implementation takes into account the previously known facts and based on them
tries to make the best decision about where to send the payment, to be settled quickly and successfully.

## Enable payment routing

To enable payment routing for you application, please concat [Payop support](https://payop.com/en/contact-us)

## Payment routing usage

If your application is integrated using [Hosted Page](../Integration/hostedPage.md) integration,
you just need to [enable payment routing](#enable-payment-routing) and it will start working automatically,
right after that. No additional actions are not required for this.

If your application is integrated using [Server-To-Server](../Integration/serverToServer.md) integration, then you need:

1. [Enable payment routing](#enable-payment-routing)
2. Make integration follow the documentation described on the [corresponding page](../Integration/serverToServer.md).
