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
  <h3><?php esc_html_e( 'Endpoint list', 'decoupled_json_content' ); ?></h3>
  <p><?php esc_html_e( 'This is a list of all available endpoints.', 'decoupled_json_content' ); ?></p>
  <p><?php esc_html_e( 'If some data is unavailable check if the data is successfully saved in the cache!', 'decoupled_json_content' ); ?></p>
  <?php if ( ! empty( $list ) ) { ?>
    <ul>
      <?php foreach ( $list as $list_item ) { ?>
        <li>
          <a href="<?php echo esc_url( $list_item['url'] ); ?>" target="_blank" rel="noopener noreferrer"> 
            <?php echo esc_html( $list_item['title'] ); ?>
          </a>
        </li>
      <?php } ?>
    </ul>
  <?php } ?>
</div>
