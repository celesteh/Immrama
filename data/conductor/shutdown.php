<!DOCTYPE html>
<?php
//$conf = parse_ini_file("config.ini", true);

$date=date_create();
$timestamp=date_timestamp_get($date);
$failed = true;
if ($_POST['timestamp']){
  if (($timestamp - $_POST['timestamp']) <= 60) {
    // 1 minute
    $success = system("sleep 2 && sudo /sbin/halt &");
    $failed = false;
  }
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" href="../color.css" type="text/css" />
<title><?php if($failed) {echo "Failure";} else {echo "Success";};?></title>
</head>
<body>
  <div id="words">
      <div class="dropdown">
        <button class="dropbtn">&#8801;</button>
       <div class="dropdown-content">
         <a href="../">Home</a>
         <a href="./">(Re)Start Piece</a>
         <a href="./end.php">Stop Piece</a>
        <a href="./piece.html">Show Piece as Conductor</a>
        <a href="../piece.html">Show piece as Player</a>
        <a href="../wifi.php">Show WiFi Password</a>
        <a href="./advanced.html">Set WiFi Password</a>
        <a href="./setintro.php">Set introductory text</a>
      </div>
    </div>
    <?php
      if ($failed) {
        echo "
<h2>Failed</h2>
<p>The computer did not shut down. This may be because you
  waited too long on the previous page.</p>
<p>You should <a href=\"requestshutdown.php\">Try shutting down again</a>.
        ";
      } else {
        echo "
<h2>Success</h2>
<p>The computer will shut down in a moment.  Please wait a few
seconds before unplugging it. If you want to restart the computer,
wait a few seconds, unplug it, then plug it back in.</p>
          ";
      };
?>
</div>
</body>
</html>
