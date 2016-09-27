<!DOCTYPE html>
<?php
$conf = parse_ini_file("config.ini", true);

function safefilerewrite($fileName, $dataToSave)
{    if ($fp = fopen($fileName, 'w'))
    {
        $startTime = microtime(TRUE);
        do
        {            $canWrite = flock($fp, LOCK_EX);
           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
           if(!$canWrite) usleep(round(rand(0, 100)*1000));
        } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

        //file was locked so now we can store information
        if ($canWrite)
        {            fwrite($fp, $dataToSave);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

}

if ($_POST['newpass'] != $_POST['confirm']) {
  $success = "Failure: Passwords don't match";
} else {
  $length = strlen($_POST['newpass']);
  if (($length < 8) && ($length != 0)) {
    $success = "Failure: Password too short";
  } else {
    if ($length > 63 ) {
      $success = "Failure: Password too long";
    } else {
      if (preg_match("/\W/", $_POST['newpass'])) {
        $success = "Failure: Password may only contain numbers and letters";
      } else {


        // ok, that was all of the tests
        // put the password into a temporary file
        $file = $conf['working']['tmp'] . '/newpass.txt';
        safefilerewrite($file, $_POST['newpass'] . "\n");

        // put in sudo later
        $cmd =  $conf['working']['installation'] . '/wpa_password.py ';
        $args = $conf['working']['data'] . '/conductor/config.ini';

        $success = system($cmd . $args);
      }
    }
  }
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/style.css">
<link rel="stylesheet" href="/color.css" type="text/css" />
<title><?php echo $success ?></title>
</head>
<body>
  <div id="words">
<h2><?php echo $success ?></h2>
<p><?php
//<!--
  if (preg_match("/Success/i", $sucess)) {
    if (strlen($_POST['newpass'] > 0)) {
      echo "Wifi password is now: " . $_POST['newpass']
    }
  }
//-->
 ?></p>
<ul>
<li><a href="./">Return to piece settings</a></li>
<li><a href="advanced.html">Return to Advanced settings</a></li>
<li><a href="piece.html">View piece as Conductor</a></li>
<li><a href="../piece.html">View piece as performer</a></li>
</ul>
</div>
</body>
</html>
