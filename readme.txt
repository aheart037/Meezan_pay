=== WooCommerce Meezan Bank Gateway Plugin ===



Contributors: AKSA, Meezan Bank



Donate link: https://www.aksa-sds.com



Tags: woocommerce, payment gateway, woocommerce extension, Meezan Bank payment, payment, payment option, custom payment



Requires at least: 6.8.0



Tested up to: 6.8.2



Stable tag: 1.0.2



Requires PHP: 8.2.x



WC requires at least: 10.0.0



WC tested up to: 10.1.2



License: GPLv3



License URI: http://www.gnu.org/licenses/gpl-3.0.html




Allows WooCommerce to accept payments using Meezan Bank gateway.




 == Upgrade Notice ==




This is an upgrade of WooCommerce Meezan Bank Gateway Plugin version 2.1.0




API Base order registration and getOrderStatus on response




Compatible with both (classic & blocked) checkout





== Description ==





> **Support policy**




>




> * Should you need assistance, please open a support request in the **[Support section, above](https://wordpress.org/support/plugin/woocommerce-jazzcash-gateway)**, and we will look into it as soon as possible (usually within a couple of days).




> * If you need support urgently, or you require a customisation, you can avail of our paid support and consultancy services. To do so, please contact us [using our dedicated portal](https://www.meezanbank.com/), specifying that you are using our WooCommerce Meezan Bank plugin. You will receive direct assistance from our team, who will troubleshoot your site and help you to make it work smoothly. We can also help you with installation, customisation and development of new features.




The plugin will add a new payment gateway called Meezan Bank, which will add support for the Meezan Bank payment gateway. Upon checkout, your customers will be redirected to a secure portal where they can complete their payment or show iframe which will complete payment process. Both standard and Quick Checkout modes are supported.




= IMPORTANT =




**Make sure that you read and understand the plugin requirements and the FAQ before installing this plugin**. Almost all support requests we receive are related to missing requirements, and incomplete reading of the message that is displayed when such requirements are not met.




= Included localisations =




* English (GB)




= Requirements =




* A JazzCash Merchant account. The plugin was not tested with personal accounts and might not work correctly with them.




* WordPress 6.8.2




* PHP 8.2.4 or greater




* WooCommerce  10.1.2




= Current limitations =




* Plugin does not yet support pre-authorisation or subscriptions.




= Notes =




* This plugin is provided as a **free** alternative to the many commercial plugins that add the Meezan Bank payment gateway to WooCommerce, and it's not automatically covered by free support. See FAQ for more details.




== Installation ==




1. Extract the zip file and drop the contents in the ```wp-content/plugins/``` directory of your WordPress installation.




2. Activate the plugin through the **Plugins** menu in WordPress.




3. Go to ```WooCommerce > Settings > Payment Gateways > Meezan Bank``` to configure the plugin.




For more information about installation and management of plugins, please refer to [WordPress documentation](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins).




= Setup =




On the settings page, the following settings are required:




* **Username**: this is the ID associated to your Meezan Bank merchant account.




* **Password**: this is the password of your Meezan Bank account.




* **APIUrl**: this is an API url provided by Meezan Bank support.




* **ReturnUrl**: http://ip:port/wordpress_directory/wp-content/plugins/meezan/meezanreturn.php.




* **CheckOut**: edit checkout page through admin and remove all code and paste '[woocommerce_checkout]' for classic, for blockbase you need to select block.




If you wish to get more details about Meezan Bank, please refer to [Meezan Bank website](https://www.meezanbank.com/).




== Screenshots ==




1. Checkout Page Preview.




2. Payment Gateway Settings Page.




== Changelog ==




= 1.0.0 =




* Initial release.




== Upgrade Notice == 




* Initial release.




== Frequently Asked Questions == 




How do I add a FAQ in WooCommerce?




Integration Steps




There is steps in integrating Meezan Bank Payment module as listed below:




1.Installation




2.Configuration




The first step in integration is installing the module. Next is to Activate it and then Configure it with the configuration data provided by Meezan Bank.