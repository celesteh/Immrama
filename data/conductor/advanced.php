<!DOCTYPE html>
<?php
$conf = parse_ini_file("config.ini", true);
$filename = $conf['working']['data'] .'/conductor/advanced.php';
 ?>
<html>
<head>
  <link rel="stylesheet" href="/style.css" type="text/css" />
  <link rel="stylesheet" href="/color.css" type="text/css" />
  <title>Advanced Options</title>
  </head>
  <body>
  <div id="words">

  <h2>Change Password</h2>
  <form action="passwd.php" method="post">

    <?php
    //if file_exists($filename) {
    echo "<p>$filename</p>"
      echo "<p><label for \"passwd\">Old Conductor password:</label>\n";
      echo "<input type=\"password\" name=\"passwd\"></p>\n";
    //}

    ?>
  
    <p>
      <label for="newpass">New Password</label>
      <input type="password" name="newpass">
    </p>
    <p>
      <label for="confirm">Confirm New Password</label>
      <input type="password" name="confirm">
    </p>
    <p><BUTTON name="submit" value="submit" type="submit">Change</BUTTON></p>
  </form>
  </div>
  </body>
  </html>
