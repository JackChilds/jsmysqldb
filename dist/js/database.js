if (!String.prototype.includes) {
  String.prototype.includes = (search, start) => {
    'use strict';

    if (search instanceof RegExp) {
      throw TypeError('first argument must not be a RegExp');
    }
    if (start === undefined) { start = 0; }
    return this.indexOf(search, start) !== -1;
  };
}

function _where(data, colname, operation, match, caseSensitive) {
  if (caseSensitive == undefined) {caseSensitive = false;}
  var columns = _get_data_cols(data);
  const operations = ["is", "in", "includes", "is.not", "in.not", "includes.not"];

  var colNameIsOk = _array_contains(columns, colname, true);
  var operationIsOk = _array_contains(operations, operation, false);

  var returnArray = [];

  if (colNameIsOk && operationIsOk) {
    switch (operation.toLowerCase()) {
      case "is":
        for (var i = 0; i < data.length; i++) {
          if (data[i][colname] == match) {
            returnArray.push(data[i]);
          } else {
            if (data[i][colname].toLowerCase() == match.toLowerCase() && !caseSensitive) {
              returnArray.push(data[i]);
            }
          }
        }
        break;
      case "in":
        for (var i = 0; i < data.length; i++) {
          if (_array_contains(match, data[i][colname], caseSensitive)) {
            returnArray.push(data[i]);
          }
        }
        break;
      case "includes":
        for (var i = 0; i < data.length; i++) {
          if (String(data[i][colname]).includes(String(match))) {
            returnArray.push(data[i]);
          } else {
            if (String(data[i][colname]).toLowerCase().includes(String(match).toLowerCase()) && !caseSensitive) {
              returnArray.push(data[i]);
            }
          }
        }
        break;
      case "is.not":
        for (var i = 0; i < data.length; i++) {
          if (data[i][colname] != match && caseSensitive) {
            returnArray.push(data[i]);
          } else {
            if (data[i][colname].toLowerCase() != match.toLowerCase() && !caseSensitive) {
              returnArray.push(data[i]);
            }
          }
        }
        break;
      case "in.not":
        for (var i = 0; i < data.length; i++) {
          if (!_array_contains(match, data[i][colname], caseSensitive)) {
            returnArray.push(data[i]);
          }
        }
        break;
      case "includes.not":
        for (var i = 0; i < data.length; i++) {
          if (String(data[i][colname]).includes(String(match))) {
            continue;
          } else {
            if (String(data[i][colname]).toLowerCase().includes(String(match).toLowerCase()) && !caseSensitive) {
              continue;
            } else {
              returnArray.push(data[i]);
            }
          }
        }
        break;
    }
  }
  return returnArray;
}

function _get_data_cols(data) {
  return Object.keys(data[0]);
}

function _array_contains(arr, searchFor, caseSensitive) {
  for (var i = 0; i < arr.length; i++) {
    if (arr[i] == searchFor) {
      return true;
    } else if (String(arr[i]).toLowerCase() == String(searchFor).toLowerCase() && !caseSensitive) {
      return true;
    }
  }
  return false;
}

function _range(start, edge, step) {
  if (arguments.length === 1) {
    edge = start;
    start = 0;
  }

  edge = edge || 0;
  step = step || 1;

  let arr = [];
  for (arr; (edge - start) * step > 0; start += step) {
    arr.push(start);
  }
  return arr;
}

function _json2table(json, classes) {
  var cols = Object.keys(json[0]);

  var headerRow = '';
  var bodyRows = '';

  classes = classes || '';

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  cols.map(function(col) {
    headerRow += '<th>' + capitalizeFirstLetter(col) + '</th>';
  });

  json.map(function(row) {
    bodyRows += '<tr>';

    cols.map(function(colName) {
      bodyRows += '<td>' + row[colName] + '</td>';
    })

    bodyRows += '</tr>';
  });

  return '<table class="' +
         classes +
         '"><thead><tr>' +
         headerRow +
         '</tr></thead><tbody>' +
         bodyRows +
         '</tbody></table>';
}

function _GET_REQUEST(url, response) {
  var xhttp;
  if (window.XMLHttpRequest) {
    xhttp = new XMLHttpRequest();
  } else {
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      response(this.responseText);
    }
  };

  xhttp.open("GET", url, true);
  xhttp.send();
}

function _POST_REQUEST(url, params, response) {
  var xhttp;
  if (window.XMLHttpRequest) {
    xhttp = new XMLHttpRequest();
  } else {
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      response(this.responseText);
    }
  };

  xhttp.open("POST", url, true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send(params);
}
