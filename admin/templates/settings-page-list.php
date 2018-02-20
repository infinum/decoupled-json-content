<?php
/**
 * Provide an admin area view for the plugin
 *
 * @since 1.0.0
 * @package decoupled_json_content
 */

?>
<div class="wrap">
  <h1><?php esc_html_e( 'REST API Endpoints', 'decoupled_json_content' ); ?></h1>
  <h3><?php esc_html_e( 'Lists Endpoints', 'decoupled_json_content' ); ?></h3>
  <p><?php esc_html_e( 'This is a list of all available endpoints for listing.', 'decoupled_json_content' ); ?></p>
  <p><?php esc_html_e( 'If some data is unavailable check if the data is successfully saved in the cache!', 'decoupled_json_content' ); ?></p>
  <p><?php esc_html_e( 'You can append items to this list using filter hooks from the documentation.', 'decoupled_json_content' ); ?></p>
  <hr/>
  <div class="js-djc-msg"></div>
  <?php if ( ! empty( $list ) ) { ?>
    <ul>
      <?php foreach ( $list as $list_item ) { ?>
        <?php
          $post_type     = $general_helper->get_array_value( 'post-type', $list_item );
          $action_filter = $general_helper->get_array_value( 'action-filter', $list_item );
          $title         = $general_helper->get_array_value( 'title', $list_item );
        ?>
        <?php if ( ! empty( $post_type && $action_filter && $title ) ) { ?>
          <?php
            $endpoint_url = get_home_url() . '/wp-content/plugins/' . $this->plugin_name . '/data-list/rest-routes/data-list.php?post-type=' . $post_type . '&filter=' . $action_filter;
          ?>
          <li>
            <a href="<?php echo esc_url( $endpoint_url ); ?>" target="_blank" rel="noopener noreferrer">
              <?php esc_html_e( 'Endpoint', 'decoupled_json_content' ); ?>
            </a>
            &nbsp;
            <button class="js-djc-rebuild-data-list button-primary" data-action-filter="<?php echo esc_attr( $action_filter ); ?>" data-post-type="<?php echo esc_attr( $post_type ); ?>">
              <?php echo esc_html( $title ); ?>
            </button>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
    <?php wp_nonce_field( 'djc_rebuild_lists_nonce_action', 'djc_rebuild_lists_nonce' ); ?>
  <?php } ?>
</div>
