<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Includes;

use Decoupled_Json_Content\Admin as Admin;
use Decoupled_Json_Content\Menu as Menu;
use Decoupled_Json_Content\Page as Page;

/**
 * The main start class.
 *
 * This is used to define admin-specific hooks
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
class Main {

  /**
   * Loader variable for hooks
   *
   * @var Loader    $loader    Maintains and registers all hooks for the plugin.
   *
   * @since 1.0.0
   */
  protected $loader;

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
   * Global assets version
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $assets_version;

  /**
   * Initialize class
   * Load hooks and define some global variables.
   *
   * @since 1.0.0
   */
  public function __construct() {
    if ( defined( 'DJC_PLUGIN_VERSION' ) ) {
      $this->plugin_version = DJC_PLUGIN_VERSION;
    } else {
      $this->plugin_version = '1.0.0';
    }

    if ( defined( 'DJC_PLUGIN_NAME' ) ) {
      $this->plugin_name = DJC_PLUGIN_NAME;
    } else {
      $this->plugin_name = 'decoupled-json-content';
    }

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_menu_hooks();
    $this->define_page_hooks();
  }

  /**
   * Load the required dependencies.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since 1.0.0
   */
  private function load_dependencies() {
    $this->loader = new Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Decoupled_Json_Content_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since 1.0.0
   */
  private function set_locale() {
    $plugin_i18n = new Internationalization( $this->get_plugin_info() );

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since 1.0.0
   */
  private function define_admin_hooks() {
    $admin             = new Admin\Admin( $this->get_plugin_info() );
    $api_settings_page = new Admin\Api_Settings_Page( $this->get_plugin_info() );

    // Admin.
    $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );

    $this->loader->add_action( 'manage_pages_custom_column', $admin, 'add_admin_columns_content', 10, 2 );
    $this->loader->add_filter( 'manage_pages_columns', $admin, 'add_admin_columns' );
    $this->loader->add_action( 'manage_posts_custom_column', $admin, 'add_admin_columns_content', 10, 2 );
    $this->loader->add_filter( 'manage_posts_columns', $admin, 'add_admin_columns' );

    // Api settings page.
    $this->loader->add_action( 'admin_menu', $api_settings_page, 'register_settings_page' );
    $this->loader->add_action( 'wp_ajax_nopriv_djc_rebuild_all_transients_ajax', $api_settings_page, 'djc_rebuild_all_transients_ajax' );
    $this->loader->add_action( 'wp_ajax_djc_rebuild_all_transients_ajax', $api_settings_page, 'djc_rebuild_all_transients_ajax' );

  }

  /**
   * Register hooks for Menu functionality
   *
   * @since 1.0.0
   */
  private function define_menu_hooks() {
    $menu = new Menu\Menu( $this->get_plugin_info() );

    // Menues.
    $this->loader->add_action( 'after_setup_theme', $menu, 'register_menu_positions' );
    $this->loader->add_action( 'admin_head', $menu, 'set_page_transient' );
    $this->loader->add_action( 'wp_update_nav_menu', $menu, 'clear_cache' );

  }

  /**
   * Register hooks for Page functionality
   *
   * @since 1.0.0
   */
  private function define_page_hooks() {
    $page = new Page\Page( $this->get_plugin_info() );

    // Page.
    $this->loader->add_action( 'save_post', $page, 'update_page_transient' );
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since 1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The reference to the class that orchestrates the hooks.
   *
   * @return Loader Orchestrates the hooks.
   *
   * @since 1.0.0
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * The name used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @return string Plugin name.
   *
   * @since 1.0.0
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * Retrieve the version number.
   *
   * @return string Plugin version number.
   *
   * @since 1.0.0
   */
  public function get_plugin_version() {
    return $this->plugin_version;
  }

  /**
   * Retrieve the plugin info array.
   *
   * @return array Plugin info array.
   *
   * @since 1.0.0
   */
  public function get_plugin_info() {
    return array(
        'plugin_name' => $this->plugin_name,
        'plugin_version' => $this->plugin_version,
    );
  }

}
