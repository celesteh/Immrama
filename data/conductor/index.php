<!DOCTYPE html>
<?php

$conf = parse_ini_file("config.ini");

?>
<html>
<head>
  <link rel="stylesheet" href="../screen.css" type="text/css" media="Screen" />
  <link rel="stylesheet" href="../mobile.css" type="text/css" media="handheld" />
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

<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
  </form>
</body> </html>
