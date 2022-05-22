<?php
/**
 * Template to display product selection fields in a table (with thumbnail etc.)
 *
 * @package WooCommerce-One-Page-Checkout/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// add the user country
$user_country = strtolower(apply_filters('get_wc_user_country', ''));

?>
<table class="shop_table <?php echo $user_country; ?>" cellspacing="0">
	<?php foreach( $products as $product ) : ?>

	<?php 

		// do not show the product if it is not purchaseable
		// if(!$product->is_purchasable()) {
		// 	continue;
		// }
		// CUSTOM 

		// See if we should show this product or not based on user role
		$current_user_roles    = alg_wc_pvbur_get_current_user_all_roles();
		$show_current_product = alg_wc_pvbur_is_visible($current_user_roles, $product->get_id());
		if(!$show_current_product) continue;

		// variables from the country specific WooCommerce plugin
		$country_restrictions = get_post_meta($product->get_id(), '_fz_country_restriction_type', true);
		$countries_restricted_class = '';
		$countries_restricted_data = 'none';
		if($country_restrictions == 'specific') {
			$countries_restricted = get_post_meta($product->get_id(), '_restricted_countries', true);
			$countries_restricted_class = 'restricted ' . strtolower(implode(' ', $countries_restricted));
			$countries_restricted_data = strtolower(implode(',', $countries_restricted));
		}
		// END CUSTOM
	?>

	<tr class="product-item cart <?php if ( wcopc_get_products_prop( $product, 'in_cart' ) ) echo 'selected'; echo $countries_restricted_class; ?>" data-restricted_countries="<?php echo $countries_restricted_data; ?>">

		<td class="product-thumbnail">
			<a href="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" data-rel="prettyPhoto[<?php echo $product->get_id(); ?>]"  itemprop="image" class="woocommerce-main-image zoom">
				<?php echo $product->get_image(); ?>
			</a>

			<?php
			$attachment_ids = $product->get_gallery_image_ids();
			echo '<div class="wcopc-gallery">';
			foreach( $attachment_ids as $attachment_id ) 
				{
				// Display the image URL
				  $original_image_url = wp_get_attachment_url( $attachment_id );

				echo '<a href="' . $original_image_url . '" data-rel="prettyPhoto[' . $product->get_id() . ']">';
				// Display Image instead of URL
				echo wp_get_attachment_image($attachment_id, 'full');
					echo '</a>';
				}
			echo '</div>';
			?>

		</td>

		<td class="product-name">
			<?php $product_url = $product->get_permalink(); ?>
			<?php echo '<a href="' . $product_url . '">' . $product->get_title() . '</a>'; ?>
			<?php if ( $product->is_type( 'variation' ) ) : ?>
				<?php $attribute_string = sprintf( '&nbsp;(%s)', wc_get_formatted_variation( $product->get_variation_attributes(), true ) ); ?>
			<span class="attributes"><?php echo esc_html( apply_filters( 'wcopc_attributes', $attribute_string, $product->get_variation_attributes(), $product ) ); ?></span>
			<?php else : ?>
				<?php $attributes = $product->get_attributes(); ?>
				<?php foreach ( $attributes as $attribute ) : ?>
					<?php $attribute_string = sprintf( '&nbsp;(%s)', $product->get_attribute( $attribute['name'] ) ); ?>
			<span class="attributes"><?php echo esc_html( apply_filters( 'wcopc_attributes', $attribute_string, $attribute, $product ) ); ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
		</td>

		<td class="product-price">
			<span itemprop="price" class="price"><?php echo $product->get_price_html(); ?></span>
		</td>

		<td class="product-quantity">
			<?php wc_get_template( 'checkout/add-to-cart/opc.php', array( 'product' => $product ), '', PP_One_Page_Checkout::$template_path ); ?>
		</td>
		<td class="restriction-notice">
			<p>This product is not available in your chosen country.</p>
		</td>
	</tr>
	<?php endforeach; // end of the loop. ?>

</table>
