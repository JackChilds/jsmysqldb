# jsmysqldb
Access your mysql databases with pure Javascript

Send a GET or POST request to a PHP file on the server to get data from your database

See installation and examples below to get started

## Installation

Follow the instructions below to get started

1. Copy the contents of the /dist/server/ folder onto your server
2. Edit lines 16 to 19 of database.php that you copied onto the server
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
    _POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
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

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  document.querySelector('#out').innerHTML = _json2table(JSON.parse(response));
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

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerName', 'is', 'bob');

  document.querySelector('#out').innerHTML = _json2table(data);
});
```

**Using WHERE IN**

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerId' in 2,3,4,5 or 6:  
```js
const config = {
  "table" : "customers",
  "limit" : "50",
  "offset" : "0"
};

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerId', 'in', _range(2,7));

  document.querySelector('#out').innerHTML = _json2table(data);
});
```
Note that the \_range() function is used to generate the array.

**Using WHERE INCLUDES**

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerAddress' includes 'London':
```js
const config = {
  "table" : "customers",
  "limit" : "50",
  "offset" : "0"
};

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerAddress', 'includes', 'London');

  document.querySelector('#out').innerHTML = _json2table(data);
});
```

**Using WHERE .NOT**

To use where not simply add the string '.not' to the end of the operation ('is', 'in' or 'include')

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerName' is not 'bob':
```js
const config = {
  "table" : "customers",
  "limit" : "50",
  "offset" : "0"
};

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerName', 'is.not', 'bob');

  document.querySelector('#out').innerHTML = _json2table(data);
});
```

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerId' not in 2,3,4,5 or 6:
```js
const config = {
  "table" : "customers",
  "limit" : "50",
  "offset" : "0"
};

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerId', 'in.not', _range(2,7));

  document.querySelector('#out').innerHTML = _json2table(data);
});
```
Note that the \_range() function is used to generate the array.

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerAddress' does not include 'London':
```js
const config = {
  "table" : "customers",
  "limit" : "50",
  "offset" : "0"
};

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  var data = _where(JSON.parse(response), 'CustomerAddress', 'includes.not', 'London');

  document.querySelector('#out').innerHTML = _json2table(data);
});
```

##Function Reference

Function|Description|Example
---|---|---
\_GET_REQUEST(url, responseCallback)|Use this function to send a GET request and get back the result.|`_GET_REQUEST('http://example.com', (response) => {/* Start coding here */})`
\_POST_REQUEST(url, parameters, responseCallback)|Use this function to send a POST request and get back the result.|`_POST_REQUEST('http://example.com', 'x=y', (response) => {/* Start coding here */})`
\_json2table(json, classes)|Use this function to generate a table from a parsed JSON object.  Classes parameter is not required.|`var table = _json2table(JSON.parse(response), 'table table-dark')`
\_array_contains(array, searchFor, caseSensitive)|Use this function to quickly check if an array contains a value.  Returns true of false.|`if (_array_contains(myArray, "Some text", false)) {/* Do something */}`
\_range(start, edge, step)|Use this function to generate an array.  Start inclusive and edge exclusive.  Step defaults to 1.|`var numbers = _range(1, 10, 1)`
\_where(data, columnName, operation, match, caseSensitive)|Use this function to select rows from the data.  Column name is always case sensitive.  Case sensitive only applies to the match variable.  If case sensitive is not provided, it will default to false.  Operation can be: 'is', 'in', 'includes', 'is.not', 'in.not' or 'includes.not'.|`var bobs = _where(data, 'customerName', 'includes', 'Bob', false)`
