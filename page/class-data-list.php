<?php
/**
 * The Data_List-specific functionality of the plugin.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Page;

use Decoupled_Json_Content\Admin as Admin;

/**
 * Class List
 */
class Data_List {

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
   * Initialize class
   *
   * @param array $plugin_info Load global theme info.
   *
   * @since 1.0.0
   */
  public function __construct( $plugin_info = null ) {
    $this->plugin_name    = $plugin_info['plugin_name'];
    $this->plugin_version = $plugin_info['plugin_version'];

    // add_filter( 'djc_set_data_list_args', array( $this, 'test' ) );
  }

  public function init() {
    
  }

  /**
   * Helper function to get Page cache name for transient by post slug and type.
   *
   * @param string $post_type      Post type to query by.
   * @param string $posts_per_page Number of items to query by.
   * @return string
   *
   * @since  1.0.0
   */
  public function get_data_list( $args = null ) {

    if( ! $args ) {
      return false;
    }

    $the_query = new \WP_Query( $args );


    if( ! $the_query ) {
      return false;
    }

    if( ! $the_query->posts ) {
      return false;
    }

    // var_dump($the_query);
    // die;

    $page = new Page();
    $posts_output = array();
    foreach( $the_query->posts as $post ) {
      if( $post ) {
        $get_transient_data = get_transient( $page->get_page_cache_name_by_slug( $post->post_name, $post->post_type ) );
        $posts_output[] = json_decode( $get_transient_data );
      }
    }

    return $posts_output;

  }

}
