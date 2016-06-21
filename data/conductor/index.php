<!DOCTYPE html>
<?php

$conf = parse_ini_file("config.ini");

?>
<html>
<head>
<title>Immrama</title>
</head>
<body>
  <form action="im_cgi.py" method="post">
  Total Duration (in seconds): <input type="number" name="dur" value=<?php echo $conf['dur']; ?>><br>
  Duration of each page: <input type="number" name="slide_dur" value=<?php echo $conf['slide_dur']; ?>><br>
  Initial pause (in seconds): <input type="number" name="init_sleep" value=<?php echo $conf['init_sleep']; ?>><br>
  Image width: <input type="number" name="width" value=<?php echo $conf['width']; ?>><br>
  Image height: <input type="number" name="height" value=<?php echo $conf['height']; ?>><br>
  Foreground colour: <input type="color" name="foreground" value=<?php echo $conf['foreground']; ?>><br>
  Background clour: <input type="color" name="background" value=<?php echo $conf['background']; ?>><br>
  <input type="submit">
  </form>
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
