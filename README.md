# ShipStation CustomStore Integration for SilverCommerce

Adds an XML feed endpoint and status update endpoint to
a SilverCommerce store to allow for "Custom Store" (CS)
integration with ShipStation. More info at:

https://help.shipstation.com/hc/en-us/articles/360025856192-Custom-Store-Development-Guide

## Installation

Install this module via composer:

    composer require silvercommerce/shipstation-customstore

## Configuration

ShipStation uses Basic Auth to access order data from the feed.
As the feed contains sensitive customer data, you should also
perform the following setup:

1. Ensure all traffic to the feed URLs use SSL
2. Add a URL key to your environmental config (covered below)

## Add a URL Key

Once installed, you will need to add a URL Key to your environmental config. This will add a string to your feed URL
that makes it a little harder to sniff.

Security through obscurity isn't an ideal solution. But adding a
custom url key as well as Basic Auth is a bit more sucure than
just one of these options.

To add a URL Key, just add the following to your environmental config:

    SHIPSTATION_ENDPOINT_KEY="XXXxxxXXXxxx"

## Adding a user account

In addition to the URL key above, you will also need to add a new
user account that ShipStation will use to login (via Basic Auth).

This module provides the following permission code that is
required in order to access the endpoint controller:

    SHIPSTATION_CS_AUTH

You will need to setup a new user account with the above
permission code, then add these details into the ShipStation
custom store:

https://help.shipstation.com/hc/en-us/articles/360025856192-Custom-Store-Development-Guide#UUID-685007d9-4cda-06f2-d2f6-011ab46805af


## Endpoint URL's

Once this module is installed, you should be able to access the feed via the following URL's

### Orders:

https://mydomain.com/shipstationcs/orders/[SHIPSTATION_ENDPOINT_KEY]

### Shipment Notifications

https://mydomain.com/shipstationcs/ship/[SHIPSTATION_ENDPOINT_KEY]