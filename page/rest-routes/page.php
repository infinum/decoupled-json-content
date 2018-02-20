<?php
/**
 * Generate rest route
 *
 * Route location: /wp-content/plugins/decoupled-json-content/page/rest-routes/page.php?slug=slug&type=type
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Page\Rest_Routes;

use Decoupled_Json_Content\Helpers as General_Helpers;
use Decoupled_Json_Content\Page as Page;


require_once( '../../wp-config-simple.php' );
require_once( '../class-page.php' );
require_once( '../../helpers/class-general-helper.php' );

$page = new Page\Page();
$general_helper = new General_Helpers\General_Helper();

// Check slug.
if ( isset( $_GET['slug'] ) && ! empty( $_GET['slug'] ) ) { // WPCS: input var ok; CSRF ok.
  $post_slug = sanitize_text_field( wp_unslash( $_GET['slug'] ) ); // WPCS: input var ok; CSRF ok.
} else {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, slug is missing!' ) );
}

// Check post type.
if ( isset( $_GET['type'] ) && ! empty( $_GET['type'] ) ) { // WPCS: input var ok; CSRF ok.
  $post_type = sanitize_text_field( wp_unslash( $_GET['type'] ) ); // WPCS: input var ok; CSRF ok.
} else {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, type is missing!' ) );
}

$cache = get_transient( $page->get_page_cache_name_by_slug( $post_slug, $post_type ) );

if ( $cache === false ) {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, there is a problem with your configuration or pages/posts are not cached correctly. Please check your configuration, rebuild the cache and try again!' ) );
}

wp_send_json( json_decode( $cache ) );
