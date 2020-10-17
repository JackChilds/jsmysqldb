<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  header("Content-Type: application/json; charset=UTF-8");

  function checkTable($inputTable) {
    $settings = json_decode(file_get_contents("settings.json"), false);

    if (in_array($inputTable, $settings->allowedTables)) {
      return $inputTable;
    } else {
      return $settings->table;
    }
  }

  function getData($tableToRead, $config) {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "varjs";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT * FROM " . $tableToRead . " LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $config->limit, $config->offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return json_encode($result->fetch_all(MYSQLI_ASSOC));

    $stmt->close();
    $conn->close();
  }

  if (isset($_REQUEST["config"])) {
    $config = json_decode($_REQUEST["config"], false);
    $_settingsConfig = json_decode(file_get_contents("settings.json"), false);
    if (!isset($config->table)) {
      $config->table = $_settingsConfig->table;
    }
    if (!isset($config->limit)) {
      $config->limit = $_settingsConfig->limit;
    }
    if (!isset($config->offset)) {
      $config->offset = $_settingsConfig->offset;
    }
  } else {
    $config = json_decode(file_get_contents("settings.json"), false);
  }

  echo getData(checkTable($config->table), $config);
?>
