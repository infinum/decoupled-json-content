<?php
/**
 * The page-specific functionality of the plugin.
 *
 * @since   1.0.0
 * @package decoupled_json_content
 */

namespace Decoupled_Json_Content\Page;

use Decoupled_Json_Content\Admin as Admin;

/**
 * Class Page
 */
class Page {

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
   * Page name used for caching in transient
   *
   * @var string
   *
   * @since 1.0.0
   */
  public $page_cache_name = 'djc_data';

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
   * Helper function to get Page cache name for transient by post slug and type.
   *
   * @param string $post_slug Page Slug to save.
   * @param string $post_type Page Type to save.
   * @return string
   *
   * @since  1.0.0
   */
  public function get_page_cache_name_by_slug( $post_slug = null, $post_type = null ) {
    if ( ! ( $post_slug || $post_type ) ) {
      return false;
    }

    $post_slug = str_replace( '__trashed', '', $post_slug );

    return $this->page_cache_name . '_' . $post_type . '_' . $post_slug;
  }

  /**
   * Helper function to get Page details by Post ID.
   *
   * @param int $post_id Page/Post ID.
   * @return array
   *
   * @since  1.0.0
   */
  public function get_page_details_by_id( $post_id = null ) {
    if ( ! $post_id ) {
      return false;
    }

    $post = get_post( $post_id );
    if ( ! $post ) {
      return false;
    }

    return array(
        'slug' => $post->post_name,
        'type' => $post->post_type,
    );
  }

  /**
   * Helper function to get cache name from post ID
   *
   * @param int $post_id Page/Post ID.
   * @return string
   *
   * @since  1.0.0
   */
  public function get_page_cache_name_by_id( $post_id = null ) {
    if ( ! $post_id ) {
      return false;
    }

    $post = $this->get_page_details_by_id( $post_id );
    if ( ! $post ) {
      return false;
    }

    return $this->get_page_cache_name_by_slug( $post['slug'], $post['type'] );
  }

  /**
   * Helper function to get endpoint link by providing post ID
   *
   * @param int $post_id Page/Post ID.
   * @return string
   *
   * @since  1.0.0
   */
  public function get_api_endpoint_link_by_id( $post_id = null ) {
    if ( ! $post_id ) {
      return false;
    }

    $post = $this->get_page_details_by_id( $post_id );
    if ( ! $post ) {
      return false;
    }

    return get_home_url() . '/wp-content/plugins/' . $this->plugin_name . '/page/rest-routes/page.php?slug=' . $post['slug'] . '&type=' . $post['type'];
  }

  /**
   * Get full post data by post slug and type.
   *
   * @param string $post_slug Page Slug to do Query by.
   * @param string $post_type Page Type to do Query by.
   * @return array
   *
   * @since  1.0.0
   */
  public function get_page_data_by_slug( $post_slug = null, $post_type = null ) {
    if ( ! ( $post_slug || $post_type ) ) {
      return false;
    }

    $page_output = '';
    $args = array(
        'name' => $post_slug,
        'post_type' => $post_type,
        'posts_per_page' => 1,
        'no_found_rows' => true,
    );

    $the_query = new \WP_Query( $args );

    if ( $the_query->have_posts() ) {
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $post_id = $the_query->post->ID;
        $page_output = (array)$the_query->post;

        $custom_fields = apply_filters( 'djc_set_custom_fields', $this->get_custom_fields( $post_id ) );
        if( $custom_fields !== false ) {
          $page_output['custom_fields'] = $custom_fields;
        }
        
        $template = apply_filters( 'djc_set_page_template', $this->get_page_template( $post_id ) );
        if( $template !== false ) {
          $page_output['template'] = $template;
        }
        
        $format = apply_filters( 'djc_set_post_format', $this->get_post_format( $post_id ) );
        if( $format !== false ) {
          $page_output['format'] = $format;
        }

        // Allow developers to add new items to list.
        if( has_filter( 'djc_set_post_append' ) ) {
          $appended_key = apply_filters( 'djc_set_post_append', $page_output );

          // Must be array.
          if ( is_array( $appended_key ) ) {
            $page_output = array_merge( $page_output, $appended_key );
          }
        }

      }
      wp_reset_postdata();
    }

    return $page_output;
  }

  /**
   * Return post format for specific post/page
   *
   * @param int $post_id Page/Post ID.
   * @return string
   *
   * @since 1.0.0
   */
  public function get_post_format( $post_id = null ) {
    if ( ! $post_id ) {
      return;
    }

    return get_post_format( $post_id );
  }

  /**
   * Return all custom fields for specific post/page
   *
   * @param int $post_id Page/Post ID.
   * @return array
   *
   * @since 1.0.0
   */
  public function get_custom_fields( $post_id = null ) {
    if ( ! $post_id ) {
      return;
    }

    return get_post_custom( $post_id );
  }

  /**
   * Return page template for specific post/page
   *
   * @param int $post_id Page/Post ID.
   * @return string
   *
   * @since 1.0.0
   */
  public function get_page_template( $post_id = null ) {
    if ( ! $post_id ) {
      return;
    }

    $template = '';

    if ( $post_type === 'page' ) {
      $template = get_page_template_slug( $post_id );
    }

    return $template;
  }

  /**
   * Return Page in JSON format
   *
   * @param string $post_slug Page Slug.
   * @param string $post_type Page Type.
   * @return json
   *
   * @since  1.0.0
   */
  public function get_json_page( $post_slug = null, $post_type = null ) {
    if ( ! ( $post_slug || $post_type ) ) {
      return false;
    }

    return wp_json_encode( $this->get_page_data_by_slug( $post_slug, $post_type ) );
  }

  /**
   * Get the array of allowed types to do operations on.
   *
   * @return array
   *
   * @since 1.0.0
   */
  public function get_allowed_post_types() {
    return apply_filters( 'djc_set_allowed_post_types', $this->set_default_allowed_post_types() );
  }

  /**
   * Set default allowed post types
   *
   * @return array
   *
   * @since 1.0.0
   */
  public function set_default_allowed_post_types() {
    return array( 'post', 'page' );
  }

  /**
   * Check if post type is allowed to be save in transient.
   *
   * @param string $post_type Get post type.
   * @return boolean
   *
   * @since 1.0.0
   */
  public function is_post_type_allowed_to_save( $post_type = null ) {
    if ( ! $post_type ) {
      return false;
    }

    $allowed_types = $this->get_allowed_post_types();

    return in_array( $post_type, $allowed_types, true );
  }

  /**
   * Update Page to transient for caching on action hooks save_post.
   *
   * @param int $post_id Saved Post ID provided by action hook.
   *
   * @since 1.0.0
   */
  public function update_page_transient( $post_id ) {
    $post_status = get_post_status( $post_id );

    $post = $this->get_page_details_by_id( $post_id );
    if ( ! $post ) {
      return false;
    }

    $cache_name = $this->get_page_cache_name_by_slug( $post['slug'], $post['type'] );
    if ( ! $cache_name ) {
      return false;
    }

    if ( $post_status === 'auto-draft' || $post_status === 'inherit' ) {
      return false;
    } elseif ( $post_status === 'trash' ) {
      delete_transient( $cache_name );
    } else {
      if ( $this->is_post_type_allowed_to_save( $post['type'] ) ) {
        $cache = $this->get_json_page( $post['slug'], $post['type'] );
        set_transient( $cache_name, $cache, 0 );
      }
    }
  }

  /**
   * Set all pages in transient in case all transients are deleted.
   * Called on custom button from admin.
   * Really heavy!!!
   *
   * @since 1.0.0
   */
  public function set_all_pages_transient() {

    if ( current_user_can( 'edit_users' ) ) {
      $allowed_types = $this->get_allowed_post_types();

      $args = array(
          'post_type' => $allowed_types,
          'posts_per_page' => 5000,
      );

      $the_query = new \WP_Query( $args );

      if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
          $the_query->the_post();
          $post_slug = $the_query->post->post_name;
          $post_type = $the_query->post->post_type;

          $cache_name = $this->get_page_cache_name_by_slug( $post_slug, $post_type );
          if ( ! $cache_name ) {
            return false;
          }

          $cache = get_transient( $cache_name );

          if ( $cache === false ) {
            $cache = $this->get_json_page( $post_slug, $post_type );

            set_transient( $cache_name, $cache, 0 );
          }
        }
        wp_reset_postdata();
      }
    }
  }
}
