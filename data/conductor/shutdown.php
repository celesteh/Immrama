<!DOCTYPE html>
<?php
//$conf = parse_ini_file("config.ini", true);

$date=date_create();
$timestamp=date_timestamp_get($date);

if ($_POST['timestamp']){
  if (($timestamp - $_POST['timestamp']) <= 60000) {
    // 1 minute
    $success = system("sudo /sbin/halt");
  }
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" href="../color.css" type="text/css" />
<title>Failure</title>
</head>
<body>
  <div id="words">
<h2>Failed</h2>
<p>The computer did not shut down. This may be because you
  waited too long on the previous page.</p>
<p>You should <a href="requestshutdown.php">Try shutting down again</a>.
<ul>
<li><a href="./">Return to piece settings</a></li>
<li><a href="advanced.html">Return to Advanced settings</a></li>
<li><a href="piece.html">View piece as Conductor</a></li>
<li><a href="../piece.html">View piece as performer</a></li>
</ul>
</div>
</body>
</html>
