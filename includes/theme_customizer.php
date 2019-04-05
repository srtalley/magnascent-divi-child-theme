<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since CFNPTheme 1.0
 */
class CFNPTheme_Customize {

   public static function dst_register ( $wp_customize ) {
     /* ========================================================== */
     //    MAIN PANEL
     /* ========================================================== */

     $wp_customize->add_panel( 'dst_child_theme_customizations_option', array(
       'priority' => 1,
       'capability' => 'edit_theme_options',
       'title' => __('CFNP Custom Options', 'dst_child_theme'),
     ));

     /* ========================================================== */
     // HEADER OPTIONS PANEL  //
     /* ========================================================== */
     $wp_customize->add_section('dst_header_section', array(
      'priority' => 10,
      'title' => __('Header', 'dst_child_theme'),
      'panel' => 'dst_child_theme_customizations_option',
      'description' => __('Customize the header.', 'dst_child_theme'),
     ));

      // Additional header image
      $wp_customize->add_setting('dst_header_fixed_header_logo');

      $wp_customize->add_control(
        new WP_Customize_Image_Control( $wp_customize, 'dst_header_fixed_header_logo',
            array(
                'label' => __('Fixed Header Alternate Logo','dst_child_theme'),
                'section' => 'dst_header_section',
                'settings' => 'dst_header_fixed_header_logo',
                'priority'   => 10
            )
        )
      );

     /* ========================================================== */
     // FOOTER OPTIONS PANEL  //
     /* ========================================================== */
     $wp_customize->add_section('dst_footer_copyright_section', array(
       'priority' => 15,
       'title' => __('Footer', 'dst_child_theme'),
       'panel' => 'dst_child_theme_customizations_option',
       'description' => __('Customize the footer.', 'dst_child_theme'),
     ));

       // Copyright text
       $wp_customize->add_setting('dst_footer_copyright_text', array(
         'default' => esc_attr( get_bloginfo( 'name' )),
       ));

       $wp_customize->add_control('dst_footer_copyright_text', array(
         'label' => __('Copyright Text', 'dst_child_theme'),
         'section' => 'dst_footer_copyright_section',
         'type' => 'text',
         'priority' => 20,
         'settings' => 'dst_footer_copyright_text'
       ));

       // Copyright rights reserved
       $wp_customize->add_setting('dst_footer_copyright_reserved', array(
         'default' => 'All Rights Reserved',
       ));

       $wp_customize->add_control('dst_footer_copyright_reserved', array(
         'label' => __('Rights Reserved Text', 'dst_child_theme'),
         'section' => 'dst_footer_copyright_section',
         'type' => 'text',
         'priority' => 25,
         'settings' => 'dst_footer_copyright_reserved'
       ));

   } //end public static function dst_register
} // end class CFNPTheme_Customize

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'CFNPTheme_Customize' , 'dst_register' ) );
