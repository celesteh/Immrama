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
                $res[] = "$skey = ". $sval; //(is_numeric($sval) ? $sval : '"'.$sval.'"');
              }
          }
          else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
      }
    safefilerewrite($file, implode("\r\n", $res));
}

$conf = parse_ini_file("config.ini", true);
write_php_ini($conf, "config.ini");

$cmd = $conf['working']['installation'] . '/immrama.py';
$args = $conf['working']['data'] . '/conductor/config.ini';
$line = $cmd . " " . $args
// tried
//exec($cmd . ' ' . $args .' >/dev/null 2>/dev/null &');
//Proc_Close (Proc_Open ($cmd . ' ' . $args .'&', Array (), $foo));
//shell_exec($cmd . ' ' . $args .' >/dev/null 2>/dev/null &');

//function LaunchBackgroundProcess($command){
  // Run command Asynchroniously (in a separate thread)
    // Linux/UNIX
//  $command = $command .'  /dev/null &';
//  $handle = popen($command, 'r');
//  if($handle!==false){
//    pclose($handle);
//    return true;
//  } else {
//    return false;
//  }
//}
//
//LaunchBackgroundProcess($line)
shell_exec($line . " >/dev/null 2>/dev/null &")


?>
<html>
<head>
  <link rel="stylesheet" href="/style.css" type="text/css" />
  <title>Playing</title>
  <META http-equiv="refresh" content="2;URL=../piece.html">
  </head>
  <body>
  <!--  <?php echo $cmd . ' ' . $args .' >/dev/null 2>/dev/null &'; ?> -->
  <p>Redirecting to <a href="../piece.html">piece</a></p>
  </body>
  </html>
