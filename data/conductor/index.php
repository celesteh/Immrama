<!DOCTYPE html>
<?php

$conf = parse_ini_file("config.ini");

?>
<html>
<head>
  <link rel="stylesheet" href="/style.css" type="text/css" />
  <title>Immrama</title>
</head>
<body>
  <form action="im_cgi.py" method="post">
  <label for="dur">Total Duration (in seconds):</label>
    <input type="number" name="dur" value=<?php echo $conf['dur']; ?>><br>
  <label for="slide_dur">Duration of each page:</label>
    <input type="number" name="slide_dur" value=<?php echo $conf['slide_dur']; ?>><br>
  <label for ="init_sleep">Initial pause (in seconds):</label>
    <input type="number" name="init_sleep" value=<?php echo $conf['init_sleep']; ?>><br>
  <label for ="width">Image width:</label>
    <input type="number" name="width" value=<?php echo $conf['width']; ?>><br>
  <label for ="height">Image height:</label>
    <input type="number" name="height" value=<?php echo $conf['height']; ?>><br>
  <label for ="foreground">Foreground colour:</label>
    <input type="color" name="foreground" value=<?php echo $conf['foreground']; ?>><br>
  <label for ="background">Background clour:</label>
    <input type="color" name="background" value=<?php echo $conf['background']; ?>><br>

<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
  </form>
</body> </html>
