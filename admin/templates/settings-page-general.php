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
  <h3><?php esc_html_e( 'General Endpoints', 'decoupled_json_content' ); ?></h3>
  <p><?php esc_html_e( 'This is a list of all general available endpoints.', 'decoupled_json_content' ); ?></p>
  <p><?php esc_html_e( 'If some data is unavailable check if it is successfully saved in the cache!', 'decoupled_json_content' ); ?></p>
  <p><?php esc_html_e( 'You can append items to this list using filter hooks from the documentation.', 'decoupled_json_content' ); ?></p>
  <hr/>
  <?php if ( ! empty( $list ) ) { ?>
    <ul>
      <?php foreach ( $list as $list_item ) { ?>
        <?php if ( ! empty( $list_item['url'] && $list_item['title'] ) ) { ?>
          <li>
            <a href="<?php echo esc_url( $list_item['url'] ); ?>" target="_blank" rel="noopener noreferrer"> 
              <?php echo wp_kses_post( $list_item['title'] ); ?>
            </a>
            <?php if ( ! empty( $list_item['note'] ) ) { ?>
              <br/>
              <small><?php echo wp_kses_post( $list_item['note'] ); ?></small>
            <?php } ?>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
  <?php } ?>
</div>
