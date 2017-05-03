<?php

class WP_Salesforce_Admin_Page {

  public function __construct() {
    add_action( 'admin_menu', array( $this, 'add_options_page' ) );
    add_action( 'admin_init', array( $this, 'consumer_info_settings' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'add_options_page_css' ) );
  }

  public function add_options_page_css( $hook ) {
    if( $hook == 'settings_page_wp-salesforce' ) {
      wp_register_style( SALESFORCE__PLUGIN_NAME, SALESFORCE__PLUGIN_URL . 'admin/css/wp-salesforce.css', false, '1.0.0' );
      wp_enqueue_style( SALESFORCE__PLUGIN_NAME );
    }
  }

  public function add_options_page() {
    add_submenu_page( 'options-general.php', 'WP Salesforce', 'WP Salesforce', 'manage_options', 'wp-salesforce', array(
      $this,
      'options_page_output'
    ));
  }

  public function options_page_output() {

    if( !current_user_can( 'manage_options' ) ) {
      wp_die( 'You do not have the permissions to edit this page.' );
    }

    include SALESFORCE__PLUGIN_DIR . '/admin/templates/wp-salesforce-form.php';
  }

  public function consumer_info_settings() {
    register_setting( 'wp_salesforce_keys', 'salesforce_consumer_key' );
    register_setting( 'wp_salesforce_keys', 'salesforce_consumer_secret' );

    add_settings_section( 'wp_salesforce_keys', 'Salesforce Consumer Keys', array($this, 'consumer_info_section'), 'wp-salesforce' );

    add_settings_field( 'salesforce_consumer_key', 'Consumer Key', array($this, 'consumer_key_field'), 'wp-salesforce', 'wp_salesforce_keys' );
    add_settings_field( 'salesforce_consumer_secret', 'Consumer Secret', array($this, 'consumer_secret_field'), 'wp-salesforce', 'wp_salesforce_keys' );
  }

  public function consumer_info_section() {

  }

  public function consumer_key_field() {
    $consumer_key = ( get_option('salesforce_consumer_key') ) ? get_option('salesforce_consumer_key') : '';
    $html = '<input type="text" name="salesforce_consumer_key" id="salesforce_consumer_key" value="' . $consumer_key . '" class="large-text">';

    echo $html;
  }

  public function consumer_secret_field() {
    $consumer_secret = ( get_option('salesforce_consumer_secret') ) ? get_option('salesforce_consumer_secret') : '';
    $html = '<input type="text" name="salesforce_consumer_secret" id="salesforce_consumer_secret" value="' . $consumer_secret . '"class="regular-text">';

    echo $html;
  }
}