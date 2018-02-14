<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Admin;

use Decoupled_Json_Content\Helpers as General_Helpers;
use Decoupled_Json_Content\Page as Page;

/**
 * Class Admin
 */
class Admin {

  /**
   * Global plugin name
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $plugin_name;

  /**
   * Global plugin version
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $plugin_version;

  /**
   * General Helper class
   *
   * @var object General_Helper
   *
   * @since 1.0.0
   */
  public $general_helper;

  /**
   * Page class
   *
   * @var object Page
   *
   * @since 1.0.0
   */
  public $page;

  /**
   * Initialize class
   *
   * @param array $plugin_info Load global theme info.
   *
   * @since 1.0.0
   */
  public function __construct( $plugin_info = null ) {
    $this->plugin_name    = $plugin_info['plugin_name'];
    $this->plugin_version = $plugin_info['plugin_version'];

    $this->general_helper = new General_Helpers\General_Helper();
    $this->page = new Page\Page( $plugin_info );
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since 1.0.0
   */
  public function enqueue_scripts() {

    $main_script = '/skin/public/scripts/djc-application.js';
    wp_register_script( $this->plugin_name . '-scripts', plugin_dir_url( __DIR__ ) . $main_script, array(), $this->general_helper->get_assets_version( $main_script ) );
    wp_enqueue_script( $this->plugin_name . '-scripts' );

    // Glbal variables for ajax and translations.
    wp_localize_script(
      $this->plugin_name . '-scripts', 'djcLocalization', array(
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
          'confirmRebuildAction' => esc_html__( 'Are you sure you want to rebuild all transients?', 'decoupled_json_content' ),
      )
    );

  }

  /**
   * Add Columns for Post/Page/Custom post type
   *
   * @param array $columns columns.
   * @return array
   *
   * @since 1.0.0
   */
  public function add_admin_columns( $columns ) {
    $columns['cached'] = esc_html__( 'Cached', 'decoupled_json_content' );
    return $columns;
  }

  /**
   * Return html for endpoint link depending on transient state.
   *
   * @param int $post_id post_id.
   * @return html
   */
  public function get_enpoint_link( $post_id = null ) {
    if ( ! $post_id ) {
      return;
    }

    $cache_name = $this->page->get_page_cache_name_by_id( $post_id );
    if ( ! $cache_name ) {
      return false;
    }

    $cache = get_transient( $cache_name );
    if ( $cache === false ) {
      return '<span class="dashicons dashicons-no"></span>';
    } else {
      return '<span class="dashicons dashicons-yes"></span>&nbsp;<a href="' . esc_url( $this->page->get_api_endpoint_link_by_id( $post_id ) ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'API', 'decoupled_json_content' ) . '</a>';
    }
  }

  /**
   * Add Columns Content for Events Type
   *
   * @param array $column  column.
   * @param int   $post_id post_id.
   *
   * @since 1.0.0
   */
  public function add_admin_columns_content( $column, $post_id ) {

    switch ( $column ) {
      case 'cached':
        $this->get_enpoint_link( $post_id );
            break;
    }
  }

  /**
   * Add endpoint link to the publish meta box
   *
   * @since 1.0.0
   */
  function add_publish_meta_options() {
    global $post;

    if ( $this->page->is_post_type_allowed_to_save( $post->post_type ) ) {
      echo wp_kses_post( '<div class="misc-pub-section">' . $this->get_enpoint_link( $post->ID ) . '</div>' );
    }
  }

}
