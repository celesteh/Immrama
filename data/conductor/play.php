<!DOCTYPE html>
<?php


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
                $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
              }
          }
          else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
      }
    safefilerewrite($file, implode("\r\n", $res));
}

$conf = parse_ini_file("config.ini", true);
write_php_ini($conf, "config.ini");

$pid=pcntl_fork();
if($pid==0)
{
 posix_setsid();
 $cmd = '/home/pi/Documents/Immrama/immrama.py';
 $args= 'var/www/html/conductor/config.ini'
 pcntl_exec($cmd,$args,$_ENV);
 // child becomes the standalone detached process
}
?>
<html>
<head>
  <link rel="stylesheet" href="/style.css" type="text/css" />
  <title>Playing</title>
  <META http-equiv="refresh" content="1;URL=../piece.html">
  </head>
  <body>
  <p>Redirecting to <a href="../piece.html">piece</a></p>
  </body>
  </html>
