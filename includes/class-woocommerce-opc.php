<?php
namespace Magnascent\WooCommerce;

class OPC {

    public function __construct() {
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
} // end class OPC

$magnascent_woocommerce_opc = new OPC();




