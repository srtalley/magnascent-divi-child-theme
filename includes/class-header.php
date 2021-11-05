<?php
namespace Magnascent\Theme;

class Header {

    public function __construct() {
        add_shortcode ('mg_woo_cart_button', array($this, 'mg_woo_cart_button') );
        add_filter( 'wp_nav_menu_primary_items', array($this, 'mg_woo_cart_button_icon'), 10, 2 ); // Change menu to suit - example uses 'top-menu'
        // add_filter( 'wp_nav_menu_items', array($this, 'mg_woo_cart_button_icon'), 10, 2 ); // Change menu to suit - example uses 'top-menu'

        add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'mg_woo_cart_button_count') );
    }

    /**
     * Create Shortcode for WooCommerce Cart Menu Item
     */
    public function mg_woo_cart_button() {
        ob_start();
     
            $cart_count = WC()->cart->cart_contents_count; // Set variable for cart item count
            $cart_url = wc_get_cart_url();  // Set Cart URL
      
            ?>
            <div class="mg-woo-cart-items"><a class="menu-item cart-contents" href="<?php echo $cart_url; ?>" title="My Basket">
            <?php
            if ( $cart_count > 0 ) {
           ?>
                <span class="cart-contents-count"><?php echo $cart_count; ?></span>
            <?php
            }
            ?>
            </a></div>
            <?php
                
        return ob_get_clean();
     
    }
    /**
     * Add AJAX Shortcode when cart contents update
     */
    function mg_woo_cart_button_count( $fragments ) {
     
        ob_start();
        
        $cart_count = WC()->cart->cart_contents_count;
        $cart_url = wc_get_cart_url();
        
        ?>
        <a class="cart-contents menu-item" href="<?php echo $cart_url; ?>" title="<?php _e( 'View your shopping cart' ); ?>">
        <?php
        if ( $cart_count > 0 ) {
            ?>
            <span class="cart-contents-count"><?php echo $cart_count; ?></span>
            <?php            
        }
            ?></a>
        <?php
     
        $fragments['a.cart-contents'] = ob_get_clean();
         
        return $fragments;
    }

    /**
     * Add WooCommerce Cart Menu Item Shortcode to particular menu
     */
    public function mg_woo_cart_button_icon ( $items, $args ) {
        $items .= '<li class="mg-woo-cart-items-wrapper">' . do_shortcode('[mg_woo_cart_button]') . '</ul>'; // Adding the created Icon via the shortcode already created
        return $items;
    }
} // end class Header

$magnascent_header = new Header();




