<!DOCTYPE html>
<?php
$conf = parse_ini_file("config.ini", true);

function write_php_ini($array, $file)
{
    $res = array();
    //foreach ($array as $title => $section)
    //  $res[] = "[$title]";
    //  foreach($array[$title] as $key => $val)
    foreach($array as $key => $val)
      {
          if (empty($_POST[$key])) {
            //$val = $val;
          } else {
            $val = $_POST[$key];
          }
          if(is_array($val))
          {
              $res[] = "[$key]";
              foreach($val as $skey => $sval) {
                if (empty($_POST[$skey])) {
                  //$val = $val;
                } else {
                  $sval = $_POST[$skey];
                }
                $res[] = "$skey = ". $sval; //(is_numeric($sval) ? $sval : '"'.$sval.'"');
              }
          }
          else $res[] = "$key = ".(is_numeric($val) ? ceil($val) : '"'.$val.'"');
      }
    safefilerewrite($file, implode("\r\n", $res));
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
        $file = $conf['working']['tmp'] . 'newpass.txt';
        safefilerewrite($file, $_POST['newpass'] . '\n');

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
<ul>
<li><a href="./">Return to piece settings</a></li>
<li><a href="advanced.html">Return to Advanced settings</a></li>
<li><a href="piece.html">View piece as Conductor</a></li>
<li><a href="../piece.html">View piece as performer</a></li>
</ul>
</div>
</body>
</html>
