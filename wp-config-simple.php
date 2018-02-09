<?php
/**
 * Create simple wp configuration for Rest API routes
 *
 * @since 1.0.0
 * @package decoupled_json_content
 *
 * TO DO: Move this to a separate method or something like this.
 */

define( 'SHORTINIT', true );

if ( ! isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
  return;
}

$parse_uri = explode( 'wp-content', sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_FILENAME'] ) ) );
require_once( $parse_uri[0] . 'wp-load.php' );
