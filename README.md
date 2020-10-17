# jsmysqldb
Access your mysql databases with pure Javascript

Send a GET or POST request to a PHP file on the server to get data from your database

See installation and examples below to get started

## Installation

Follow the instructions below to get started

1. Copy the contents of the /dist/server/ folder onto your server
2. Edit the settings.json file that you copied to the server (see the settings section below)
3. Link to the JS file 'database.js' in your HTML body section
4. See examples to get started with reading your databases

## Settings

**Default Config:**

```json
{
  "table": "table",
  "limit": "100",
  "offset": "0",
  "allowedTables": ["table"]
}
```

**Properties**

table : specifies the default table to use if no table is provided in the request config
limit : specifies the default row limit to use if no limit is provided in the request config
offset : specifies the default row offset to use if no offset is provided in the request config
allowedTables: specifies the ist of tables that the script is allowed to access
