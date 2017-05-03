# WP Salesforce

## Setup 
Be sure to:
- Obtain a client key and client secret from Salesforce.
- Add the site url to the list of white-listed urls in Salesforce.
- Add "https://YOURSITENAME/salesforce-callback" to the Callback URL in your app's Salesforce OAuth settings.
- Select "Access and Manage your data(api), Full Access(full), Perform requests on your behalf at any time (refresh_token, offline_access)" in your app's Selected OAuth Scopes.

## Usage
Once the plugin is enabled and the connection is established, in your theme or plugin you can create a new instance of WP_Salesforce_Connection.

`$salesforce = new WP_Salesforce_Connection();`

To run a query, use the query method:

`$salesforce->query('MY SALESFORCE QUERY');`