<!DOCTYPE html>
<?php

$conf = parse_ini_file("config.ini", true);

$cmd = $conf['working']['installation'] . '/end.py';
$args = $conf['working']['data'] . '/conductor/config.ini';
$line = $cmd . " " . $args;

system("nohup ". $line . " >/dev/null 2>/dev/null &");

?>
<html>
<head>
  <link rel="stylesheet" href="../style.css" type="text/css" />
  <link rel="stylesheet" href="../color.css" type="text/css" />
  <title>Playing</title>
  <META http-equiv="refresh" content="0;URL=piece.html">
  </head>
  <body>
    <div id="words">
  <!--  <?php echo $cmd . ' ' . $args .' >/dev/null 2>/dev/null &'; ?> -->
  <p>Redirecting to <a href="./">Conductor</a></p>
</div>
  </body>
  </html>
