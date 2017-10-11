# Getting Started

## Install Plugin
1. Download this repository ZIP (**Clone or Download** > **Download ZIP**)
1. Login & Navigate to your WordPress Dashboard
1. Go to Plugins > Add New > Upload Plugin
1. Upload the Xendit Gateway ZIP file
1. Click `Activate Plugin`

## Configure Xendit
Go to WooCommerce > Settings > Checkout > Xendit
1. Tick `Enable xendit`
1. Tick `Enable Test Mode` to do testing first
1. Set the 4 API Keys from your [Xendit Dashboard](https://dashboard.xendit.co/dashboard/settings/developer)
1. Click `Save changes`

## Configure WooCommerce
Go to WooCommerce > Settings > General > Currency options
1. Set **Currency** to `Indonesian rupiah (Rp)`
1. Set **Number of decimals** to `0`

# Testing
1. Test [Authentication Cases](https://dashboard.xendit.co/docs/testing-payments#cards-authentication):
    + **3DS Passed**: 4000000000000002
    + **3DS Failed**: 4000000000000010
    + **3DS Not Enrolled**: 5200000000000056

1. Test [Charge Failure Reasons](https://dashboard.xendit.co/docs/testing-payments#cards-authorization). Adding test products with these exact prices can make it easier to test these cases:
    + **SUCCESS**: Rp 10000
    + **CARD_DECLINED**: Rp 5001
    + **INSUFFICIENT_BALANCE**: Rp 5004
    + **INACTIVE_CARD**: Rp 5006
    
1. If using Subscriptions, we recommend following instructions in the WooCommerce [Testing Subscription Renewal Payments](https://docs.woocommerce.com/document/testing-subscription-renewal-payments/) guide.

# Updating
1. Deactivate the plugin
1. Delete the plugin
1. Follow instructions for [Installing](#installing).

Note: Your settings will remain saved
