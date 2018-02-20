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

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] ); // WPCS: XSS ok, sanitization ok.
require_once( $parse_uri[0] . 'wp-load.php' );
require( $parse_uri[0] . WPINC . '/formatting.php' );

