<?php
/**
 * The menu-specific functionality of the plugin.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Menu;

/**
 * Class Menu
 */
class Menu {

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
   * Menu name used for caching in transient
   *
   * @var string
   *
   * @since 1.0.0
   */
  public $menu_cache_name = 'djc_menu';


  /**
   * Default posts slug used as prefix
   *
   * @var string
   *
   * @since 1.0.0
   */
  public $posts_slug = 'blog';

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
   * Return all menu poistions
   *
   * @return array Of menu positions with name and slug.
   *
   * @since 1.0.0
   */
  public function get_menu_positions() {
    return apply_filters( 'djc_set_menu_positions', $this->set_default_menu_positions() );
  }

  /**
   * Set default menu positions
   *
   * @return array
   *
   * @since 1.0.0
   */
  public function set_default_menu_positions() {
    return array(
        'header_main_nav' => esc_html__( 'Main Menu', 'decoupled_json_content' ),
    );
  }

  /**
   * Register All Menu positions
   *
   * @since 1.0.0
   */
  public function register_menu_positions() {
    register_nav_menus(
      $this->get_menu_positions()
    );
  }

  /**
   * Set default posts slug used for post url
   *
   * @return string
   *
   * @since  1.0.0
   */
  public function get_default_posts_slug() {
    return apply_filters( 'djc_set_posts_slug', $this->posts_slug );
  }

  /**
   * Return menu items assigned to menu locations
   * With changed url from absolute to relative path
   *
   * @param string $theme_location Menu location configured in get_menu_positions() function.
   * @return array Menu items styled for json-api.
   *
   * @since 1.0.0
   */
  public function get_assigned_filtered_menu_items( $theme_location ) {
    if ( ! $theme_location ) {
      return false;
    }

    $locations = get_nav_menu_locations();
    if ( ! $locations ) {
      return false;
    }

    if ( ! isset( $locations[ $theme_location ] ) ) {
      return false;
    }

    $menu = get_term( $locations[ $theme_location ], 'nav_menu' );

    // Return menu items as object.
    $menu_items = wp_get_nav_menu_items( $menu->term_id );

    // Filter output to match requirements.
    $menu_items_oputput = array();
    foreach ( $menu_items as $menu_item ) {

      // Remove absolute url and add prefix for blog on posts.
      $url = rtrim( str_replace( get_home_url(), '', $menu_item->url ), '/' );
      if ( $menu_item->object === 'post' ) {
        $url = '/' . $this->get_default_posts_slug() . $url;
      }

      $menu_items_oputput[] = array(
          'id'          => $menu_item->ID,
          'title'       => $menu_item->title,
          'url'         => $url,
          'parent'      => (int) $menu_item->menu_item_parent,
          'target'      => $menu_item->target === '_blank',
          'attr_title'  => $menu_item->attr_title,
          'xfn'         => $menu_item->xfn,
          'description' => $menu_item->description,
          'classes'     => implode( ' ', $menu_item->classes ),
          'type'        => $menu_item->object,
      );
    }

    return $menu_items_oputput;
  }

  /**
   * Return array with all menus and their items
   *
   * @return array Menu array styled for json-api.
   *
   * @since 1.0.0
   */
  public function get_full_filtered_menus() {
    $menu_positions = $this->get_menu_positions();

    $menu_output = array();
    foreach ( $menu_positions as $menu_position_key => $menu_position_value ) {
      $menu_output[] = array(
          'name'  => $menu_position_value,
          'slug'  => $menu_position_key,
          'items' => $this->get_assigned_filtered_menu_items( $menu_position_key ),
      );
    }

    return $menu_output;
  }

  /**
   * Return Menus in JSON format
   *
   * @return json
   *
   * @since  1.0.0
   */
  public function get_json_menus() {
    return wp_json_encode( $this->get_full_filtered_menus() );
  }

  /**
   * Set Menus to transient for caching
   *
   * @since  1.0.0
   */
  public function set_page_transient() {
    $cache_name = $this->menu_cache_name;
    $cache = get_transient( $cache_name );

    if ( $cache === false ) {
      $cache = $this->get_json_menus();

      set_transient( $cache_name, $cache, 0 );
    }
  }

  /**
   * Chear cache on menu open
   *
   * @since  1.0.0
   */
  public function clear_cache() {
    $cache_name = $this->menu_cache_name;

    $screen = get_current_screen();

    if ( is_admin() && $screen->base === 'nav-menus' ) {
      delete_transient( $cache_name );
    }
  }
}
