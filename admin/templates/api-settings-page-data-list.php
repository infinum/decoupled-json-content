<?php
/**
 * Provide an admin area view for the plugin
 *
 * @since 1.0.0
 * @package decoupled_json_content
 */

?>
<div class="wrap">
  <div class="js-djc-msg"></div>
  <h1><?php esc_html_e( 'REST API Endpoints DATA LIST', 'decoupled_json_content' ); ?></h1>
  <h3><?php esc_html_e( 'Endpoint list', 'decoupled_json_content' ); ?></h3>
  <p><?php esc_html_e( 'This is a list of all available endpoints.', 'decoupled_json_content' ); ?></p>
  <p><?php esc_html_e( 'If some data is unavailable check if the data is successfully saved in the cache!', 'decoupled_json_content' ); ?></p>
  <?php wp_nonce_field( 'djc_rebuild_nonce_action', 'djc_rebuild_nonce' ); ?>
  <?php if ( ! empty( $list ) ) { ?>
    <ul>
      <?php foreach ( $list as $list_item ) { ?>
        <?php if ( ($list_item['action-filter'] && $list_item['transient-name'] && $list_item['title'] ) !== null ) { ?>
          <li>
            <button class="js-djc-rebuild-data-list button-primary" data-action-filter="<?php echo esc_html( $list_item['action-filter'] ); ?>" data-transient-name="<?php echo esc_html( $list_item['transient-name'] ); ?>">
              <?php esc_html_e( $list_item['title'] ); ?>
            </button>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
  <?php } ?>
</div>
