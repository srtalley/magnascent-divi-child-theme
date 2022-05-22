<?php
/**
 * Product quantity input
 *
 * Extends the WooCommerce quantity input template to include the add_to_cart data attribute.
 *
 * @package WooCommerce-One-Page-Checkout/Templates
 * @version 1.7.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$cart_item = wcopc_get_products_prop( $product, 'cart_item' );
$cart_quantity = ! empty( $cart_item ) ? $cart_item['quantity'] : 0;
// $input_button = woocommerce_quantity_input( array(
// 	'input_name'  => 'product_id',
// 	'input_value' => $cart_quantity,
// 	'max_value'   => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
// 	'min_value'   => 0,
// ), $product, false );
// echo str_replace( 'type="number"', 'type="number" data-add_to_cart="' . $product->get_id() . '" data-cart_quantity="' . $cart_quantity . '"', $input_button ); 
// get min/max amounts
$min_max_enabled = get_post_meta($product->get_id(), '_wc_mmax_prd_opt_enable', true);
if($min_max_enabled) {
	$minQty = get_post_meta($product->get_id(), '_wc_mmax_min', true);
	$maxQty = get_post_meta($product->get_id(), '_wc_mmax_max', true);
} else {
	$minQty = 0;
	$maxQty = 1000;
}

?>
<!-- <div class="add-to-cart"><button class="single_add_to_cart_button button alt">Add to Cart</button></div> -->
<div class="quantity buttons_added">
	<input type="button" value="-" class="minus" />
<input type="number" step="1" min="<?php echo $minQty;?>" max="<?php echo $maxQty;?>" name="product_id" value="<?php echo $cart_quantity; ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'bridge' ) ?>" class="input-text qty text" size="4" inputmode="numeric" data-add_to_cart="<?php echo $product->get_id(); ?>" data-cart_quantity="<?php echo $cart_quantity; ?>" />

	<input type="button" value="+" class="plus" />
</div>