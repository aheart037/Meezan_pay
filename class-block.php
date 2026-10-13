<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
final class Meezan_Bank_Blocks extends AbstractPaymentMethodType
{
    private $gateway;
    protected $name = 'meezanbank'; // your payment gateway name
    public function initialize()
    {
        $this->settings = get_option('woocommerce_meezanbank_settings', []);
        $this->gateway = new Class_Meezan_Bank();
    }
    public function is_active()
    {
       return $this->gateway->is_available();      
    }
    public function get_payment_method_script_handles()
    {
        wp_register_script(
            'meezanbank-blocks-integration',
            plugin_dir_url(__FILE__) . 'checkout.js',
            [
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
                'wp-html-entities',
                'wp-i18n',
            ],
            null,
            true
        );
        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations('meezanbank-blocks-integration');
        }
        return ['meezanbank-blocks-integration'];
    }
    public function get_payment_method_data()
    {
        return [
            'title' => $this->gateway->title,
            'description' => $this->gateway->description,
			'icon' => plugin_dir_url(__DIR__) . 'meezan/assets/logo.png'
		];
    }
}