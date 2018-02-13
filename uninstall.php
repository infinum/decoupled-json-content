<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
  // Clear all the existing and registered tranisents - probably try to keep a record of all existing
  // and registered transients with this plugin.
}
