<?php
namespace Magnascent\WooCommerce;

class ProductRestriction {

    public function __construct() {
        add_action( 'wp_ajax_nopriv_mgnscnt_get_user_country', array($this, 'mgnscnt_get_user_country') );
        add_action( 'wp_ajax_mgnscnt_get_user_country', array($this, 'mgnscnt_get_user_country') );
        add_filter( 'get_wc_user_country', array($this, 'get_wc_user_country'), 10, 1 );
    }
    
     /**
     * Ajax function to get the current user country
     */
    public function mgnscnt_get_user_country() {
        $nonce_check = check_ajax_referer( 'mgnscnt_action_nonce', 'nonce' );

        $user_country = $this->get_wc_user_country();
        $return_arr = array(
            'user_country' => $user_country,
        );
    
        wp_send_json($return_arr);

    }
    
    /**
     * Get the current user country
     */
    public function get_wc_user_country() {
        $user_country = '';
        global $woocommerce;
        if( isset($woocommerce->customer) ){
            $shipping_country = $woocommerce->customer->get_shipping_country();
            $cookie_country = !empty($_COOKIE["country"]) ? sanitize_text_field($_COOKIE["country"]) : $shipping_country;
            if( isset($cookie_country) ){
                $user_country = $cookie_country;
                return $user_country;
            }
        }
		
		if( empty( $user_country )  ) {
			$geoloc = \WC_Geolocation::geolocate_ip();
			$cookie_country = !empty($_COOKIE["country"]) ? sanitize_text_field($_COOKIE["country"]) : $geoloc['country'];
			$user_country = $cookie_country;
			return $user_country;
		}

		// this only runs if the above two items did not work
		return 'US';
    }
} // end class ProductRestriction

$magnascent_woocommerce_product_restriction = new ProductRestriction();