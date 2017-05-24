<?php

class WP_Salesforce_Admin {

  public function init() {
    add_filter( 'query_vars', array( $this, 'create_query_vars' ) );
    add_action( 'init', array( $this, 'add_rewrite_rules' ) );
    add_action( 'parse_request', array( $this, 'parse_login_request') );
  }

  /**
   * Add salesforce login and salesforce callback
   * as virtual pages to WordPress
   */
  public function add_rewrite_rules() {
    add_rewrite_tag('%salesforce-login%', '([^&]+)');
    add_rewrite_rule('salesforce-login/?$', 'index.php?salesforce-login=salesforce-login', 'top');

    add_rewrite_tag('%salesforce-callback%', '([^&]+)');
    add_rewrite_rule('salesforce-callback/?$', 'index.php?salesforce-callback=salesforce-callback', 'top');
  }

  /**
   * Set query to create virtual pages
   * @param $vars
   * @return array
   */
  public function create_query_vars( $vars ) {
    $vars[] = 'salesforce-login';
    $vars[] = 'salesforce-callback';
    return $vars;
  }

  /**
   * Add custom templates for salesforce
   * login and callback urls
   * *@param $wordpress
   */
  public function parse_login_request( &$wordpress ) {
    if ( array_key_exists( 'salesforce-login', $wordpress->query_vars ) ) {
      include(SALESFORCE__PLUGIN_DIR . 'admin/salesforce-login.php');
      exit();
    }
    if ( array_key_exists( 'salesforce-callback', $wordpress->query_vars ) ) {
      include(SALESFORCE__PLUGIN_DIR . 'admin/salesforce-callback.php');
      exit();
    }
    return;
  }
}