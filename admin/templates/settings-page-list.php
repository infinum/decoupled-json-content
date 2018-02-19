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
        <?php if ( ($list_item['action-filter'] && $list_item['title'] ) !== null ) { ?>
          <li>
            <a href="<?php echo esc_url( get_home_url() . '/wp-content/plugins/' . $this->plugin_name . '/data-list/rest-routes/data-list.php?filter=' . $list_item['action-filter'] ); ?>"target="_blank" rel="noopener noreferrer">
              <?php esc_html_e( 'Endpoint', 'decoupled_json_content' ); ?>
            </a>
            &nbsp;
            <button class="js-djc-rebuild-data-list button-primary" data-action-filter="<?php echo esc_html( $list_item['action-filter'] ); ?>">
              <?php esc_html_e( $list_item['title'] ); ?>
            </button>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
    <?php wp_nonce_field( 'djc_rebuild_lists_nonce_action', 'djc_rebuild_lists_nonce' ); ?>
  <?php } ?>
</div>
