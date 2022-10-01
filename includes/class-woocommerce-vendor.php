<?php
namespace Magnascent\WooCommerce;

class Vendor{

    public function __construct() {
        add_shortcode('magnascent-contact-form-7', array($this,'cfp'));
        add_shortcode('vendor-approve', array($this,'vendor_approve_shortcode'));

        add_action( 'wpcf7_before_send_mail', array($this, 'add_pending_vendor'), 10, 3 );
        add_action( 'wcmp_vendor_preview_tabs_form_post', array($this, 'show_vendor_info_in_plugin') );
        add_filter('wcmp_vendor_get_image_src', array($this, 'replace_broken_wcmp_user_profile_image'), 10, 1);
        add_action( 'edit_user_profile',  array($this, 'show_user_vendor_application') );

        add_filter( 'wcmp_show_report_abuse_link', '__return_false' );
    }
    /**
     * Adds the vendor app info to the user profile
     *
     * @since 0.2
     */
    public function show_user_vendor_application( $user ) {
        if(current_user_can('edit_pages')) {
            $vendor_application = $this->get_vendor_user_meta($user->ID);

            $vendor_html = '';
            $show_vendor = false;
            foreach ($vendor_application as $key => $value) {
                $vendor_html .= '<div class="wcmp-form-field">';
                $vendor_html .= '<label>' . html_entity_decode($value['label']) . ':</label>';
                $vendor_html .= '<span> ' . $value['value'] . '</span>';
                $vendor_html .= '</div>';
                // ignore the standard fields and see if any vendor app
                // fields have been filled
                $ignore_labels = array('Website', 'Email', 'First Name', 'Last Name');
                if(!in_array($value['label'], $ignore_labels)) {
                    if($value['value'] != '') {
                        $show_vendor = true;
                    }
                }
            }

            if($show_vendor) {
                ?>
                 <h3>Vendor Application Info</h3>
                    <table class="form-table">
                        <tr>
                        <td>
                            <?php
                            echo $vendor_html;
                            ?>
                        </td>
                        </tr>
                    </table>
            <?php
            }
        }
    }
    /**
     * Fix broken user avatar images in the user area because of WCMP
     */
    public function replace_broken_wcmp_user_profile_image($src_url) {
        if($src_url == '') {
            $src_url = get_stylesheet_directory_uri() . '/images/WP-stdavatar.png';
        }
        return $src_url;
    }
    /**
     * Add a password field to the contact form 7
     */
    function cfp($atts, $content = null) {
        extract(shortcode_atts(array( "id" => "", "title" => "", "pwd" => "" ), $atts));
        if(empty($id) || empty($title)) return "";
    
        $cf7 = do_shortcode('[contact-form-7 id="'. $id .'" title="' . $title . '"]');
    
        $pwd = explode(',', $pwd);
        foreach($pwd as $p) {
            $p = trim($p);
    
            $cf7 = preg_replace('/<input type="text" name="' . $p . '"/usi', '<input type="password" name="' . $p . '"', $cf7);
        }
    
        return $cf7;
    }
    /**
     * Shortcode to add the accept or reject functions
     */
    public function vendor_approve_shortcode($atts, $content = null) {

        $userid = sanitize_text_field(base64_decode(isset($_GET['userid']) ? $_GET['userid'] : ""));
        $usertype = sanitize_text_field(isset($_GET['type']) ? $_GET['type'] : "");

        if($userid == '' || $usertype == '') {
            return;
        }

        $user_meta = get_userdata($userid);

        if(empty($user_meta)) {
            return;
        }


        $userlogin = $user_meta->user_login;
        $useremail = $user_meta->user_email;
        if($usertype=='Approve') {	
            $userid = base64_decode($_REQUEST['userid']);
            $u = new \WP_User($userid);
            $u->remove_role( 'dc_pending_vendor' );
            $u->add_role('dc_vendor');	
            $wcmp_sent_email = get_user_meta($userid, "wcmp_sent_email", true);
            if($wcmp_sent_email != 'WC_Email_Approved_New_Vendor_Account') {
                $email = WC()->mailer()->emails['WC_Email_Approved_New_Vendor_Account'];
                $email->trigger( $userid );	
                update_user_meta($userid, "wcmp_sent_email", 'WC_Email_Approved_New_Vendor_Account');
            }
            echo '<div class="text-success text-center vendor-status vendor-approve">
                    <h4>Vendor application approved successfully!</h4>
                    <a href="' .site_url(). '/wp-admin/admin.php?page=vendors&action=edit&ID=' . $userid . '" target="_blank">View Vendor Info</a>
                </div>';
        }
        if($usertype=='Reject') {	
            $userid = base64_decode($_REQUEST['userid']);
            $u2 = new \WP_User($userid);
            $u2->remove_role( 'dc_pending_vendor' );
            $u2->remove_role( 'dc_vendor' );
            $u2->add_role('customer');
            $wcmp_sent_email = get_user_meta($userid, "wcmp_sent_email", true);
            if($wcmp_sent_email != 'WC_Email_Rejected_New_Vendor_Account') {
                $email = WC()->mailer()->emails['WC_Email_Rejected_New_Vendor_Account'];
                $email->trigger( $userid );	
                update_user_meta($userid, "wcmp_sent_email", 'WC_Email_Rejected_New_Vendor_Account');
            }

            echo '<div class="text-success text-center vendor-status vendor-reject">
                    <h4>Vendor application rejected successfully!</h4>
                    <a href="' .site_url(). '/wp-admin/user-edit.php?user_id=' . $userid . '" target="_blank">View User Account Info</a>
                </div>';
        }
        $vendor_application = $this->get_vendor_user_meta($userid);
        $vendor_html = '';
        foreach ($vendor_application as $key => $value) {
            $vendor_html .= '<tr>';
            $vendor_html .= '<td><label>' . html_entity_decode($value['label']) . ':</label></td>';
            $vendor_html .= '<td><span> ' . $value['value'] . '</span></td>';
            $vendor_html .= '</tr>';

        }

        ?>
            <h3>Vendor Application Info</h3>
            <table class="form-table">
                <?php
                echo $vendor_html;
                ?>
            </table>
        <?php
        
    }

    /**
     * Add the vendor who submitted a form as a pending vendor
     */
    public function add_pending_vendor($contact_form, &$abort, $submission) {

        if (6770 == $contact_form->id()) {

            // Ok go forward
            if ($submission) {
    
                // get submission data
                $data = $submission->get_posted_data();

                // nothing's here... do nothing...
                if (empty($data)) {
                    return;
                }

                $user_email = sanitize_text_field(isset($data['dist-email']) ? $data['dist-email'] : "");

                $password = sanitize_text_field(isset($data['dist-password']) ? $data['dist-password'] : "");
                
                // check if there's an existing user id
                $user_id = email_exists($user_email);
                $already_vendor = false;

                if(!$user_id) {
                    // create an account by first creating a username
                    $username = strstr($user_email, '@', true);
                    // remove punctuation
                    $username = preg_replace('/[^a-z0-9]+/i', '_', $username);
                    // check if a username exists
                    for($i = 1; $i <= 1000; $i++) {
                        // checking username exists
                        if(username_exists($username)) {
                            $username = $username + $i;
                        } else {
                            break;
                        }
                    }
                    // create the account

                    $user_id = wp_insert_user(array(
                        'user_login' => $username,
                        'user_pass' => $password,
                        'user_email' => $user_email,
                        'role' => 'dc_pending_vendor'
                    ));
                } else {
                    // user exists

                    // see if it is a vendor already
                    $user = get_user_by('ID', $user_id);

                    $username = $user->data->user_login;
                    if(is_array($user->roles) && !empty($user->roles)) {
                        if(in_array('dc_vendor', $user->roles) || in_array('administrator', $user->roles)) {
                            $already_vendor = true;
                        }
                    }
                }
                if($user_id > 0 && !$already_vendor) {                
                    
                    wp_update_user( array(
                            'ID' => $user_id,
                            'user_url' => sanitize_text_field(isset($data['dist-websiteurl']) ? $data['dist-websiteurl'] : "")
                       ) );
                    
                    // store settings and currency
                    update_user_meta($user_id, "_vendor_page_title", $username);

                    update_user_meta($user_id, "_vendor_country_code", 'US');
                    update_user_meta($user_id, "_vendor_country", 'United States (US)');
                    

                    update_user_meta($user_id, "userphone", sanitize_text_field(isset($data['dist-tel']) ? $data['dist-tel'] : ""));
                    update_user_meta($user_id,"hear_us", sanitize_text_field(isset($data['dist-how_hear']) ? $data['dist-how_hear'] : ""));
                    
                    update_user_meta($user_id,"sold_before", sanitize_text_field(isset($data['dist-sold_before']) ? $data['dist-sold_before'] : ""));
                    update_user_meta($user_id,"have_website_or_store", sanitize_text_field(isset($data['dist-website_or_brickmortar']) ? $data['dist-website_or_brickmortar'] : ""));
                    update_user_meta($user_id,"how_plan_distribute", sanitize_text_field(isset($data['dist-how_distribute']) ? $data['dist-how_distribute'] : ""));
                    update_user_meta($user_id,"dis_ship_streetaddress", sanitize_text_field(isset($data['dist-shipping_street']) ? $data['dist-shipping_street'] : ""));
                    update_user_meta($user_id,"dis_ship_address2", sanitize_text_field(isset($data['dist-shipping_street2']) ? $data['dist-shipping_street2'] : ""));
                    update_user_meta($user_id,"dis_ship_city", sanitize_text_field(isset($data['dist-shipping_city']) ? $data['dist-shipping_city'] : ""));
                    update_user_meta($user_id,"dis_ship_zip", sanitize_text_field(isset($data['dist-shipping_zip']) ? $data['dist-shipping_zip'] : ""));
                    update_user_meta($user_id,"dis_ship_country", sanitize_text_field(isset($data['dist-shipping_country']) ? $data['dist-shipping_country'] : ""));
                    update_user_meta($user_id,"dis_ship_state", sanitize_text_field(isset($data['dist-shipping_state']) ? $data['dist-shipping_state'] : ""));
                    update_user_meta($user_id,"dis_streetaddress", sanitize_text_field(isset($data['dist-billing_street']) ? $data['dist-billing_street'] : ""));
                    update_user_meta($user_id,"dis_address2", sanitize_text_field(isset($data['dist-billing_street2']) ? $data['dist-billing_street2'] : ""));
                    update_user_meta($user_id,"dis_city", sanitize_text_field(isset($data['dist-billing_city']) ? $data['dist-billing_city'] : ""));
                    update_user_meta($user_id,"dis_zip", sanitize_text_field(isset($data['dist-billing_zip']) ? $data['dist-billing_zip'] : ""));
                    update_user_meta($user_id,"dis_country", sanitize_text_field(isset($data['dist-billing_country']) ? $data['dist-billing_country'] : ""));
                    update_user_meta($user_id,"dis_state", sanitize_text_field(isset($data['dist-billing_state']) ? $data['dist-billing_state'] : ""));
                    update_user_meta($user_id,"dis_type_business", sanitize_text_field(isset($data['dist-business_type']) ? $data['dist-business_type'] : ""));
                    update_user_meta($user_id,"dis_resale", sanitize_text_field(isset($data['dist-resale_taxid']) ? $data['dist-resale_taxid'] : ""));
                    update_user_meta($user_id,"dis_state_register", sanitize_text_field(isset($data['dist-state_registered']) ? $data['dist-state_registered'] : ""));
                    update_user_meta($user_id,"dis_private_label", sanitize_text_field(isset($data['dist-private_label_name']) ? $data['dist-private_label_name'] : ""));
                    // update_user_meta($user_id,"dis_passcode", sanitize_text_field(isset($data['dist-tel']) ? $data['dist-passcode'] : ""));
            
                      update_user_meta($user_id,"first_name", sanitize_text_field(isset($data['dist-first_name']) ? $data['dist-first_name'] : ""));
                    update_user_meta($user_id,"last_name", sanitize_text_field(isset($data['dist-last_name']) ? $data['dist-last_name'] : ""));

                    // we already set the vendor role for new accounts
                    // but if this is an existing account we want to make
                    // sure the role is added
                    $u = new \WP_User($user_id);
                    $u->add_role('dc_pending_vendor');

                }
                $accept_link='<a href="' . site_url() . '/store/vendor-status?userid='.base64_encode($user_id).'&type=Approve" target="_blank" style="color:#fff; text-decoration:none;">Approve</a>';
                $reject_link='<a href="' . site_url() . '/store/vendor-status/?userid='.base64_encode($user_id).'&type=Reject" target="_blank" style="color:#fff; text-decoration:none;">Reject</a>';

                // do some replacements in the cf7 email body
                $mail = $contact_form->prop('mail');

                $userapprovereject = '';
                $user_already_vendor = '';
                if($already_vendor) {
                    $user_already_vendor = '<table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                    <tbody>
                        <tr>
                            <td width="100%">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                                <tbody>
                                    <tr>
                                        <td align="center" style="border-bottom:1px solid #e5e5e5"><!-- Start of left column -->
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                                            <tbody>
                                                <tr>
                                                    <td class="pad_01 restdminwdt" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #9f9d9d; text-align:right; line-height:18px;  padding:15px 0px 10px 10px;" width="105"><span style="font-family:Arial, Helvetica, sans-serif; font-size: 14px; color:#9f9d9d; text-align:right; line-height:18px;">NOTE:</span></td>
                                                    <td class="pad_01" style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color: #4a4848; text-align:left; line-height:18px; padding:15px 0px 10px 10px;" width="467"><span style="font-family:Arial, Helvetica, sans-serif; font-size: 13px; color:#4a4848; text-align:left; line-height:18px;">User registered, but they are already a vendor. No changes were made to their account, but the info they submitted is below.</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                    </tbody>
                </table>';
                } else {
                    $userapprovereject = '<table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="620">
                    <tbody>
                        <tr>
                            <td width="100%">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                                <tbody>
                                    <tr>
                                        <td align="center">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="572">
                                            <tbody>
                                                <tr>
                                                    <td align="center" class="pad_01">&nbsp;
                                                    <table border="0" cellpadding="0" cellspacing="10" width="350">
                                                        <tbody>
                                                            <tr>
                                                                <td style="background:#5c89a9; text-align:center;font-family:Arial, Helvetica, sans-serif; font-size: 14px;color: #fff;line-height:18px; padding:13px 10px 13px 10px;">' . $accept_link . '</td>
                                                                <td style="background:#f6a740; text-align:center;font-family:Arial, Helvetica, sans-serif; font-size: 14px;color: #fff;line-height:18px; padding:13px 10px 13px 10px;">' . $reject_link . '</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                    </tbody>
                </table>';
                }
                // Find/replace the tags as defined in your CF7 email body
                $mail['body'] = str_replace('[username]', $username, $mail['body']);
                $mail['body'] = str_replace('[user_already_vendor]', $user_already_vendor, $mail['body']);
                $mail['body'] = str_replace('[userapprovereject]', $userapprovereject, $mail['body']);

                // Save the email body
                $contact_form->set_properties(array(
                    "mail" => $mail
                ));

            }
        }
    }
    public function show_vendor_info_in_plugin($is_approved_vendor) {

        $user_id = $_GET['ID'];

        $vendor_user_meta = $this->get_vendor_user_meta($user_id);

        echo '<div id="magnascent-vendor-info" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-corner-bottom ui-widget-content">';
        echo '<style>#magnascent-vendor-info {margin-top: 10px;}#vendor-application{display: none !important;}</style>';
        echo '<h2>Vendor Info</h2>';

        echo '<div class="wcmp-form-field">';
        echo '<label>Status:</label>';

        if($is_approved_vendor) {
            echo 'Approved';
            
        } else {
            echo 'Not Approved';
        }
        echo '</div>';
        foreach($vendor_user_meta as $key => $value) {


            echo '<div class="wcmp-form-field">';
            echo '<label>' . html_entity_decode($value['label']) . ':</label>';
            echo '<span> ' . $value['value'] . '</span>';

            echo '</div>';
        }
        echo '</div>';

    }

    public function get_vendor_user_meta($user_id) {
        $user_data = get_userdata($user_id)->data;
        $vendor_meta = array(
            array(
                'label' => 'Website',
                'value' => $user_data->user_url
            ),
            array(
                'label' => 'Email',
                'value' => $user_data->user_email
            ),
            array(
                'label' => 'Phone',
                'value' => get_user_meta( $user_id, 'userphone', true )
            ),
            array(
                'label' => 'How did you hear about us?',
                'value' => get_user_meta( $user_id, 'hear_us', true )
            ),
            array(
                'label' => 'Have you sold dietary supplements before?',
                'value' => get_user_meta( $user_id, 'sold_before', true )
            ),
            array(
                'label' => 'Do you have a website to sell the iodine or a brick and mortar store?',
                'value' => get_user_meta( $user_id, 'have_website_or_store', true )
            ),
            array(
                'label' => 'If not, how do you plan to distribute our product?',
                'value' => get_user_meta( $user_id, 'how_plan_distribute', true )
            ),
            array(
                'label' => 'Billing Street Address',
                'value' => get_user_meta( $user_id, 'dis_streetaddress', true )
            ),
            array(
                'label' => 'Billing Street Address Line 2',
                'value' => get_user_meta( $user_id, 'dis_address2', true )
            ),
            array(
                'label' => 'Billing City',
                'value' => get_user_meta( $user_id, 'dis_city', true )
            ),
            array(
                'label' => 'Billing State',
                'value' => get_user_meta( $user_id, 'dis_state', true )
            ),
            array(
                'label' => 'Billing Zip/Postal Code',
                'value' => get_user_meta( $user_id, 'dis_zip', true )
            ),
            array(
                'label' => 'Billing Country',
                'value' => get_user_meta( $user_id, 'dis_country', true )
            ),
            array(
                'label' => 'Shipping Street Address',
                'value' => get_user_meta( $user_id, 'dis_ship_streetaddress', true )
            ),
            array(
                'label' => 'Shipping Street Address Line 2',
                'value' => get_user_meta( $user_id, 'dis_ship_address2', true )
            ),
            array(
                'label' => 'Shipping City',
                'value' => get_user_meta( $user_id, 'dis_ship_city', true )
            ),
            array(
                'label' => 'Shipping State',
                'value' => get_user_meta( $user_id, 'dis_ship_state', true )
            ),
            array(
                'label' => 'Shipping Zip/Postal Code',
                'value' => get_user_meta( $user_id, 'dis_ship_zip', true )
            ),
            array(
                'label' => 'Shipping Country',
                'value' => get_user_meta( $user_id, 'dis_ship_country', true )
            ),
            array(
                'label' => 'Type of Business',
                'value' => get_user_meta( $user_id, 'dis_type_business', true )
            ),
            array(
                'label' => 'Resale Tax ID#(TX Wholesalers Only)',
                'value' => get_user_meta( $user_id, 'dis_resale', true )
            ),
            array(
                'label' => 'State Registered In',
                'value' => get_user_meta( $user_id, 'dis_state_register', true )
            ),
            array(
                'label' => 'Private Label Name (if approved)',
                'value' => get_user_meta( $user_id, 'dis_private_label', true )
            ),
            array(
                'label' => 'First Name',
                'value' => get_user_meta( $user_id, 'first_name', true )
            ),
            array(
                'label' => 'Last Name',
                'value' => get_user_meta( $user_id, 'last_name', true )
            ),
            // array(
            //     'label' => 'Passcode',
            //     'value' => get_user_meta( $user_id, 'dis_passcode', true )
            // )
        );
        return $vendor_meta;
    }
} // end class Vendor

$magnascent_woocommerce_vendor= new Vendor();




