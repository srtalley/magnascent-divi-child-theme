<?php
namespace Magnascent\WooCommerce;

class SingleProduct {

    public function __construct() {
        /**
         * Remove related products output
         */
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


    }
 

} // end class SingleProduct

$magnascent_woocommerce_single_product = new SingleProduct();




