# jsmysqldb

## v1.2

Access your mysql databases with pure Javascript

This extension allows you to read SQL databases on your server with Javascript, by sending a POST request to a PHP file on the server.  It should be simple to use so read the installation and examples below to get started.

## Contents

- [Notes](#notes)
- [Disclousure](#disclosure)
- [Installation](#installation)
- [Settings](#settings)
- [Examples](#examples)
- [Function Reference](#function-reference)
- [Troubleshooting](#troubleshooting)

## Notes

- You must **explicitly** specify that the a table is allowed to be accessed
- When the JSON is parsed in JS, data can be accessed in the following way: `data[row][columnName]`
- Use PHP where statements (see examples) if you want to search the data on the server, whereas use JS where statements (see examples) if you want to use the end user's device to search the data

## Disclosure

Although I have tried to make it as secure as possible, by using prepared statements, input validation methods, and ways to make sure that you can't read a different table (see settings.json 'allowedTables' array), I cannot guarantee complete security, if you do find an problem then please open an issue, and if you have then knowledge it would be great if you could submit a pull request that will fix this issue.

## Installation

Follow the instructions below to get started

1. Copy the contents of the /dist/server/ folder onto your server
2. Edit lines 16 to 20 of database.php that you copied onto the server, filling it out with the details of your mysql server
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
  "where": "false",
  "allowedTables": ["table"]
}
```

**Properties**

- table : specifies the default table to use if no table is provided in the request config.  Case sensitive
- limit : specifies the default row limit to use if no limit is provided in the request config
- offset : specifies the default row offset to use if no offset is provided in the request config
- where : specifies the default where condition to use, false just means don't use a where statement by default.  This is the property that will be used if no where key is provided in the config
- allowedTables: specifies the list of tables that the script is allowed to access.  Case sensitive

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
### Using JS *\_where(data, colname, operation, match, caseSensitive)* function

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

### Using PHP where

To use PHP where then add the 'where' key to the config that is sent to the server.
Note that the 'limit' is the amount of rows read before the database is searched, so if your database has 100 rows, you set the limit to 50, the where statement only applies to the 50 rows that are read, so it can return less than 50 rows

A where statement can be made using the following syntax:

*column name*;*operation*;*match*;*case sensitive*

Operations you can use: 'is', 'is.not', 'in', 'in.not', 'includes', 'includes.not'
Case sensitivity defaults to false (so you can leave it out if you want).  Column name is always case sensitive.

**E.g**

Where customer name includes 'Jack' (not case sensitive): `'CustomerName;includes;Jack'`

Where customer ID is not '145' (not case sensitive): `'CustomerID;is.not;145'`

Where customer city not in \['London', 'Manchester', 'New York'] (case sensitive): `'CustomerCity;in.not;;true'`.  'whereArray': `['London', 'Manchester', 'New York']`

Below are a few examples of using this syntax

**WHERE IS**

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'Name' is 'Bob' (case sensitive):
```js
const config = {
  "table": "customers",
  "limit": "50",
  "offset": "0",
  "where": "Name;is;Bob;true"
}

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  document.querySelector('#out').innerHTML = _json2table(JSON.parse(response));
});
```

**WHERE IN**

Example where the script will read the 'customers' table (limit:50,offset:0) then output all rows where 'CustomerId' in 2,3,4,5 or 6 (case sensitive).  Note when using the operation 'in' or 'not.in' then you should add an extra key to the config called 'whereArray' to hold the array, and leave match part of the statement empty:
```js 
const config = {
  "table": "customers",
  "limit": "50",
  "offset": "0",
  "where": "Name;in;;true",
  "whereArray": [2,3,4,5,6]
}

_POST_REQUEST("link-to-server/database.php", 'config=' + JSON.stringify(config), (response) => {
  document.querySelector('#out').innerHTML = _json2table(JSON.parse(response));
});
```

## Function Reference

Function|Description|Example
---|---|---
\_GET_REQUEST(url, responseCallback)|Use this function to send a GET request and get back the result.|`_GET_REQUEST('http://example.com', (response) => {/* Start coding here */})`
\_POST_REQUEST(url, parameters, responseCallback)|Use this function to send a POST request and get back the result.|`_POST_REQUEST('http://example.com', 'x=y', (response) => {/* Start coding here */})`
\_json2table(json, classes)|Use this function to generate a table from a parsed JSON object.  Classes parameter is not required.|`var table = _json2table(JSON.parse(response), 'table table-dark')`
\_array_contains(array, searchFor, caseSensitive)|Use this function to quickly check if an array contains a value.  Returns true or false.|`if (_array_contains(myArray, "Some text", false)) {/* Do something */}`
\_range(start, edge, step)|Use this function to generate an array.  Start inclusive and edge exclusive.  Step defaults to 1.|`var numbers = _range(1, 10, 1)`
\_where(data, columnName, operation, match, caseSensitive)|Use this function to select rows from the data.  Column name is always case sensitive.  Case sensitive only applies to the match variable.  If case sensitive is not provided, it will default to false.  Operation can be: 'is', 'in', 'includes', 'is.not', 'in.not' or 'includes.not'.|`var bobs = _where(data, 'customerName', 'includes', 'Bob', false)`
\_get_data_cols(data)|Use this function to get an array of all the columns in the data.|`var columns = _get_data_cols(data)`

## Troubleshooting

- Make sure that the table name, column names and any other case sensitive details are set correctly
- Make sure that you have explicitly placed the table name for your database in the settings.json file on the server
- Check the database connection establishment details in database.php on the server
- Open a new issue in this repository
