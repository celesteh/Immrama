<!DOCTYPE html>
<?php
$password = "";
$myfile = fopen("/etc/hostapd/hostapd.conf", "r") or die("Unable to open file!");
// Output one line until end-of-file
while(!feof($myfile)) {
 $line =  fgets($myfile);
 if (preg_match("/^wpa_passphrase\s?=/i", $line)) {
   list($wpa, $password) = explode('=', $line); //text mangling
   $password = trim($password);
 }
}
fclose($myfile);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<title>Wifi Password</title>
</head>
<body>
  <div id="words">
      <div class="dropdown">
        <button class="dropbtn">&#8801;</button>
       <div class="dropdown-content">
         <a href="./">Home</a>
         <a href="conductor">Conductor</a>
         <a href="conductor/advanced.html">Change WiFi Password</a>
         <a href="conductor/piece.html">View piece as Conductor</a>
         <a href="./piece.html">View piece as performer</a>
       </div>
     </div>
     <main class="main">


<?php if (strlen($password) > 0) {
    echo "<h1>" . $password . "</h1>\n";
    echo "<p>Wifi password is " . $password . "</p>\n";
  } else {
    echo "<p>No Wifi password set.</p>\n";
    echo "<p><a href=\"conductor/advanced.html\">Set a password!</a></p>\n";
  }
 ?>
</main>
</div>
</body>
</html>
