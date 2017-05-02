<?php

$client_id = get_option('salesforce_consumer_key');

$auth_url = SALESFORCE__LOGIN_URI
  . "/services/oauth2/authorize?response_type=code&client_id="
  . $client_id . "&redirect_uri=" . urlencode(SALESFORCE__REDIRECT_URI);

header('Location: ' . $auth_url);