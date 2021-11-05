<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

// BEGIN CUSTOM 
$userdata = get_user_by('login', $user_login);
$user_email = $userdata->user_email; 
?>
<table bgcolor="#e4e4e4" border="0" cellpadding="0" cellspacing="0" id="backgroundTable" st-sortable="header" width="100%">
  <tbody>
    <tr>
      <td><table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
          <tbody>
            <tr>
              <td width="100%"><table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                  <tbody>
                    <tr>
                      <td bgcolor="#e4e4e4">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#007acc" height="8">&nbsp;</td>
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
                              <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #585858; text-align:left; line-height:18px; border-bottom:1px solid #e5e5e5; padding:20px 0px 20px 0;"><img src="https://magnascent.com/images/magnascent.png" alt="Magnascent"></td>
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
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 18px; color: #808080; text-align:left; line-height:18px;  padding:10px 0px 10px 0;"><span class="font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 16px; color:#808080; text-align:left; line-height:22px;">Thanks for creating an account on Magnascent. Your username is <?php echo  esc_html( $user_email ); ?></span></td>
                            </tr>
                            <tr>
                              <td class="pad_01 font1" style="font-family:Arial, Helvetica, sans-serif; font-size: 15px; color: #808080; text-align:left; line-height:18px;  padding:5px 0px 30px 0;">Thanks,<br/>The Magnascent Team<br/>magnascent.com</td>
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
                      <td bgcolor="#007acc" height="8">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#e4e4e4">&nbsp;</td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>