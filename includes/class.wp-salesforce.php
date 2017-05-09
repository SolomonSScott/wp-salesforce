<?php

class WP_Salesforce {

  public static $instance = FALSE;

  public function __construct() {
    $this->load_dependencies();
    $this->updater();
    $this->admin_init();
  }

  public static function init() {
    if ( ! self::$instance ) {
      self::$instance = new WP_Salesforce;
    }
    return self::$instance;
  }

  private function load_dependencies() {
    require_once SALESFORCE__PLUGIN_DIR . '/includes/class.wp-salesforce.updater.php';
    require_once SALESFORCE__PLUGIN_DIR . '/admin/class.wp-salesforce.admin-page.php';
    require_once SALESFORCE__PLUGIN_DIR . '/admin/class.wp-salesforce.admin.php';
  }

  private function updater() {
    $updater = new WP_Salesforce_Updater();
    $updater->init();
  }

  private function admin_init() {
    $admin_page = new WP_Salesforce_Admin_Page();
    $admin_urls = new WP_Salesforce_Admin();
    $admin_urls->init();
  }

  public function rewrite_rules() {
    $rewrite_rules = new WP_Salesforce_Admin();
    $rewrite_rules->add_rewrite_rules();
  }

  public static function activate() {
    self::rewrite_rules();
    flush_rewrite_rules();
  }

  public static function deactivate() {
    flush_rewrite_rules();
  }
}