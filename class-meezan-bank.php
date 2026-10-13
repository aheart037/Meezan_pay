<?php


class Class_Meezan_Bank extends WC_Payment_Gateway


{


    function __construct()


    {


        $this->id = "class_meezan_bank";


        $this->method_title = __("Meezan Bank", 'meezanbank');


        $this->method_description = __("Meezan Bank Payment Method Plug-in for WooCommerce", 'meezanbank');


        $this->title = __("Meezan Bank", 'meezanbank');


        $dir = plugin_dir_url(__FILE__);


        $this->icon = apply_filters('woocommerce_gateway_icon', $dir . 'assets/meezan-logo.png');


        $this->options = array(


            '1' => __('Iframe', 'meezanbank'),


            '2' => __('Redirection', 'meezanbank')


        );


        $this->has_fields = TRUE;


        $this->init_form_fields();


        $this->init_settings();


        foreach ($this->settings as $setting_key => $value) {


            $this->$setting_key = $value;


        }


        if (is_admin()) {


            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(


                $this,


                'process_admin_options'


            ));


        }


        add_action('woocommerce_api_meezanresponse', array($this, 'meezanresponse'));


        add_action('woocommerce_receipt_class_meezan_bank', array(


            $this,


            'receipt_page'


        ));


    } // END OF FUNCTION construct


    public function init_form_fields()


    {


        $this->form_fields = [


            'enabled' => [


                'title'   => __('Enable/Disable', 'meezanbank'),


                'label'   => __('Enable this payment gateway', 'meezanbank'),


                'type'    => 'checkbox',


                'default' => 'yes',


            ],


            'api_url' => [


                'title' => __('Api Url', 'meezanbank'),


                'type' => 'text',


                'desc_tip' => __('Provided by Meezan', 'meezanbank'),


                'custom_attributes' => [


                    'required' => 'required',


                    'pattern' => 'https://.*',


                    'Title' => 'Only https accepted'


                ],


                'description' => __('Provided by Meezan.', 'meezanbank')


            ],


            'title' => [


                'title' => __('Payment Method Title', 'meezanbank'),


                'type' => 'text',


                'desc_tip' => __('Payment title the customer will see during the checkout process.', 'meezanbank'),


                'default' => __('Meezan Bank', 'meezanbank'),


                'description' => __('Payment title the customer will see during the checkout process.', 'meezanbank')


            ],


            'description' => [


                'title' => __('Description', 'meezanbank'),


                'type' => 'textarea',


                'css' => 'width:500px;',


                'default' => __('Do not stand in line. Pay your bills from any of your preferred channel - Absolutely free!', 'meezanbank'),


                'description' => __('This will appear on the checkout page with the Meezan Bank payment option.', 'meezanbank')


            ],


            'username' => [


                'title' => __('Username', 'meezanbank'),


                'type' => 'text',


                'desc_tip' => __('Provided by Meezan', 'meezanbank'),


                'custom_attributes' => [


                    'required' => 'required'


                ],


                'description' => __('Provided by Meezan.', 'meezanbank')


            ],


            'password' => [


                'title' => __('Password', 'meezanbank'),


                'type' => 'text',


                'desc_tip' => __('Provided by Meezan', 'meezanbank'),


                'custom_attributes' => [


                    'required' => 'required'


                ],


                'description' => __('Provided by Meezan.', 'meezanbank')


            ],


            'returnURL' => [


                'title' => __('Return Url', 'meezanbank'),


                'type' => 'text',


                'desc_tip' => __('Merchant Url for returning Transactions', 'meezanbank'),


                'custom_attributes' => [


                    'required' => 'required'


                ],


                'description' => __('https://ip:port/wordpressdirectory/wp-content/plugins/meezan/meezanreturn.php', 'meezanbank')


            ],


            'checkout_type' => [


                'title' => __('Checkout Type', 'meezanbank'),


                'type' => 'select',


                'options' => $this->options,


                'default' => '2'


            ],


            // Add more settings fields as needed


        ];


    } // END OF FUNCTION init_form_fields


    function receipt_page($order)


    {


        echo $this->generate_meezanbank_form($order);


    }


    public function validate_fields()


    {


        return true;


        exit();


    } // END OF FUNCTION validate_fields


    public function generate_meezanbank_form($order_id)


    {


        global $woocommerce;


        $customer_order = new WC_Order($order_id);


        $_ApiUrl = esc_html($this->api_url);


        $_Username = esc_html($this->username);


        $_Password = esc_html($this->password);


        $_ReturnURL = esc_html($this->returnURL);


        $checkout_type = esc_html($this->checkout_type);


        $_OrderNumber = $customer_order->get_order_number();


        $_AmountTmp = $customer_order->order_total * 100;


        $_AmtSplitArray = explode('.', $_AmountTmp);


        $_FormattedAmount = $_AmtSplitArray[0];


        $url = $_ApiUrl . '/register.do?password=' . $_Password . '&userName=' . $_Username . '&amount=' . $_FormattedAmount . '&currency=586&orderNumber=' . (int) $_OrderNumber . '&returnUrl=' . $_ReturnURL;


        $args = array(


            'headers' => array(


                'Content-Type' => 'application/json',


                "Content-Length: 0"


            )


        );


        $resp = wp_remote_get($url, $args);


        $response = json_decode(wp_remote_retrieve_body($resp), true);


        $payment_method = $customer_order->get_payment_method();


        if(($payment_method === 'class_meezan_bank') && ($response === null)) {
            echo "<span style='color:red!important;font-size:14px!important;margin-top:20px!important;margin-bottom:20px!important;'>Meezan Bank Payment Gateway is not responding. Please try again later or contact to Meezan Bank.</span>";
            exit();
        }

        WC()->session->set('formid', $response['orderId']);


        WC()->session->set('ordernumber', $_OrderNumber);


        WC()->session->set('api_responce', wp_remote_retrieve_body($resp));


        WC()->session->set('cart_order_id', $order_id);


        if ($response['errorCode'] > 0) {


            $form = $response['errorMessage'];


            return $form;


        } else {


            if ($checkout_type == "1") {


                $form = '<iframe src="' . $response['formUrl'] . '" id="frame" name="frame" width="900" height="700" scrolling="NO" frameBorder="0"></iframe>';


                echo  $form;


            } else {


                header('location:' . $response['formUrl']);


                exit();


            }


        }


        exit();


    } // END OF FUNCTION generate_meezanbank_form


    public function process_payment($order_id)


    {


        global $woocommerce;


        $order = new WC_Order($order_id);


        return array(


            'result' => 'success',


            'redirect' => $order->get_checkout_payment_url(TRUE)


        );


    } // END OF FUNCTION process_payment


    public function meezanresponse()


    {


        try {


            $responceApi = json_decode(WC()->session->get('api_responce'), true);


            $api_id_order = $responceApi['orderId'];


            $_ApiUrl = esc_html($this->api_url);


            $_Username = esc_html($this->username);


            $_Password = esc_html($this->password);


            if (function_exists('WC') && WC()->session) {


                $Orderid = WC()->session->get('cart_order_id', 'Default value if not set');


            }


            if ($api_id_order != "") {


                $url = "$_ApiUrl/getOrderStatus.do?password=$_Password&userName=$_Username&orderId=$api_id_order";


                $args = array(


                    'headers' => array(


                        'Content-Type' => 'application/json',


                        "Content-Length: 0"


                    )


                );


                $resp = wp_remote_get($url, $args);


                $response = json_decode(wp_remote_retrieve_body($resp), true);


                $order = wc_get_order($Orderid);


                $custom_order_status = '1'; // for case:success test


                if ($response['OrderStatus'] == 2) {


                    $order->update_status('wc-completed', 'Payment received, your order is currently being processed.');


                    $mailer = WC()->mailer();


                    $mails = $mailer->get_emails();


                    if (!empty($mails)) {


                        foreach ($mails as $mail) {


                            if ($mail->id == 'customer_completed_order') {


                                $mail->trigger($order->id);


                            }


                        }


                    }


                    WC()->cart->empty_cart();


                    wp_safe_redirect($order->get_checkout_order_received_url());


                    exit;


                } else if ($response['OrderStatus'] == 6) {


                    $order = wc_get_order($Orderid);


                    $order->update_status('wc-cancelled', 'Order cancelled.');


                    wc_increase_stock_levels($order->get_id());


                    $wc_emails = WC()->mailer()->get_emails();


                    $customer_email = $order->get_billing_email();


                    $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;


                    $wc_emails['WC_Email_Cancelled_Order']->trigger($order->id);


                    WC()->cart->empty_cart();


                    wp_safe_redirect($order->get_checkout_order_received_url());


                    exit;


                } else {


                    $order->update_status('wc-cancelled', 'Order cancelled.');


                    wc_increase_stock_levels($order->get_id());


                    $wc_emails = WC()->mailer()->get_emails();


                    $customer_email = $order->get_billing_email();


                    $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;


                    $wc_emails['WC_Email_Cancelled_Order']->trigger($order->id);


                    WC()->cart->empty_cart();


                    wp_safe_redirect($order->get_checkout_order_received_url());


                    exit;


                }


            } else {


                wp_redirect("cart");


                exit;


            }


        } catch (Exception $e) {


            wc_add_notice($e->getMessage(), 'error');


            wp_safe_redirect(wc_get_checkout_url());


            exit;


        } finally {


            //unset($_SESSION['formid'], $_SESSION['ordernumber']);


        }


    } // END OF FUNCTION meezanresponse


    protected function complete_order($order, $posted_data)


    {


        $approval_code = get_value('approval_code', $posted_data);


        $order->payment_complete();


        $this->woocommerce()->cart->empty_cart();


    } // END OF FUNCTION complete_order


    function showMessage($content)


    {


        return '<div class="box ' . $this->msg['class'] . '-box" >' . $this->msg['message'] . '</div>' . $content;


    } // END OF FUNCTION showMessage


} // END OF CLASS MeezanBank


add_action('woocommerce_checkout_update_order_meta', 'mz_bank_payment_update_order_meta');


function mz_bank_payment_update_order_meta($order_id)


{


    if ($_POST['payment_method'] != 'meezanbank')


        return;


} // END OF FUNCTION mz_bank_payment_update_order_meta


add_action('woocommerce_admin_order_data_after_billing_address', 'mz_bank_checkout_field_display_admin_order_meta', 10, 1);


function mz_bank_checkout_field_display_admin_order_meta($order)


{


    $method = get_post_meta($order->get_id(), '_payment_method', TRUE);


    if ($method != 'meezanbank')


        return;


    $TxnType_show = 'Meezan Bank';


    echo '<p><strong>' . __('Transaction Type') . ':</strong> ' . $TxnType_show . '</p>';


} // END OF FUNCTION mz_bank_checkout_field_display_admin_order_meta


add_filter('the_title', 'woo_title_order_received_bank', 10, 2);


function woo_title_order_received_bank($title, $id)


{


    if (function_exists('is_order_received_page') && is_order_received_page() && get_the_ID() === $id) {


        $title = "";


    }


    return $title;


} // END OF FUNCTION woo_title_order_received_bank


add_filter('woocommerce_thankyou_order_received_text', 'wpb_meezanbank_thankyou', 10, 2);


function wpb_meezanbank_thankyou($thankyoutext, $order)


{


    $order_status = $order->get_status();


    $succesCase = 'Thanks, Your Order has been Processed.';


    $failureCase = 'Sorry! Your Order has been failed.';


    $pendingCase = "Your Order's payment is pending.";


    if ($order_status == 'completed') {


        $order_message = '<span style="color:green;" >' . esc_html($succesCase) . '</span>';


        $note = __("Payment Success via Meezan Bank");


        $order->set_customer_note($note);


        $order->save();


    } else if ($order_status == 'cancelled') {


        $order_message = '<span style="color:red;" >' . esc_html($failureCase) . '</span>';


        $note = __("Payment Failed via Meezan Bank");


        $order->set_customer_note($note);


        $order->save();


    } else {


        $order_message = '<span style="color:grey;" >' . esc_html($pendingCase) . '</span>';


        $note = __("Payment pending via Meezan Bank");


        $order->set_customer_note($note);


        $order->save();


    }


    $added_text = $order_message;


    return $added_text;


} // END OF FUNCTION wpb_meezanbank_thankyou


add_filter('woocommerce_email_headers', 'woo_order_completed_email_add_cc_bcc', 9999, 3);


function woo_order_completed_email_add_cc_bcc($headers, $email_id, $order)


{


    $wc_emails = WC()->mailer()->get_emails();


    if ('customer_completed_order' == $email_id) {


        $headers .= "Cc: <" . $wc_emails['WC_Email_New_Order']->recipient . ">\r\n"; // Admin Configured Email


    } else {


        $headers .= "Cc: <" . $wc_emails['WC_Email_New_Order']->recipient . ">\r\n"; // Admin Configured Email


    }


    return $headers;


} // END OF FUNCTION woo_order_completed_email_add_cc_bcc