<?php
/**
 * The Data_List-specific functionality of the plugin.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Data_List;

// use Decoupled_Json_Content\Admin as Admin;
use Decoupled_Json_Content\Page as Page;
use Decoupled_Json_Content\Helpers as General_Helpers;

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
   * List name used for caching in transient prepended with filter name.
   *
   * @var string
   *
   * @since 1.0.0
   */
  public $list_cache_name = 'djs_data_list';

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
   * Build final json by getting the order from transient build by button in list settings page.
   *
   * @param string $filter Transient name provided by filter paremetar in url.
   * @return array
   *
   * @since  1.0.0
   */
  public function get_data_list( $post_type = null, $filter = null ) {
    if ( ! ( $post_type || $filter ) ) {
      return false;
    }

    $page = new Page\Page();

    $cache_name = $this->get_cache_name( $post_type, $filter );
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
   * @param string $action_filter Action filter name provided by filter paremetar in url.
   * @return string
   *
   * @since  1.0.0
   */
  public function get_cache_name( $post_type = null, $action_filter = null ) {
    if ( ! ( $post_type || $action_filter ) ) {
      return false;
    }

    return $this->list_cache_name . '_' . $post_type . '_' . $action_filter;
  }

  /**
   * Get default posts list arguments.
   *
   * @return array
   *
   * @since  1.0.0
   */
  public function get_default_args() {
    return array(
      'post_type'      => 'post',
      'posts_per_page' => 10000,
      'post_status'    => 'publish'
    );
  }

  /**
   * Get arguments list for query.
   *
   * @param string $filter Add custom filter to override the default. Filter hook is created by default name and prefixed filter provider as arg.
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
    if ( has_filter( 'djs_set_lists_endpoint_query' . $action_filter ) ) {
      $filtered = apply_filters( 'djs_set_lists_endpoint_query' . $action_filter, $default );

      // Must be array.
      if ( is_array( $filtered ) ) {
        $default = $filtered;
      }
    }

    return $default;
  }

  /**
   * Set transient data to be later use for sort ordering.
   *
   * @param string $action_filter Action filter name provided by filter paremetar in url.
   *
   * @since  1.0.0
   */
  public function set_transient( $postType = null, $action_filter = null ) {
    if ( current_user_can( 'edit_users' ) ) {
      if ( ! ( $post_type || $action_filter ) ) {
        return false;
      }

      if( ! empty( $action_filter ) ) {
        $action_filter = str_replace(' ', '_', $action_filter);
        $action_filter = str_replace('-', '_', $action_filter);
      }

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

      $cache_name = $this->get_cache_name( $postType, $action_filter );
      if ( ! $cache_name ) {
        return false;
      }

      set_transient( $cache_name, $output_array, 0 );
    }
  }

  /**
   * Update list to transient for caching on action hooks save_post.
   *
   * @param int $post_id Saved Post ID provided by action hook.
   *
   * @since 1.0.0
   */
  public function update_page_transient( $post_id ) {

    // Remove updating on save.
    if ( has_filter( 'djc_remove_lists_updating' ) ) {
      $remove_updating = apply_filters( 'djc_remove_lists_updating', '' );

      if( $remove_updating === true ) {
        return false;
      }
    }

    $post_status = get_post_status( $post_id );
    $post_type = get_post_type( $post_id );
    $transient_name = '_transient_' . $this->list_cache_name . '_' . $post_type . '_';

    if ( $post_status === 'auto-draft' || $post_status === 'inherit' ) {
      return false;
    } else {

      // Loop all options to find transient items.
      foreach( wp_load_alloptions() as $key => $value ) {

        // Regex to find transient matching post type.
        if( preg_match( '/^' . $transient_name  . '/', $key ) ) {
          
          // Strip string to get action filter name.
          $action_filter = str_replace( $transient_name, '', $key );

          // Update transient.
          $this->set_transient( $post_type, $action_filter );
        }
      }
    }
  }


  /**
   * Ajax function to rebuild all data list transients.
   *
   * @since 1.0.0
   */
  public function djc_rebuild_lists_transients_ajax() {
    $general_helper = new General_Helpers\General_Helper();
    $action_filter = '';

    if ( ! isset( $_POST['djcRebuildNonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['djcRebuildNonce'] ), 'djc_rebuild_nonce_action' ) ) {
      wp_send_json( $general_helper->set_msg_array( 'error', 'Check your nonce!' ) );
    }

    if ( isset( $_REQUEST['actionFilter'] ) ) { // WPCS: input var ok; CSRF ok.
      $action_filter = sanitize_text_field( wp_unslash( $_REQUEST['actionFilter'] ) ); // WPCS: input var ok; CSRF ok.
    }

    if ( isset( $_REQUEST['postType'] ) ) { // WPCS: input var ok; CSRF ok.
      $postType = sanitize_text_field( wp_unslash( $_REQUEST['postType'] ) ); // WPCS: input var ok; CSRF ok.
    }

    $this->set_transient( $postType, $action_filter );

    wp_send_json( $general_helper->set_msg_array( 'success', 'Success in rebuilding transients for cache!' ) );
  }
}
