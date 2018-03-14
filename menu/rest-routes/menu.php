<?php
/**
 * Generate rest doute
 *
 * Route location: /wp-content/plugins/decoupled-json-content/menu/rest-routes/menu.php
 *
 * @since   1.0.0
 * @package Decoupled_Json_Content
 */

namespace Decoupled_Json_Content\Menu;

use Decoupled_Json_Content\Helpers as General_Helpers;

require_once '../../wp-config-simple.php';
require_once '../class-menu.php';
require_once '../../helpers/class-general-helper.php';

header( 'Access-Control-Allow-Origin: *' );

$menu           = new Menu();
$general_helper = new General_Helpers\General_Helper();

$cache = get_transient( $menu->menu_cache_name );

if ( $cache === false ) {
  wp_send_json( $general_helper->set_msg_array( 'error', 'Error, menu does not exist in cache. Please rebuild cache.' ) );
}

wp_send_json( json_decode( $cache ) );
