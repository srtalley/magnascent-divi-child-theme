<?php
namespace Magnascent\WooCommerce;

class Emails {

    public function __construct() {
        add_action( 'woocommerce_email_before_order_table', array($this, 'dst_add_content_to_tracking_email'), 20, 4 );


    }
    
    /*
    * Add the call to action to the order shipment email
    */
    
    function dst_add_content_to_tracking_email( $order, $sent_to_admin, $plain_text, $email ) {
    if ( $email->id == 'BST_Tracking_Order_Email' ) {
            echo '<br>';
            echo '<p>If you have been using Magnascent for sometime now, we would love to hear how you have enjoyed it. <a href="https://magnascent.com/reviews">	Write us here</a>. If you are a first time purchaser, please don\'t forget to come back and share your experience with us!</p>';
    }
    }
} // end class Emails

$magnascent_woocommerce_emails = new Emails();




