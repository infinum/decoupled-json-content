<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since   1.0.0
 * @package Decoupled_Json_Content
 */

namespace Decoupled_Json_Content\Admin;

use Decoupled_Json_Content\Helpers as General_Helpers;

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

}
