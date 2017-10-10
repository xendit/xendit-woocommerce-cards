## Prep
- Install WordPress, WooCommerce, and WooCommerce Subscriptions
- Add some simple & subscription products

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
1. Test [Authentication Cases](https://dashboard.xendit.co/docs/testing-payments#cards-authentication)
1. Test [Charge Failure Reasons](https://dashboard.xendit.co/docs/testing-payments#cards-authorization)

For easy testing of charge failure reasons, we recommend adding test products with these prices to your WooCommerce Products:
- SUCCESS: Rp 10000
- CARD_DECLINED: Rp 5001
- INSUFFICIENT_BALANCE: Rp 5004
- INACTIVE_CARD: Rp 5006

# Updating
1. Deactivate the plugin
1. Delete the plugin
1. Follow instructions for [Installing](#installing).

Note: Your settings will remain saved
