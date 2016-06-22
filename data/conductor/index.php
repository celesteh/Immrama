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
  <form action="play.py" method="post">
    <p>


<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
</p>
    <p>
  <label for="dur">Total Duration (in seconds):</label>
    <input type="number" name="dur" value=<?php echo $conf['dur']; ?>>
  </p>
  <p>
  <label for="slide_dur">Duration of each page:</label>
    <input type="number" name="slide_dur" value=<?php echo $conf['slide_dur']; ?>>
  </p>
  <p>
  <label for ="init_sleep">Initial pause (in seconds):</label>
    <input type="number" name="init_sleep" value=<?php echo $conf['init_sleep']; ?>>
  </p>
      <p>
  <label for ="width">Image width:</label>
    <input type="number" name="width" value=<?php echo $conf['width']; ?>>
  </p>
  <p>

  <label for ="height">Image height:</label>
    <input type="number" name="height" value=<?php echo $conf['height']; ?>>
  </p>
  <p>

  <label for ="foreground">Foreground colour:</label>
    <input type="color" name="foreground" value=<?php echo $conf['foreground']; ?>>  </p>
      <p>

  <label for ="background">Background clour:</label>
    <input type="color" name="background" value=<?php echo $conf['background']; ?>>  </p>
      <p>


<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
</p>
  </form>
</body> </html>
