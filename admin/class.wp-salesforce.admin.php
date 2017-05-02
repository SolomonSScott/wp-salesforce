<?php

class WP_Salesforce_Admin {

  public function __construct() {
    add_filter('query_vars', array( $this, 'create_query_vars' ));
    add_action( 'init', array( $this, 'add_rewrite_rules' ) );
    add_action( 'parse_request', array( $this, 'parse_login_request') );
  }

  public function add_rewrite_rules() {
    // TODO: Add flush_rewrite_rules() to install
    add_rewrite_tag('%salesforce-login%', '([^&]+)');
    add_rewrite_rule('salesforce-login/?$', 'index.php?salesforce-login=salesforce-login', 'top');

    add_rewrite_tag('%salesforce-callback%', '([^&]+)');
    add_rewrite_rule('salesforce-callback/?$', 'index.php?salesforce-callback=salesforce-callback', 'top');
  }

  public function create_query_vars( $vars ) {
    $vars[] = 'salesforce-login';
    $vars[] = 'salesforce-callback';
    return $vars;
  }

  public function parse_login_request( &$wp ) {
    if ( array_key_exists( 'salesforce-login', $wp->query_vars ) ) {
      include(SALESFORCE__PLUGIN_DIR . 'admin/salesforce-login.php');
      exit();
    }
    if ( array_key_exists( 'salesforce-callback', $wp->query_vars ) ) {
      include(SALESFORCE__PLUGIN_DIR . 'admin/salesforce-callback.php');
      exit();
    }
    return;
  }
}