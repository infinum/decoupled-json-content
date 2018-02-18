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
  }

  /**
   * Build json buy getting the order from transient build by button.
   *
   * @param string $transient_name Transient name provide from url.
   * @return array
   *
   * @since  1.0.0
   */
  public function get_data_list( $transient_name = null ) {
    $page = new Page();

    $cache_name = $this->get_cache_name( $transient_name );
    if ( ! $cache_name ) {
      return false;
    }

    $posts = get_transient( $cache_name );

    if ( ! $posts ) {
      return false;
    }

    $posts_output = array();
    foreach( $posts as $post ) {
      if( $post ) {
        $get_transient_data = get_transient( $page->get_page_cache_name_by_slug( $post['slug'], $post['type'] ) );
        if( $get_transient_data === false ) {
          return false;
        }

        $posts_output[] = json_decode( $get_transient_data );
      }
    }

    return $posts_output;
  }

  /**
   * Get cache name.
   *
   * @param string $transient_name Treansient name provided by filter.
   * @return string
   *
   * @since  1.0.0
   */
  public function get_cache_name( $transient_name = null ) {
    if( ! empty( $transient_name ) ) {
      $transient_name = '_' . $transient_name;
    }

    return 'djs_data_list' . $transient_name;
  }

  /**
   * Get default posts list.
   *
   * @return array
   *
   * @since  1.0.0
   */
  public function get_default_args() {
    return array(
      'post_type'      => 'post',
      'posts_per_page' => 10000,
    );
  }

  /**
   * Get arguments list for query.
   *
   * @param string $filter Add custom filter to override the default.
   * @return array
   *
   * @since  1.0.0
   */
  public function get_args( $filter = null ) {

    $default = $this->get_default_args();
    $action_filter = '';

    if( $filter ) {
      $action_filter = '_' . $filter;
    }

    // Allow developers to add new items to array.
    if ( has_filter( 'djs_set_data_list' . $action_filter ) ) {
      $filtered = apply_filters( 'djs_set_data_list' . $action_filter, $default );

      // Must be array.
      if ( is_array( $filtered ) ) {
        $default = $filtered;
      }
    }

    return $default;
  }

  /**
   * Set transient data for order list.
   *
   * @param [type] $action_filter Filter name.
   * @param [type] $transient_name Treansient name.
   *
   * @since  1.0.0
   */
  public function set_transient( $action_filter = null, $transient_name = null ) {
    if ( current_user_can( 'edit_users' ) ) {


      $output_array = array();
      $args = $this->get_args( $action_filter );

      if( ! $args ) {
        return false;
      }

      $the_query = new \WP_Query( $args );

      if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
          $the_query->the_post();
          $post_slug = $the_query->post->post_name;
          $post_type = $the_query->post->post_type;

          $output_array[] = array(
            'slug' => $post_slug,
            'type' => $post_type,
          );
        }
        wp_reset_postdata();
      }

      $cache_name = $this->get_cache_name( $transient_name );
      if ( ! $cache_name ) {
        return false;
      }

      set_transient( $cache_name, $output_array, 0 );
    }
  }
}
