<?php
/**
 * The API settings page specific functionality.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Admin;

use Decoupled_Json_Content\Page as Page;
use Decoupled_Json_Content\Helpers as General_Helpers;

/**
 * Class Api_Settings_Page
 *
 * Creates API setting page to display all api endpoints and ability to rebuild all page transients
 */
class Api_Settings_Page {

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
   * Settings page main page slug
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $settings_page_main_slug = 'api-settings';

  /**
   * Settings page rebuild page slug
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $settings_page_rebuild_slug = 'api-settings-rebuild';

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
  }

  /**
   * Register Setting page to sidebar navigation
   *
   * @since 1.0.0
   */
  public function register_settings_page() {

    add_menu_page(
      esc_html__( 'API Settings', 'decoupled_json_content' ),
      esc_html__( 'API Settings', 'decoupled_json_content' ),
      'manage_options',
      $this->settings_page_main_slug
    );

    add_submenu_page(
      $this->settings_page_main_slug,
      esc_html__( 'APIs List', 'decoupled_json_content' ),
      esc_html__( 'APIs List', 'decoupled_json_content' ),
      'manage_options',
      $this->settings_page_main_slug,
      array( $this, 'get_settings_page_list' )
    );

    add_submenu_page(
      $this->settings_page_main_slug,
      esc_html__( 'Rebuild Cache', 'decoupled_json_content' ),
      esc_html__( 'Rebuild Cache', 'decoupled_json_content' ),
      'manage_options',
      $this->settings_page_rebuild_slug,
      array( $this, 'get_settings_page_rebuild' )
    );
  }

  /**
   * Return array of all avaiable endpoints
   *
   * @return array
   *
   * @since 1.0.0
   */
  public function get_api_endpoints_list() {
    $default_endpoints = array(
      array(
          'title' => 'Page per slug',
          'url' => get_home_url() . '/wp-content/plugins/' . $this->plugin_name . '/page/rest-routes/page.php?slug=&type=',
          'note' => wp_kses_post( 'Transient data is set on post/page/custom_post_type save. <br/>Cache updated on post/page/custom_post_type save or on rebuild button on this <a href="' . get_home_url() . '/wp-admin/admin.php?page=' . $this->settings_page_rebuild_slug . '">link.</a>', 'decoupled_json_content' )
      ),
      array(
          'title' => 'Menus',
          'url' => get_home_url() . '/wp-content/plugins/' . $this->plugin_name . '/menu/rest-routes/menu.php',
          'note' => wp_kses_post( 'Transient data is set on admin init. <br/>Cache updated on Menu update.', 'decoupled_json_content' )
      )
    );

    // Allow developers to add new items to list.
    if( has_filter( 'djc_append_endpoints_list' ) ) {
      $appended_endpoints = apply_filters( 'djc_append_endpoints_list', $default_endpoints );

      // Appended Items must be multidimensional array.
      if ( is_array( $appended_endpoints ) ) {
        $default_endpoints = array_merge( $default_endpoints, $appended_endpoints );
      }
    }


    return $default_endpoints;
  }
  

  /**
   * Get template view from partial file for list page.
   *
   * @since 1.0.0
   */
  public function get_settings_page_list() {
    $list = $this->get_api_endpoints_list();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/api-settings-page-list.php';
    unset( $list );
  }

  /**
   * Get template view from partial file for cache rebuild page.
   *
   * @since 1.0.0
   */
  public function get_settings_page_rebuild() {
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/api-settings-page-rebuild.php';
  }

  /**
   * Ajax function to rebuild all data transients
   *
   * @since 1.0.0
   */
  public function djc_rebuild_all_transients_ajax() {
    $page = new Page\Page();
    $general_helper = new General_Helpers\General_Helper();

    if ( ! isset( $_POST['djcRebuildNonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['djcRebuildNonce'] ), 'djc_rebuild_nonce_action' ) ) {
      wp_send_json( $general_helper->set_msg_array( 'error', 'Check your nonce!' ) );
    }

    $page->set_all_pages_transient();

    wp_send_json( $general_helper->set_msg_array( 'success', 'Success in rebuilding transients for cache!' ) );
  }
}
