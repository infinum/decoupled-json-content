<?php
/**
 * Generate rest route for posts list
 *
 * Route location: /wp-content/plugins/decoupled-json-content/page/rest-routes/list.php?post_type=post_type&posts_per_page=posts_per_page
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Page\Rest_Routes;

use Decoupled_Json_Content\Helpers as General_Helpers;
use Decoupled_Json_Content\Page as Page;

require_once( '../../wp-config-simple.php' );
require_once( '../class-page.php' );
require_once( '../class-data-list.php' );
require_once( '../../helpers/class-general-helper.php' );

$data_list = new Page\Data_List();
$general_helper = new General_Helpers\General_Helper();

$transient_name = '';
if ( isset( $_GET['transient_name'] ) ) { // WPCS: input var ok; CSRF ok.
  $transient_name = sanitize_text_field( wp_unslash( $_GET['transient_name'] ) ); // WPCS: input var ok; CSRF ok.
}

$cache = $data_list->get_data_list( $transient_name );

if ( $cache === false ) {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, there is a problem with your configuration or pages/posts are it is not cached correctly. Please try rebuilding cache and try again!' ) );
}

wp_send_json( $cache );
