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

// Check input and protect it.
if ( ( isset( $_GET['slug'] ) || ! empty( $_GET['slug'] ) ) && ( isset( $_GET['type'] ) || ! empty( $_GET['type'] ) ) ) { // WPCS: XSS ok, sanitization ok, CSRF ok.
  $post_slug = htmlentities( trim( $_GET['slug'] ), ENT_QUOTES ); // WPCS: XSS ok, sanitization ok, CSRF ok.
  $post_type = htmlentities( trim( $_GET['type'] ), ENT_QUOTES ); // WPCS: XSS ok, sanitization ok, CSRF ok.
} else {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, page slug or type is missing!' ) );
}

$cache = get_transient( $page->get_page_cache_name_by_slug( $post_slug, $post_type ) );

if ( $cache === false ) {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, the page does not exist or it is not cached correctly. Please try rebuilding cache and try again!' ) );
}

wp_send_json( json_decode( $cache ) );
