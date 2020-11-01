<?php
  // This is an install script and should be deleted after it has been run
  $installPerformed = false;

  if (isset($_POST["installSubmit"])) {
    $servername = $_POST["servername"];
    $username = $_POST["username"];
    if (!isset($_POST["password"])) {
      $password = "";
    } else {
      $password = $_POST["password"];
    }
    $dbname = $_POST["dbname"];
    $port = $_POST["port"];

    $settingsConfig = $_POST["settings"];

    $databaseFileContents = file_get_contents('database-php-1.txt') . "\$servername = '" . $servername . "';\$username = '" . $username . "';\$password = '" . $password . "';\$dbname = '" . $dbname . "';\$port = '" . $port . "';" . file_get_contents('database-php-2.txt');

    file_put_contents('../database.php', $databaseFileContents);

    file_put_contents("../settings.json", $settingsConfig);

    $installPerformed = true;
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Install</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="codemirror/codemirror.css">
  <script src="codemirror/codemirror.js"></script>
  <script src="codemirror/javascript.js"></script>


  <script type="text/javascript">
    var installPerformed = <?php echo $installPerformed; ?>;
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
</head>
<body>
  <div class="container">
    <div class="bg-info p-5 rounded-sm mt-1">
      <h1>JSMYSQLDB</h1>
      <p>Thank you for downloading JSMYSQLDB</p>
      <p>Please complete fill out the details of your server below to get started with this program</p>
      <p><b>Note: </b>this <kbd>install</kbd> folder should be removed manually after this install is complete</p>
      <br>
    </div>
    <br>
    <div class="bg-warning p-5 rounded-sm mb-1">
      <form method="post" class="needs-validation" novalidate>
        <p><b>Please fill out the configuration of your server below:</b></p>
        <div class="form-group">
          <label for="servername">Servername:</label>
          <input type="text" name="servername" class="form-control" placeholder="localhost" required>
          <div class="invalid-feedback">Please fill out this field correctly</div>
        </div>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" name="username" class="form-control" placeholder="username" required>
          <div class="invalid-feedback">Please fill out this field correctly</div>
        </div>
        <div class="form-group">
          <label for="password">Password (leave empty if there isn't one):</label>
          <input type="password" name="password" class="form-control" placeholder="password">
          <div class="invalid-feedback">Please fill out this field correctly</div>
        </div>
        <div class="form-group">
          <label for="dbname">Database Name:</label>
          <input type="text" name="dbname" class="form-control" placeholder="myDatabase" required>
          <div class="invalid-feedback">Please fill out this field correctly</div>
        </div>
        <div class="form-group">
          <label for="port">Server Port:</label>
          <input type="number" name="port" class="form-control" placeholder="3306" required>
          <div class="invalid-feedback">Please fill out this field correctly</div>
        </div>

        <br>
        <div class="form-group">
          <p>Please edit the settings configuration here:</p>
          <textarea name="settings" id="settingsjsoneditor"><?php echo file_get_contents("../settings.json"); ?></textarea>
        </div>
        <br>
        <input type="submit" value="Submit Configuration" class="btn btn-info btn-lg" name="installSubmit">
      </form>
    </div>
  </div>

  <script type="text/javascript">
    var settingsjsoneditor = CodeMirror.fromTextArea(document.querySelector('#settingsjsoneditor'), {
      mode:  "javascript",
      lineNumbers: true,
      matchBrackets: true
    });
  </script>

  <script>
    // Disable form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Get the forms we want to add validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>

  <script type="text/javascript">
    if (installPerformed) {
      swal({
        title: "Install Complete",
        text: "You should now delete this install directory",
        icon: "success",
        button: "Ok",
      });
    }
  </script>
</body>
</html>
