# jsmysqldb
Access your mysql databases with pure Javascript

Send a GET or POST request to a PHP file on the server to get data from your database

See installation and examples below to get started

## Installation

Follow the instructions below to get started

1. Copy the contents of the /dist/server/ folder onto your server
2. Edit lines 18 to 21 of database.php that you copied onto the server
3. Edit the settings.json file that you copied to the server (see the settings section below)
4. Link to the JS file 'database.js' in your HTML body section
5. See examples to get started with reading your databases

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

- table : specifies the default table to use if no table is provided in the request config.  Case sensitive
- limit : specifies the default row limit to use if no limit is provided in the request config
- offset : specifies the default row offset to use if no offset is provided in the request config
- allowedTables: specifies the ist of tables that the script is allowed to access.  Case sensitive

## Examples

**Page Setup**

An example of how to use the extension to read the first 50 lines of the table 'Customers':
```html
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Example</title>

  <!-- Link to Bootstrap for some basic styling-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
</head>
<body>
  <h3>Read first 50 lines of table 'Customers'</h3>
  <div id="out"></div>
  <!-- Link to the database.js file -->
  <script src="js/database.js"></script>

  <script>
    // Config that will be sent to server
    const config = {
      "table" : "customers",
      "limit" : "50",
      "offset" : "0"
    };

    // Use the _POST_REQUEST function to send data to the server and get a response
    _POST_REQUEST("link-to-server/database.php", JSON.stringify(config), (response) => {
      // Now use the _json2table method to output the database "customers" to #out.  Note the second parameter of the function _json2table is for any classes you would like to apply to the table
      document.querySelector('#out').innerHTML = _json2table(JSON.parse(response), 'table table-striped');
    });
  </script>
</body>
</html>
```

**Using Config Values**

Example where the script will read the 'cities' table and get rows 10-40 (inclusive):
```js
const config = {
  "table" : "cities",
  "limit" : "30",
  "offset" : "9"
};

_POST_REQUEST("link-to-server/database.php", JSON.stringify(config), (response) => {
  document.querySelector('#out').innerHTML = _json2table(JSON.parse(response), 'table table-striped');
});
```

**Using WHERE IS**

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerName' is Bob:
```js
const config = {
  "table" : "customers",
  "limit" : "50",
  "offset" : "0"
};

_POST_REQUEST("link-to-server/database.php", JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerName', 'is', 'bob');

  document.querySelector('#out').innerHTML = _json2table(data, 'table table-striped');
});
```

**Using WHERE IN**

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerId' is 2,3,4,5 or 6.  Note that the \_range() function is used to generate the array:
```js
const config = {
  "table" : "customers",
  "limit" : "50",
  "offset" : "0"
};

_POST_REQUEST("link-to-server/database.php", JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerId', 'in', _range(2,7));

  document.querySelector('#out').innerHTML = _json2table(data, 'table table-striped');
});
```

**Using WHERE INCLUDES**
