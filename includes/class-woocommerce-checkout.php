<?php
namespace Magnascent\WooCommerce;

class Checkout {

    public function __construct() {
        add_action('woocommerce_checkout_before_order_review_heading', array($this, 'magnascent_woocommerce_checkout_before_order_review_heading'));

        add_action('woocommerce_checkout_after_order_review', array($this, 'magnascent_woocommerce_checkout_after_order_review'));
        add_filter( 'default_checkout_billing_country', array($this, 'ds_change_default_checkout_country') );


    }
 
    /**
     * Wrap the second column on the woocommerce checkout
     */
    function magnascent_woocommerce_checkout_before_order_review_heading() {
        echo '<div id="woocommmerce-checkout-review-wrapper">';
    }
    function magnascent_woocommerce_checkout_after_order_review() {
        echo '</div>';
    }


    /**
     * Change the default checkout country
     */
    
    function ds_change_default_checkout_country() {
        return 'US'; 
    }

   
} // end class Checkout

$magnascent_woocommerce_checkout = new Checkout();




