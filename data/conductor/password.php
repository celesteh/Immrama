<!DOCTYPE html>
<?php
$conf = parse_ini_file("config.ini", true);

$cmd = 'sudo ' . $conf['working']['installation'] . '/wpa_password.py';
$args = ' '. $_POST['newpass'] . ' ' . $_POST['confirm'];

$success = system($cmd . $args);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/style.css">
<link rel="stylesheet" href="/color.css" type="text/css" />
<title><?php echo $sucess ?></title>
</head>
<body>
  <div id="words">
<h2><?php echo $sucess ?></h2>
<ul>
<li><a href="./">Return to piece settings</a></li>
<li><a href="advanced.html">Return to Advanced settings</a></li>
<li><a href="piece.html">View piece as Conductor</a></li>
<li><a href="../piece.html">View piece as performer</a></li>
</ul>
</div>
</body>
</html>
