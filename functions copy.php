<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
require_once( dirname( __FILE__ ) . '/includes/class-header.php');
require_once( dirname( __FILE__ ) . '/includes/class-homepage-animation.php');

require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-checkout.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-emails.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-opc.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-single-product.php');
require_once( dirname( __FILE__ ) . '/includes/class-woocommerce-vendor.php');


/* Add custom functions below */

add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets', 15 );
function ds_enqueue_assets() {

  // wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), et_get_theme_version() );
  // wp_dequeue_style( 'divi-style' );
  // wp_enqueue_style( 'child-theme', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

  wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/main.js', '', '0.1', true );

  wp_register_script( 'scrollme', get_stylesheet_directory_uri() . '/js/jquery.scrollme.min.js', '', '1.0', true );

}//end function ds_enqueue_assets



/////////////////////////////////////////////////////
// NEW MENU LOCATIONS
////////////////////////////////////////////////////
add_action( 'init', 'register_dst_custom_menus' );
function register_dst_custom_menus() {
  register_nav_menu('mobile-tab-menu',__( 'Mobile Tab Menu' ));
  register_nav_menu('footer-copyright-menu',__( 'Footer Copyright Menu' ));
}

////////////////////////////////////////////////////
// CHILD THEME CUSTOMIZER OPTIONS
////////////////////////////////////////////////////
// require_once('includes/theme_customizer.php');

////////////////////////////////////////////////////
// HEADER CUSTOMIZATIONS
////////////////////////////////////////////////////
function dst_mobile_tab_menu() {
if ( has_nav_menu( 'mobile-tab-menu' ) ) : 
  ?>
    <div id="mobile-tab-nav">
			<div class="container">
        <?php
          wp_nav_menu( array(
            'theme_location' => 'mobile-tab-menu',
            'depth'          => '2',
            'menu_class'     => 'mobile-tab-nav',
            'container'      => '',
            'fallback_cb'    => '',
          ) );
        ?>
      </div>
    </div> <!-- #mobile-tab-nav -->

<?php
endif;
  // ob_start(); 
  // echo '<a class="et_pb_button header_button" href="#">New Button</a>';
  // $duplicate_social_icons = ob_get_contents();
  // ob_end_clean(); 

} // end function et_header_top_hook_example
add_action( 'et_after_main_content', 'dst_mobile_tab_menu' );


////////////////////////////////////////////////////
// CUSTOM WIDGET AREAS
////////////////////////////////////////////////////
function dst_widgets_init() {
  register_sidebar( array(
    'name'          => 'Above Footer Fullwidth',
    'id'            => 'footer-fullwidth',
    'before_widget' => '<div class="clear"></div><div id="footer-fullwidth" class="widget-area">',
    'after_widget'  => '</div>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
  ));
} //end function ds_widgets_init
add_action( 'widgets_init', 'dst_widgets_init');

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


// THEME CUSTOMIZER OPTIONS

// Output custom CSS to live site
// add_action( 'wp_head' , 'dst_header_output' );
add_action( 'wp_enqueue_scripts', 'dst_header_output', 14 );

function dst_header_output() {
  $footer_background_image = esc_attr(get_theme_mod('dst_footer_background_img'));
  $header_fixed_alternate_logo = esc_attr( get_theme_mod( 'dst_header_fixed_header_logo', ''));
  if($header_fixed_alternate_logo != '' || $footer_background_image != '') {
    // $customizer_css = '<style type="text/css">';
    $customizer_css = '';

    if($header_fixed_alternate_logo != '') {
        $customizer_css .= '
        .logo_container a:before {
          background-image: url("' . $header_fixed_alternate_logo . '");
        }';
        $customizer_css .= file_get_contents(get_stylesheet_directory_uri() . '/css/logo.css');
    }
    if ($footer_background_image != '') {
      $customizer_css .= '#main-footer {
        background-image: url("' . $footer_background_image . '"); 
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
      }';
    }

    // $customizer_css .= '</style><!--/Customizer CSS-->';

    // echo $customizer_css;
    wp_register_style( 'customizer-css', false );
    wp_enqueue_style( 'customizer-css' );
    wp_add_inline_style( 'customizer-css', $customizer_css );
  
  } // end  if($header_top_left_image != '') {
   
} // end function dst_header_output


// // change the archive titles
// function dst_archive_title( $title ) {
//   if ( is_category() ) {
//       $title = single_cat_title( '', false );
//   } elseif ( is_tag() ) {
//       $title = single_tag_title( '', false );
//   } elseif ( is_author() ) {
//       $title = '<span class="vcard">' . get_the_author() . '</span>';
//   } elseif ( is_post_type_archive() ) {
//       $title = post_type_archive_title( '', false );
//   } elseif ( is_tax() ) {
//       $title = single_term_title( '', false );
//   }

//   return $title;
// }
// add_filter( 'get_the_archive_title', 'dst_archive_title' );

function wl ( $log ) {
	if ( is_array( $log ) || is_object( $log ) ) {
	error_log( print_r( $log, true ) );
	} else {
	error_log( $log );
	}
}