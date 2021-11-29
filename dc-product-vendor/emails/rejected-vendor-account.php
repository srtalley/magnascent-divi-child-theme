<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/emails/rejected-vendor-account.php
 *
 * @author 		WC Marketplace
 * @package 	dc-product-vendor/Templates
 * @version   0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $WCMp;
?>
<?php /*do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( __( "Thanks for creating an account with us on %s. Unfortunately your request has been rejected.",  $WCMp->text_domain ), esc_html( $blogname )); ?></p>
<p><?php printf( __( "You may contact the site admin at %s.",  $WCMp->text_domain ), get_option('admin_email')); ?></p>

<?php do_action( 'woocommerce_email_footer' ); */?>

<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="header" width="100%">
  <tbody>
    <tr>
      <td><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td bgcolor="#e4e4e4"></td>
                    </tr>
                    <tr>
                      <td bgcolor="#007acc" height="8"></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="left-image" width="100%">
  <tbody>
    <tr>
      <td><table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                          <tbody>
                            <tr>
                              <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #585858; text-align:left; line-height:18px; border-bottom:1px solid #e5e5e5; padding:20px 0px 20px 0;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/magnascent.png" alt="Magnascent"></td>
                            </tr>
                            <tr>
                              <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #585858; text-align:left; line-height:18px; padding:27px 0px 10px 0;"><span style="font-family:Arial, Helvetica, sans-serif; font-size: 20px; color:#e21717 ; text-align:left; line-height:18px;font-weight:bold"><?php echo $email_heading; ?></span></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="left-image" width="100%">
  <tbody>
    <tr>
      <td><table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td align="center"><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                          <tbody>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">Thanks for creating an account with us on Magnascent. Unfortunately your request has been rejected.</span></td>
                            </tr>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">You may contact the site admin at <?php echo get_option('admin_email'); ?></span></td>
                            </tr>                                                        
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="header" width="100%">
  <tbody>
    <tr>
      <td><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td bgcolor="#007acc" height="8"></td>
                    </tr>
                    <tr>
                      <td bgcolor="#e4e4e4"></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>