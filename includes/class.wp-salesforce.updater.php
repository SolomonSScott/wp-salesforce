<?php

class WP_Salesforce_Updater {

  /**
   * @var
   */
  private $plugin;

  /**
   * @var
   */
  private $basename;

  /**
   * @var string
   */
  private $username = 'SolomonSScott';

  /**
   * @var string
   */
  private $repository = 'wp-salesforce';

  /**
   * @var
   */
  private $github_response;

  /**
   * @var
   */
  private $active;

  public function __construct() {
    add_action( 'admin_init', array( $this, 'set_plugin_properties' ) );
  }

  /**
   * Set the plugin properties when initialized
   */
  public function set_plugin_properties() {
    $this->plugin	= get_plugin_data( SALESFORCE__PLUGIN_FILE );
    $this->basename = plugin_basename( SALESFORCE__PLUGIN_FILE );
    $this->active	= is_plugin_active( $this->basename );
  }

  public function init() {
    add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_transient' ), 10, 1 );
    add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3);
    add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
  }

  /**
   * Fetch Github repository info
   * and save the response
   */
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

  /**
   * Check if the plugin is out of date
   * and update transient
   * @param $transient
   * @return mixed
   */
  public function modify_transient( $transient ) {
    if( property_exists( $transient, 'checked') ) {
      if( $checked = $transient->checked ) {
        $this->get_repository_info();
        $out_of_date = version_compare( $this->github_response['tag_name'], $checked[ $this->basename ], 'gt' );
        if( $out_of_date ) {
          $new_files = $this->github_response['zipball_url'];
          $slug = current( explode('/', $this->basename ) );
          $plugin = array(
            'url' => $this->plugin["PluginURI"],
            'slug' => $slug,
            'package' => $new_files,
            'new_version' => $this->github_response['tag_name']
          );
          $transient->response[$this->basename] = (object) $plugin; // Return it in response
        }
      }
    }
    return $transient;
  }

  /**
   * Modify plugin modal to pull information from github
   * @param $result
   * @param $action
   * @param $args
   * @return object
   */
  public function plugin_popup( $result, $action, $args ) {
    if( ! empty( $args->slug ) ) { // If there is a slug
      if( $args->slug == current( explode( '/' , $this->basename ) ) ) {
        $this->get_repository_info();
        $plugin = array(
          'name'				=> $this->plugin["Name"],
          'slug'				=> $this->basename,
          'requires'		=> '3.3',
          'tested'			=> '4.7.4',
          'rating'			=> '100.0',
          'num_ratings'	=> '',
          'downloaded'	=> '',
          'added'				=> '2017-05-8',
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

  /**
   * Change plugin files to updated version
   * @param $response
   * @param $hook_extra
   * @param $result
   * @return mixed
   */
  public function after_install( $response, $hook_extra, $result ) {
    global $wp_filesystem;
    $install_directory = SALESFORCE__PLUGIN_DIR;
    $wp_filesystem->move( $result['destination'], $install_directory );
    $result['destination'] = $install_directory;
    if ( $this->active ) {
      activate_plugin( $this->basename );
    }
    return $result;
  }

}