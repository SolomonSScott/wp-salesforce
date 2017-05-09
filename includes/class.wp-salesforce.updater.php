<?php

class WP_Salesforce_Updater {

  private $plugin;

  private $username = 'SolomonSScott';

  private $repository = 'wp-salesforce';

  private $github_response;

  private $active;

  public function __construct() {
    add_action( 'admin_init', array( $this, 'set_plugin_properties' ) );
  }

  public function set_plugin_properties() {
    $this->plugin	= get_plugin_data( SALESFORCE__PLUGIN_FILE );
    $this->active	= is_plugin_active( SALESFORCE__PLUGIN_FILE );
  }

  public function init() {
    add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_transient' ), 10, 1 );
    add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3);
    add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
  }

  private function get_repository_info() {
    if ( is_null( $this->github_response ) ) {
      $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository );

      $response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true );
      if( is_array( $response ) ) {
        $response = current( $response );
      }
      $this->github_response = $response;
    }
  }

  public function modify_transient( $transient ) {
    if( property_exists( $transient, 'checked') ) {
      if( $checked = $transient->checked ) {
        $this->get_repository_info();
        $out_of_date = version_compare( $this->github_response['tag_name'], $checked[ SALESFORCE__PLUGIN_FILE ], 'gt' );
        if( $out_of_date ) {
          $new_files = $this->github_response['zipball_url'];
          $slug = current( explode('/', SALESFORCE__PLUGIN_FILE ) );
          $plugin = array(
            'url' => $this->plugin["PluginURI"],
            'slug' => $slug,
            'package' => $new_files,
            'new_version' => $this->github_response['tag_name']
          );
          $transient->response[SALESFORCE__PLUGIN_FILE] = (object) $plugin; // Return it in response
        }
      }
    }
    return $transient;
  }

  public function plugin_popup( $result, $action, $args ) {
    if( ! empty( $args->slug ) ) { // If there is a slug
      if( $args->slug == current( explode( '/' , SALESFORCE__PLUGIN_FILE ) ) ) {
        $this->get_repository_info();
        $plugin = array(
          'name'				=> $this->plugin["Name"],
          'slug'				=> $this->basename,
          'requires'					=> '3.3',
          'tested'						=> '4.7.4',
          'rating'						=> '100.0',
          'num_ratings'				=> '10823',
          'downloaded'				=> '14249',
          'added'							=> '2016-01-05',
          'version'			=> $this->github_response['tag_name'],
          'author'			=> $this->plugin["AuthorName"],
          'author_profile'	=> $this->plugin["AuthorURI"],
          'last_updated'		=> $this->github_response['published_at'],
          'homepage'			=> $this->plugin["PluginURI"],
          'short_description' => $this->plugin["Description"],
          'sections'			=> array(
            'Description'	=> $this->plugin["Description"],
            'Updates'		=> $this->github_response['body'],
          ),
          'download_link'		=> $this->github_response['zipball_url']
        );
        return (object) $plugin;
      }
    }
    return $result;
  }

  public function after_install( $response, $hook_extra, $result ) {
    global $wp_filesystem;
    $install_directory = plugin_dir_path( SALESFORCE__PLUGIN_FILE );
    $wp_filesystem->move( $result['destination'], $install_directory );
    $result['destination'] = $install_directory;
    if ( $this->active ) {
      activate_plugin( SALESFORCE__PLUGIN_FILE );
    }
    return $result;
  }

}