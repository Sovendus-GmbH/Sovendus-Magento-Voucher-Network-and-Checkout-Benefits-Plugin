# Sovendus Voucher Network & Checkout Benefits Module for Magento

Official Sovendus App for Magento

## Disclaimer

This plugin is released as open source under the GPL v3 license. We welcome bug reports and pull requests from the community. However, please note that the plugin is provided "as is" without any warranties or guarantees. It may not be compatible with all other plugins and could potentially cause issues with your store. We strongly recommend that you test the plugin thoroughly in a staging environment before deploying it to a live site. Furthermore, we do not promise future support or updates and reserve the right to discontinue support for the plugin at any time.

## Install through Magento App Store

coming soon...

## Manual Installation on Magento

1. Download the latest version [from here](https://raw.githubusercontent.com/Sovendus-GmbH/Sovendus-Magento-Voucher-Network-and-Checkout-Benefits-Plugin/main/releases/sovendus_app_magento_latest.zip)
2. Navigate to your Magento root folder
3. Unpack the downloaded module zip file into yourMagentoRoot/app/code/Sovendus
4. After you've unpacked it, the folder structure should look like this: yourMagentoRoot/app/code/Sovendus/SovendusApp
5. In your Magento root folder, execute the following command, if you haven't already, this will prompt you to enter your [Adobe access keys](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/prerequisites/authentication-keys.html): \
   `composer update`
6. In your magento root folder, execute the following command, to install the plugin: \
   `php bin/magento setup:upgrade`

> [!WARNING]
> If you are upgrading from version 1.x.x, you must uninstall the previous version of the plugin before updating. Also make sure to note down the settings before updating, as you have to configure the plugin again.

## Setup the Sovendus Plugin

1. Go to your Magento shop admin dashboard
2. In the menu on the left, click on Stores -> Configuration -> Sovendus App
3. Follow the instructions on the plugins settings page.
