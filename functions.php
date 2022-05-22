<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
require_once( dirname( __FILE__ ) . '/includes/class-cache.php');
require_once( dirname( __FILE__ ) . '/includes/class-header.php');
require_once( dirname( __FILE__ ) . '/includes/class-homepage-animation.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-checkout.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-emails.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-opc.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-product-restriction.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-single-product.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-vendor.php');


/* Add custom functions below */

add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets', 10 );
function ds_enqueue_assets() {

  wp_enqueue_style( 'divi-style-parent', get_template_directory_uri() . '/style.css', array(), et_get_theme_version() );
  wp_enqueue_style( 'divi-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

  wp_enqueue_script( 'mgnscnt-main', get_stylesheet_directory_uri() . '/js/main.js', '',  wp_get_theme()->get('Version'), true );
  wp_localize_script( 'mgnscnt-main', 'mgnscnt_actions', array(
    'ajaxurl'   => admin_url( 'admin-ajax.php' ),
    'ajaxnonce' => wp_create_nonce( 'mgnscnt_action_nonce' )
  ) );
  wp_register_script( 'scrollme', get_stylesheet_directory_uri() . '/js/jquery.scrollme.min.js', '', '1.0', true );
  


}//end function ds_enqueue_assets


////////////////////////////////////////////////////
// FEATURED IMAGES IN RSS (for MailChimp)
////////////////////////////////////////////////////
function rss_post_thumbnail($content) {
  global $post;
  if(has_post_thumbnail($post->ID)) {
  $content = '<p>' . get_the_post_thumbnail($post->ID, 'large') .
  '</p>' . get_the_content();
}
return $content;
}
add_filter('the_excerpt_rss', 'rss_post_thumbnail');
add_filter('the_content_feed', 'rss_post_thumbnail');



function wl ( $log ) {
	if ( is_array( $log ) || is_object( $log ) ) {
	error_log( print_r( $log, true ) );
	} else {
	error_log( $log );
	}
}