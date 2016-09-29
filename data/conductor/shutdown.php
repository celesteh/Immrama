<!DOCTYPE html>
<?php
//$conf = parse_ini_file("config.ini", true);

$success = system("sudo /sbin/halt");
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
<p>You should not be able to see this page.</p>
<p>Normally, there will be a short delay before the comuter turns off.
  Please wait a few moments before unplugging the power supply.
  The shutdown computer should have a solid red light.
  Some Pis may reboot instead of shutting down. If this happens,
  try to unplug the machine while the red light is on.
</p>
<p>You should <a href="advanced.html">Try shutting down again</a>.
<ul>
<li><a href="./">Return to piece settings</a></li>
<li><a href="advanced.html">Return to Advanced settings</a></li>
<li><a href="piece.html">View piece as Conductor</a></li>
<li><a href="../piece.html">View piece as performer</a></li>
</ul>
</div>
</body>
</html>
