# WP Salesforce

## Setup 
You will need:
- A Salesforce account
- A client key from Salesforce
- A client secret from Salesforce

Your site will also need to have SSL enabled.

## Usage
Once the plugin is enabled and the connection is established, in your theme or plugin you can create a new instance of WP_Salesforce_Connection.

`$salesforce = new WP_Salesforce_Connection();`

To run a query, use the query method:

`$salesforce->query('MY SALESFORCE QUERY');`

This returns an array with the total size and records.

```
array(
  'totalSize' => 5
  'done' => true
  'records' => array()
)
```

## Actions
There is an action that is fired once Salesforce is connected.

`add_action('wp_salesforce_connection', 'YOUR_FUNCTION_HERE');`

## Recommendations
It would be best to store the result in a transient or in the options table. Once the session is closed the query will not run.