<div class="wrap">
  <h1>Salesforce Connection</h1>

  <?php // TODO: add info about https and updating salesforce ?>

  <?php
    $consumer_key = ( get_option('salesforce_consumer_key') ) ? get_option('salesforce_consumer_key') : '';
    $consumer_secret = ( get_option('salesforce_consumer_secret') ) ? get_option('salesforce_consumer_secret') : '';
  ?>

  <form action="options.php" method="post">
    <?php settings_fields('wp_salesforce_keys'); ?>
    <?php do_settings_sections('wp-salesforce'); ?>
    <?php submit_button(); ?>
  </form>

  <hr>

  <?php
  // TODO: make sure https is enforced
    $disabled = 'disabled';
    if( get_option('salesforce_consumer_key') && get_option('salesforce_consumer_secret') ) {
      $disabled = '';
    }
  ?>

  <h2>Login to Salesforce</h2>

  <button class="button-secondary" <?php echo $disabled; ?>>
    <a href="<?php echo site_url('/salesforce-login'); ?>" title="<?php esc_attr_e( 'Login' ); ?>"><?php esc_attr_e( 'Login' ); ?></a>
  </button>

</div>