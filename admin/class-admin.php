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
    $this->plugin_name     = $plugin_info['plugin_name'];
    $this->plugin_version  = $plugin_info['plugin_version'];

    $this->general_helper = new General_Helpers\General_Helper();
    $this->page = new Page\Page( $plugin_info );
  }

  /**
   * Register the Stylesheets for the admin area.
   *
   * @since 1.0.0
   */
  public function enqueue_styles() {

    $main_style = '/skin/public/styles/djc-application.css';
    wp_register_style( $this->plugin_name . '-style', plugin_dir_url( __DIR__ ) . $main_style, array(), $this->general_helper->get_assets_version( $main_style ) );
    wp_enqueue_style( $this->plugin_name . '-style' );

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
   * Add Columns Content for Events Type
   *
   * @param array  $column column.
   * @param string $post_id post_id.
   *
   * @since 1.0.0
   */
  public function add_admin_columns_content( $column, $post_id ) {

    switch ( $column ) {
      case 'cached':
        $cache_name = $this->page->get_page_cache_name_by_id( $post_id );
        if ( ! $cache_name ) {
          return false;
        }

        $cache = get_transient( $cache_name );
        if ( $cache === false ) {
          echo '<span class="dashicons dashicons-no"></span>';
        } else {
          echo '<span class="dashicons dashicons-yes"></span>&nbsp;<a href="' , esc_url( $this->page->get_api_endpoint_link_by_id( $post_id ) ) , '" target="_blank" rel="noopener noreferrer">' , esc_html__( 'API', 'decoupled_json_content' ) , '</a>';
        }
            break;
    }
  }
}
