<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../style.css" type="text/css" />
  <link rel="stylesheet" href="../color.css" type="text/css" />
  <title>Shutdown</title>
  </head>
  <body>
  <div id="words">
    <div class="dropdown">
      <button class="dropbtn">&#8801;</button>
     <div class="dropdown-content">
       <a href="../">Home</a>
       <a href="./">(Re)Start Piece</a>
      <a href="./piece.html">Show Piece as Conductor</a>
      <a href="../piece.html">Show piece as Player</a>
      <a href="../wifi.php">Show WiFi Password</a>
      <a href="./advanced.html">Set WiFi Password</a>
      <a href="./setintro.php">Set introductory text</a>
    </div>
  </div>
  <main class="main">
  <form action="shutdown.php" method="post">
    <h2>Shutdown the computer</h2>
    <p>After you hit submit, your browser should fail to load the next page.</p>
    <p>There will be a short delay before the comuter turns off.
      Please wait a few moments before unplugging the power supply.
      The shutdown computer may have a solid red light.
      Some Pis may reboot instead of shutting down. If this happens,
      try to unplug the machine while the red light is on.
    </p>
    <p>To Restart the machine after shutting down, unplug it and then plug it back in.</p>
    <p><input type="hidden" name="timestamp" value=<?php $date=date_create();
    echo date_timestamp_get($date); ?> /></p>
    <p><BUTTON name="submit" value="submit" type="submit">Shutdown</BUTTON></p>
    <hr />
  </form>
</main>
    </div>
  </body>
  </html>
