# WP Salesforce

## Setup 
You will need:
- A Salesforce account
- A client key from Salesforce
- A client secret from Salesforce

Also your site will need to have SSL.

## Usage
Once the plugin is enabled and the connection is established, in your theme or plugin you can create a new instance of WP_Salesforce_Connection.

`$salesforce = new WP_Salesforce_Connection();`

To run a query, use the query method:

`$salesforce->query('MY SALESFORCE QUERY');`