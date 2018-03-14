<?php
/**
 * Plugin main file
 *
 * @link              https://eightshift.com/
 * @since             1.0.0
 * @package           Decoupled_Json_Content
 *
 * @wordpress-plugin
 * Plugin Name:       Decoupled JSON Content
 * Plugin URI:        https://wordpress.org/plugins/decoupled-json-content/
 * Description:       A faster alternative to the default REST API provided by WordPress for the usage by decoupled (headless) WordPress approach
 * Version:           1.0.0
 * Author:            Infinum WordPress Team
 * Author URI:        https://eightshift.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       decoupled_json_content
 */

namespace Decoupled_Json_Content;

use Decoupled_Json_Content\Includes as Includes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Plugin version global
 *
 * @since 1.0.0
 * @package Decoupled_Json_Content
 */
define( 'DJC_PLUGIN_VERSION', '1.0.0' );

/**
 * Plugin name global
 *
 * @since 1.0.0
 * @package Decoupled_Json_Content
 */
define( 'DJC_PLUGIN_NAME', 'decoupled-json-content' );

/**
 * Include the autoloader so we can dynamically include the rest of the classes.
 *
 * @since 1.0.0
 * @package Decoupled_Json_Content
 */
require_once 'lib/autoloader.php';

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 *
 * @since 1.0.0
 */
function deactivate() {
  Includes\Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function init_plugin() {
  $plugin = new Includes\Main();
  $plugin->run();
}

init_plugin();
