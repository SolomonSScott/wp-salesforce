<?php
/*
 * Plugin Name: WP Salesforce
 * Plugin URI: https://github.com/SolomonSScott/wp-salesforce/
 * Description: Pull information from Salesforce
 * Version: 1.2.0
 * Author: Solomon Scott
 * Author URI: http://solomonscott.com/
 * License: GPL2+
 * Text Domain: wp-salesforce
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

define( 'SALESFORCE__VERSION', '1.2.0' );
define( 'SALESFORCE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SALESFORCE__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SALESFORCE__PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'SALESFORCE__PLUGIN_FILE', __FILE__ );
define( 'SALESFORCE__PLUGIN_NAME', 'wp-salesforce' );

// Salesforce Specific Globals
define( "SALESFORCE__REDIRECT_URI", site_url('/salesforce-callback', 'https') );
define( "SALESFORCE__LOGIN_URI", "https://login.salesforce.com" );

require_once SALESFORCE__PLUGIN_DIR . 'includes/class.wp-salesforce.php';
require_once SALESFORCE__PLUGIN_DIR . 'includes/class.wp-salesforce.connection.php';

register_activation_hook( SALESFORCE__PLUGIN_FILE, array('WP_Salesforce', 'activate') );
register_deactivation_hook( SALESFORCE__PLUGIN_FILE, array('WP_Salesforce', 'deactivate') );

WP_Salesforce::init();