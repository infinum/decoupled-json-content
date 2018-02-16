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
require( $parse_uri[0] . WPINC . '/default-constants.php' );

require( $parse_uri[0] . WPINC . '/class-wp-query.php' );
require( $parse_uri[0] . WPINC . '/class-wp-tax-query.php' );
require( $parse_uri[0] . WPINC . '/class-wp-meta-query.php' );
require( $parse_uri[0] . WPINC . '/class-wp-user.php' );
require( $parse_uri[0] . WPINC . '/class-wp-post.php' );
require( $parse_uri[0] . WPINC . '/taxonomy.php' );
require( $parse_uri[0] . WPINC . '/post.php' );
require( $parse_uri[0] . WPINC . '/formatting.php' );
require( $parse_uri[0] . WPINC . '/pluggable.php' );
require( $parse_uri[0] . WPINC . '/user.php' );
require( $parse_uri[0] . WPINC . '/meta.php' );
require( $parse_uri[0] . WPINC . '/kses.php' );
require( $parse_uri[0] . WPINC . '/capabilities.php' );
require( $parse_uri[0] . WPINC . '/rest-api.php' );

require( $parse_uri[0] . WPINC . '/comment.php' );
require( $parse_uri[0] . WPINC . '/l10n.php' );
