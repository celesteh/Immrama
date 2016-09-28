<!DOCTYPE html>
<?php
$password = "";
$myfile = fopen("/etc/hostapd/hostapd.conf", "r") or die("Unable to open file!");
// Output one line until end-of-file
while(!feof($myfile)) {
 $line =  fgets($myfile);
 if (preg_match("/^wpa\s?=/i", $line)) {
   list($wpa, $password) = explode('=', $line); //text mangling
   $password = trim($password);
 }
}
fclose($myfile);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/style.css">
<link rel="stylesheet" href="/color.css" type="text/css" />
<title>Wifi Password</title>
</head>
<body>
  <div id="words">
<?php if (strlen($password) > 0) {
    echo "<h1>" . $password . "</h1>\n";
    echo "<p>Wifi password is " . $password . "</p>\n"
  } else {
    echo "<p>No Wifi password set.</p>\n";
    echo ""<p><a href=\"advanced.html\">Set a password!</a></p>\n";
  }
 ?>
<ul>
<li><a href="./">Go to piece settings</a></li>
<li><a href="advanced.html">Go to Advanced settings</a></li>
<li><a href="piece.html">View piece as Conductor</a></li>
<li><a href="../piece.html">View piece as performer</a></li>
</ul>
</div>
</body>
</html>
