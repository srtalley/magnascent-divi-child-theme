<?php
namespace Magnascent\Theme;

class Cache {

    public function __construct() {
        add_action( 'template_redirect', array($this, 'add_cache_control_headers') );

    }
    /**
     * Add cache-control headers to response
     */

    function add_cache_control_headers() {
        global $post;
        $post_slug = $post->post_name;

        $no_cache_pages = array(
            'cart',
            'checkout',
            'store',
            'my-account'
        );
        if (!is_admin() && !in_array($post_slug , $no_cache_pages)) {
            header("Cache-Control: public, must-revalidate, max-age=3600");
        }
    }
} // end class Cache

$magnascent_cache = new Cache();




