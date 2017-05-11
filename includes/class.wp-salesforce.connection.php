<?php

class WP_Salesforce_Connection {

  /**
   * @var mixed|void
   */
  private $access_token;

  /**
   * @var mixed|void
   */
  private $instance_url;

  public function __construct() {
    $this->access_token = get_option('salesforce_access_token');
    $this->instance_url = get_option('salesforce_instance_url');
  }

  /**
   * Build and fetch data based on query
   * @param $query
   * @return array|mixed|object|string
   */
  public function query( $query ) {

    if( empty($this->access_token) || empty($this->instance_url) ) {
      return 'Login to Salesforce in order to make queries.';
    }

    $url = $this->instance_url . "/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
      array("Authorization: OAuth " . $this->access_token));

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);

    return $response;
  }

}