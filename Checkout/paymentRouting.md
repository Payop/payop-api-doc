 * [Back to contents](../Readme.md#contents)

# Payment Routing

* [Intro](#intro)
* [Enable payment routing](#enable-payment-routing)
* [Payment routing usage](#payment-routing-usage)

## Intro

Essentially, **payment routing** is a way to decrease
the number of declined transactions and increase the transactions revenue/conversion.

**How does it work?**

**Payment routing** uses its internal algorithms and tries to find the most efficient channel to make transactions successful.

Intelligent logic implementation takes the previously known facts into account 
and tries to make the best decision about where to send the payment based on them.

## Enable payment routing

To enable payment routing for your application, please contact [Payop support](https://payop.com/en/contact-us)

## Payment routing usage

If your application is integrated using the [Hosted Page](../Integration/hostedPage.md) method, 
all you need to do is enable [payment routing](#enable-payment-routing) and it will start working 
automatically right away. No additional actions are required for this.

If your application is integrated using the [Server-To-Server](../Integration/serverToServer.md) method, you need to:

1. [Enable payment routing](#enable-payment-routing)
2. Make your integration follow the flow described on the [corresponding page](../Integration/serverToServer.md).