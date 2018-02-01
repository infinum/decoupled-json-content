<?php
/**
 * Provide a admin area view for the plugin
 *
 * @since 1.0.0
 * @package decoupled_json_content
 */

?>
<div class="wrap">
  <div class="js-djc-msg"></div>
  <h1><?php esc_html_e( 'REST API Endpoints', 'decoupled_json_content' ); ?></h1>
  <h3><?php esc_html_e( 'Rebuilding Transient cache!', 'decoupled_json_content' ); ?></h3>
  <p><?php esc_html_e( 'This action will rebild and cache all pages/posts and custom post types in the database.', 'decoupled_json_content' ); ?></p>
  <p><strong><?php esc_html_e( 'Use this action with caution. It can be veary heavy if there are a lot of data.', 'decoupled_json_content' ); ?></strong></p>
  <?php wp_nonce_field( 'djc_rebuild_nonce_action', 'djc_rebuild_nonce' ); ?>
  <button class="js-djc-rebuild button-primary"><?php esc_html_e( 'Rebuild', 'decoupled_json_content' ); ?></button>
</div>
