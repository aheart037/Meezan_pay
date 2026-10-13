<?php


/*


	Plugin Name: Meezan Bank


	Plugin URI: https://www.meezanbank.com/


	Description: Enjoy online shopping with Meezan Bank. WP_V: 6.8.2, WC_V: 10.1.2. Compatible with both (classic & block-base) checkout.


	Version: 1.0.2


	Author: AKSA-SDS Development Team


	Author URI: https://www.aksa-sds.com/


*/


// INCLUDE GATEWAY CLASS AND REGISTER PAYMENT GATEWAY WITH WOOCOMMERCE


add_action('plugins_loaded', 'woocommerce_myplugin', 0);


function woocommerce_myplugin()


{


    if (!class_exists('WC_Payment_Gateway'))


        return; // if the WC payment gateway class


    include(plugin_dir_path(__FILE__) . 'class-meezan-bank.php');


}


add_filter('woocommerce_payment_gateways', 'add_meezan_bank_gateway');


function add_meezan_bank_gateway($gateways)


{


    $gateways[] = 'Class_Meezan_Bank';


    return $gateways;


}


/**


 * Custom function to declare compatibility with cart_checkout_blocks feature 


 */


function declare_cart_checkout_blocks_compatibility()


{


    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {


        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);


    }


}


add_action('before_woocommerce_init', 'declare_cart_checkout_blocks_compatibility');


add_action('woocommerce_blocks_loaded', 'oawoo_register_order_approval_payment_method_type');


/**


 * Custom function to register a payment method type


*/


function oawoo_register_order_approval_payment_method_type()


{


    if (!class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {


        return;


    }


    require_once plugin_dir_path(__FILE__) . 'class-block.php';


    add_action(


        'woocommerce_blocks_payment_method_type_registration',


        function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry) {


            $payment_method_registry->register(new Meezan_Bank_Blocks);


        }


    );


}


add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'meezanbank_action_links');


function meezanbank_action_links($links)


{


    $plugin_links = array('<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=class_meezan_bank') . '">' . __('Settings', 'meezanbank') . '</a>');


    return array_merge($plugin_links, $links);


} // END OF FUNCTION meezanbank_action_links