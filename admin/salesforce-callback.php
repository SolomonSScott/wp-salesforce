<?php

session_start();

$token_url = SALESFORCE__LOGIN_URI . "/services/oauth2/token";

$client_id = get_option('salesforce_consumer_key');
$client_secret = get_option('salesforce_consumer_secret');

// Get login code
$code = $_GET['code'];

if (!isset($code) || $code == "") {
  die("Error - code parameter missing from request!");
}

// Build cURL paramerters
$params = "code=" . $code
  . "&grant_type=authorization_code"
  . "&client_id=" . $client_id
  . "&client_secret=" . $client_secret
  . "&redirect_uri=" . urlencode(SALESFORCE__REDIRECT_URI);

// Start cURL to get access token and instance url
$curl = curl_init($token_url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

$json_response = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// If there is an error let user know why
if ( $status != 200 ) {
  die("Error: call to token URL $token_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
}

curl_close($curl);

// Decode response
$response = json_decode($json_response, true);

$access_token = $response['access_token'];
$instance_url = $response['instance_url'];

if (!isset($access_token) || $access_token == "") {
  die("Error - access token missing from response!");
}

if (!isset($instance_url) || $instance_url == "") {
  die("Error - instance URL missing from response!");
}

$_SESSION['access_token'] = $access_token;
$_SESSION['instance_url'] = $instance_url;

// Add access token to options table
update_option( 'salesforce_access_token', $access_token );
update_option( 'salesforce_instance_url', $instance_url );

// Let user hook into successful connection
do_action( 'wp_salesforce_connection' );

// Redirect user back to options page
header('Location: ' . admin_url('options-general.php?page=wp-salesforce', 'https'));