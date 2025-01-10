# Sovendus Voucher Network & Checkout Benefits Module for Magento

Official Sovendus Voucher Network & Checkout Benefits Plugin for Magento

## Install through Magento App Store

coming soon...

## Manual Installation on Magento

1. Download the latest version [from here](https://raw.githubusercontent.com/Sovendus-GmbH/Sovendus-Magento-Voucher-Network-and-Checkout-Benefits-Plugin/main/releases/sovendus-magento-latest.zip)
2. Navigate to your magento root folder
3. Unpack the downloaded module zip file into your magento root folder
4. After you've unpacked it, the folder structer should look like this: yourMagentoRoot/app/code/Sovendus/SovendusVoucherNetwork
5. In your magento root folder, execute the following command, if you haven't already, this will prompt you to enter your [Adobe access keys](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/prerequisites/authentication-keys.html): \
   `composer update`
6. In your magento root folder, execute the following command, to install the plugin: \
   `php bin/magento setup:upgrade`

## Setup the Sovendus Plugin

1. Go to your Magento shop admin dashboard
2. In the menu on the left, click on Stores -> Configuration -> Sovendus
3. Enter the traffic source number and traffic medium number, you have received in your integration documentation, for each country and enable them. Once saved, the sovendus banner will be visible on the post checkout page
