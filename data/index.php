<!DOCTYPE html>
<?php

// Grant access if the Security code is accurate.
if ($_POST['security_code'] == "andrew-wippler-is-cool") {

// Grab the MAC address
$arp = "/usr/sbin/arp"; // Attempt to get the client's mac address
$mac = shell_exec("$arp -a ".$_SERVER['REMOTE_ADDR']);
preg_match('/..:..:..:..:..:../',$mac , $matches);
$mac2 = $matches[0];

// Reconnect the device to the firewall
exec("sudo rmtrack " . $_SERVER['REMOTE_ADDR']);
$i = "sudo iptables -t mangle -A wlan0_Outgoing  -m mac --mac-source ".$_GET['mac']." -j MARK --set-mark 2";
exec($i);

sleep(5);

?> <html>
<head>
<title></title>
</head>
<body>
<h1>You are now free to browse the internet.</h1>
</body> </html>
<?php } else {
  // this is what is seen when first viewing the page
  ?>
  <html>
  <head>
  <title></title>
  </head>
  <body>
  <h1>Authorization Required</h1>
  <p>Before continuing, you must first agree to the <a href="#">Terms of Service</a> and be of the legal age to do that in your selective country or have Parental Consent.
  </p>
  <form method="post" action="index.php">
    <input type="hidden" name="security_code" value="andrew-wippler-is-cool" />
    <input type="checkbox" name="checkbox1" CHECKED /><label for="checkbox1">I Agree to the terms</label><br />
    <input type="submit" value="Connect" />
  </form>
  </body> </html>
<?php } ?>
