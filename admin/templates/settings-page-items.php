<?php
/**
 * Provide a admin area view for the plugin
 *
 * @since 1.0.0
 * @package Decoupled_Json_Content
 */

?>
<div class="wrap">
  <h1><?php esc_html_e( 'REST API Endpoints', 'decoupled_json_content' ); ?></h1>
  <h3><?php esc_html_e( 'Items Endpoints', 'decoupled_json_content' ); ?></h3>
  <p><?php echo wp_kses_post( 'For items endpoint you must provide <strong>post_type and slug</strong> as a GET parameter.', 'decoupled_json_content' ); ?></p>
  <p><?php echo wp_kses_post( 'You can also find the link on listing page and individual item.', 'decoupled_json_content' ); ?></p>
  <p><?php esc_html_e( 'You can append items to this list using filter hooks from the documentation.', 'decoupled_json_content' ); ?></p>
  <hr/>
  <?php if ( ! empty( $list ) ) { ?>
    <ul>
      <?php foreach ( $list as $list_item ) { ?>
        <?php
          $url   = $general_helper->get_array_value( 'url', $list_item );
          $title = $general_helper->get_array_value( 'title', $list_item );
          $note  = $general_helper->get_array_value( 'note', $list_item );
        ?>
        <?php if ( ! empty( $url && $title ) ) { ?>
          <li>
            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
              <?php echo wp_kses_post( $title ); ?>
            </a>
            <?php if ( ! empty( $note ) ) { ?>
              <br/>
              <small><?php echo wp_kses_post( $note ); ?></small>
            <?php } ?>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
  <?php } ?>
  <hr/>
  <h3><?php esc_html_e( 'Rebuilding Transient cache!', 'decoupled_json_content' ); ?></h3>
  <div class="js-djc-msg"></div>
  <p><?php esc_html_e( 'This action will rebild and cache all pages/posts and custom post types in the database.', 'decoupled_json_content' ); ?></p>
  <p><strong><?php esc_html_e( 'Use this action with caution. It can be veary heavy if there are a lot of data.', 'decoupled_json_content' ); ?></strong></p>
  <?php wp_nonce_field( 'djc_rebuild_items_nonce_action', 'djc_rebuild_items_nonce' ); ?>
  <button class="js-djc-rebuild button-primary"><?php esc_html_e( 'Rebuild Items', 'decoupled_json_content' ); ?></button>
</div>
