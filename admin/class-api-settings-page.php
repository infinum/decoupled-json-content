<?php
/**
 * The API settings page specific functionality.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Admin;

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
   * Settings page items slug
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $settings_page_items_slug = 'api-settings-items';

  /**
   * Settings page rebuild page slug
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $settings_page_list_slug = 'api-settings-list';

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
      esc_html__( 'General', 'decoupled_json_content' ),
      esc_html__( 'General', 'decoupled_json_content' ),
      'manage_options',
      $this->settings_page_main_slug,
      array( $this, 'get_settings_page_general' )
    );

    add_submenu_page(
      $this->settings_page_main_slug,
      esc_html__( 'Items', 'decoupled_json_content' ),
      esc_html__( 'Items', 'decoupled_json_content' ),
      'manage_options',
      $this->settings_page_items_slug,
      array( $this, 'get_settings_page_items' )
    );

    add_submenu_page(
      $this->settings_page_main_slug,
      esc_html__( 'List', 'decoupled_json_content' ),
      esc_html__( 'List', 'decoupled_json_content' ),
      'manage_options',
      $this->settings_page_list_slug,
      array( $this, 'get_settings_page_list' )
    );
  }

  /**
   * Return array of all avaiable endpoints for General List
   *
   * @return array
   *
   * @since 1.0.0
   */
  public function get_settings_page_data_general() {
    $default_endpoints = array(
        array(
            'title' => 'Menus',
            'url' => get_home_url() . '/wp-content/plugins/' . $this->plugin_name . '/menu/rest-routes/menu.php',
            'note' => wp_kses_post( 'Transient data is set on admin init. <br/>Cache updated on Menu update.', 'decoupled_json_content' ),
        ),
    );

    // Allow developers to add new items to list.
    if ( has_filter( 'djc_set_general_endpoint' ) ) {
      $appended_endpoints = apply_filters( 'djc_set_general_endpoint', $default_endpoints );

      // Appended Items must be multidimensional array.
      if ( is_array( $appended_endpoints ) ) {
        $default_endpoints = $appended_endpoints;
      }
    }

    return $default_endpoints;
  }

  /**
   * Return array of all avaiable endpoints for Items list.
   *
   * @return array
   *
   * @since 1.0.0
   */
  public function get_settings_page_data_items() {
    $default_endpoints = array(
        array(
            'title' => 'Individual Item',
            'url' => get_home_url() . '/wp-content/plugins/' . $this->plugin_name . '/page/rest-routes/page.php?slug=&type=',
            'note' => wp_kses_post( 'Transient data is set on post/page/custom_post_type save. <br/>Cache updated on post/page/custom_post_type save or on rebuild button on this <a href="' . get_home_url() . '/wp-admin/admin.php?page=' . $this->settings_page_items_slug . '">link.</a>.', 'decoupled_json_content' ),
        ),
    );

    // Allow developers to add new items to list.
    if ( has_filter( 'djc_set_items_endpoint' ) ) {
      $appended_endpoints = apply_filters( 'djc_set_items_endpoint', $default_endpoints );

      // Appended Items must be multidimensional array.
      if ( is_array( $appended_endpoints ) ) {
        $default_endpoints = $appended_endpoints;
      }
    }

    return $default_endpoints;
  }

  /**
   * Return array of all avaiable endpoints for data list.
   *
   * @return array
   *
   * @since 1.0.0
   */
  public function get_settings_page_data_list() {
    $default_endpoints = array(
        array(
            'title' => 'Rebuild Default Posts List',
            'action-filter' => 'default',
            'post-type' => 'post',
        ),
    );

    // Allow developers to add new items to list.
    if ( has_filter( 'djs_set_lists_endpoint' ) ) {
      $appended_endpoints = apply_filters( 'djs_set_lists_endpoint', $default_endpoints );

      // Appended Items must be multidimensional array.
      if ( is_array( $appended_endpoints ) ) {
        $default_endpoints = $appended_endpoints;
      }
    }

    return $default_endpoints;
  }


  /**
   * Get template view from partial file for general page.
   *
   * @since 1.0.0
   */
  public function get_settings_page_general() {
    $list = $this->get_settings_page_data_general();
    $general_helper = new General_Helpers\General_Helper();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/settings-page-general.php';
    unset( $list );
  }

  /**
   * Get template view from partial file for items page.
   *
   * @since 1.0.0
   */
  public function get_settings_page_items() {
    $list = $this->get_settings_page_data_items();
    $general_helper = new General_Helpers\General_Helper();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/settings-page-items.php';
    unset( $list );
  }

  /**
   * Get template view from partial file for list page.
   *
   * @since 1.0.0
   */
  public function get_settings_page_list() {
    $list = $this->get_settings_page_data_list();
    $general_helper = new General_Helpers\General_Helper();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/settings-page-list.php';
    unset( $list );
  }

}
