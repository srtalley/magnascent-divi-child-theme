<?php
namespace Magnascent\WooCommerce;

class OPC {

    public function __construct() {
        add_action('init', array($this, 'move_wcopc_messages'));

        add_filter('wp_robots', array($this, 'mg_maybe_enable_robots'), 100, 1);
    }
 
   
    /**
     * Remove noindex from the one page checkout page
     */
    function mg_maybe_enable_robots($robots) {
        if ( is_wcopc_checkout() ) {
            $robots['noindex'] = 0;
        }
        return $robots;
    }

    /**
     * Move the One page checkout messages area below the product fields
     */
    public function move_wcopc_messages() {
        remove_action( 'wcopc_product_selection_fields_before', array( 'PP_One_Page_Checkout', 'opc_messages' ), 10, 2 );
        add_action( 'wcopc_product_selection_fields_after', array( 'PP_One_Page_Checkout', 'opc_messages' ), 10, 2 );

    }
} // end class OPC

$magnascent_woocommerce_opc = new OPC();




