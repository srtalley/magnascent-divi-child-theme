<?php
namespace Magnascent\WooCommerce;

class Vendor{

    public function __construct() {
        add_shortcode('magnascent-contact-form-7', array($this,'cfp'));
        add_shortcode('vendor-approve', array($this,'vendor_approve_shortcode'));

        add_action( 'wpcf7_before_send_mail', array($this, 'add_pending_vendor') );
        add_action( 'wcmp_vendor_preview_tabs_form_pre', array($this, 'show_vendor_info_in_plugin') );
        add_filter('wp_ajax_reject_pending_vendor', array($this, 'new_reject_pending_vendor'));

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
        wl($user_meta);
        if(empty($user_meta)) {
            return;
        }
        $user_roles=$user_meta->roles[0];
        $userlogin = $user_meta->user_login;
        $useremail = $user_meta->user_email;

        if($usertype=='Approve') {	
            $userid = base64_decode($_REQUEST['userid']);
            $u = new \WP_User($userid);
            $u->remove_role($user_roles);
            $u->add_role('dc_vendor');	
            echo '<div class="text-success text-center vendor-status vendor-approve">
                    <h4>Vendor application approved successfully!</h4>
                    <a href="' .site_url(). '/wp-admin/admin.php?page=vendors&action=edit&ID=' . $userid . '" target="_blank">View Vendor Info</a>
                </div>';
        }
        if($usertype=='Reject') {	
            $userid = base64_decode($_REQUEST['userid']);
            $u2 = new \WP_User($userid);
            $u2->remove_role($user_roles);
            $u2->add_role('customer');
            echo '<div class="text-success text-center vendor-status vendor-reject">
                    <h4>Vendor application rejected successfully!</h4>
                    <a href="' .site_url(). '/wp-admin/user-edit.php?user_id=' . $userid . '" target="_blank">View User Account Info</a>
                </div>';
        }
    }
    /**
     * Reject new pending vendor
     */
    function new_reject_pending_vendor()
    {
        $user_id = $_POST['user_id']; 
        $user = new WP_User( absint( $user_id ) );
        if(is_array( $user->roles ) && in_array( 'dc_pending_vendor', $user->roles )) {
            $user->remove_role( 'dc_pending_vendor' );
        }
        $user->add_role( 'customer' );
        $user_dtl = get_userdata( absint( $user_id ) );
        $email = WC()->mailer()->emails['WC_Email_Rejected_New_Vendor_Account'];
        $email->trigger( $user_id, $user_dtl->user_pass );		
        
        if(is_array( $user->roles ) && in_array( 'dc_vendor', $user->roles )) {
            $vendor = get_wcmp_vendor($user_id);
            if($vendor) wp_delete_term( $vendor->term_id, 'dc_vendor_shop' );
            $caps = $this->get_vendor_caps( $user_id );
            foreach( $caps as $cap ) {
                $user->remove_cap( $cap );
            }
            $user->remove_cap('manage_woocommerce');
        }
        die();
    }

    /**
     * Add the vendor who submitted a form as a pending vendor
     */
    public function add_pending_vendor($wpcf7_form) {
        if (6770 == $wpcf7_form->id()) {

            //Get current form
            $wpcf7      = \WPCF7_ContactForm::get_current();

            // get current SUBMISSION instance
            $submission = \WPCF7_Submission::get_instance();

            // Ok go forward
            if ($submission) {
    
                // get submission data
                $data = $submission->get_posted_data();
                // nothing's here... do nothing...
                if (empty($data)) {
                    return;
                }
    
                // extract posted data for example to get name and change it
                $name         = isset($data['your-name']) ? $data['your-name'] : "";

                $user_email = sanitize_text_field(isset($data['dist-email']) ? $data['dist-email'] : "");

                $password = sanitize_text_field(isset($data['dist-password']) ? $data['dist-password'] : "");
                
                // check if there's an existing user id
                $user_id = email_exists($user_email);

                // see if it is a vendor already
                $user = get_user_by('ID', $user_id);

                $username = $user->data->user_login;
                $already_vendor = false;
                if(in_array('dc_vendor', $user->roles) || in_array('administrator', $user->roles)) {
                    $already_vendor = true;
                }
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
                    $user_id = wp_create_user( $username, $password, $user_email );

                }
                if($user_id > 0 && !$already_vendor) {                
                    
                    wp_update_user( array(
                            'ID' => $user_id,
                            'user_url' => sanitize_text_field(isset($data['dist-websiteurl']) ? $data['dist-websiteurl'] : "")
                       ) );
                    
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
                    update_user_meta($user_id,"dis_passcode", sanitize_text_field(isset($data['dist-tel']) ? $data['dist-passcode'] : ""));
            
                      update_user_meta($user_id,"first_name", sanitize_text_field(isset($data['dist-first_name']) ? $data['dist-first_name'] : ""));
                    update_user_meta($user_id,"last_name", sanitize_text_field(isset($data['dist-last_name']) ? $data['dist-last_name'] : ""));

                    $u = new \WP_User($user_id);
                    $u->add_role('dc_pending_vendor');

                }
                $accept_link='<a href="' . site_url() . '/store/vendor-status?userid='.base64_encode($user_id).'&type=Approve" target="_blank" style="color:#fff; text-decoration:none;">Approve</a>';
                $reject_link='<a href="' . site_url() . '/store/vendor-status/?userid='.base64_encode($user_id).'&type=Reject" target="_blank" style="color:#fff; text-decoration:none;">Reject</a>';
                // do some replacements in the cf7 email body
                $mail         = $wpcf7->prop('mail');
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
                }
                // Find/replace the tags as defined in your CF7 email body
                $mail['body'] = str_replace('[username]', $username, $mail['body']);
                $mail['body'] = str_replace('[acceptlink]', $accept_link, $mail['body']);
                $mail['body'] = str_replace('[rejectlink]', $reject_link, $mail['body']);
                $mail['body'] = str_replace('[user_already_vendor]', $user_already_vendor, $mail['body']);

                // Save the email body
                $wpcf7->set_properties(array(
                    "mail" => $mail
                ));
    
                // return current cf7 instance
                return $wpcf7;
            }
        }
    }
    public function show_vendor_info_in_plugin($is_approved_vendor) {

        $user_id = $_GET['ID'];

        $vendor_user_meta = $this->get_vendor_user_meta($user_id);

        echo '<style>#vendor-application{display: none !important;}</style>';
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
            array(
                'label' => 'Passcode',
                'value' => get_user_meta( $user_id, 'dis_passcode', true )
            )
        );
        return $vendor_meta;
    }
} // end class Vendor

$magnascent_woocommerce_vendor= new Vendor();




