<div class="wrap">
  <h1>Salesforce Connection</h1>

  <p>Be sure to:</p>

  <ul>
    <li>Obtain a client key and client secret from Salesforce.</li>
    <li>Add the site url to the list of white-listed urls in Salesforce.</li>
    <li>Add "<?php echo SALESFORCE__REDIRECT_URI; ?>" to the Callback URL in your app's Salesforce OAuth settings.</li>
    <li>Select "Access and Manage your data(api), Full Access(full), Perform requests on your behalf at any time (refresh_token, offline_access)" in your app's Selected OAuth Scopes.</li>
  </ul>

  <hr>

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

  <h2>Login to Salesforce</h2>

  <?php if( !is_ssl() ) : ?>
    <p>OAuth will not work correctly from plain http, please use an https URL.</p>
  <?php elseif ( !get_option('salesforce_consumer_key') ) : ?>
    <p>You must have the client key in order to login to Salesforce.</p>
  <?php elseif ( !get_option('salesforce_consumer_secret') ) : ?>
    <p>You must have the client secret in order to login to Salesforce.</p>
  <?php else: ?>
    <a class="button-primary" href="<?php echo site_url('/salesforce-login'); ?>" title="<?php esc_attr_e( 'Login' ); ?>"><?php esc_attr_e( 'Login' ); ?></a>
  <?php endif; ?>


</div>