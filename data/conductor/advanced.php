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

  <form action="passwd.php" method="post">
    <h2>Change Password</h2>

    <?php
    echo "<p>$filename</p>"
    //if (file_exists($filename)) {
    //  echo "<p>exists</p>"
    //  echo "<p><label for \"passwd\">Old Conductor password:</label>\n";
    //  echo "<input type=\"password\" name=\"passwd\"></p>\n";
    //}

    if (file_exists($filename)) {
    echo "The file $filename exists";
} else {
    echo "The file $filename does not exist";
}

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
