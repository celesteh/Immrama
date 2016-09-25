<!DOCTYPE html>
<?php

$conf = parse_ini_file("config.ini");

?>
<html>
<head>
  <link rel="stylesheet" href="/style.css" type="text/css" />
  <link rel="stylesheet" href="/color.css" type="text/css" />
  <SCRIPT LANGUAGE="javascript">
<!--
function OnChange(form)
{
  //alert("change")
    var val  = form.score_size.value
    if(val=="prev")
    {
      form.imagewidth.value = <?php echo $conf['imagewidth']; ?>

      form.imagewidth.readonly = true
      form.imageheight.value = <?php echo $conf['imageheight']; ?>

      form.imageheight.readonly = true

    } else {
      if (val=="phone")
      {
        form.imagewidth.value = 640
        form.imagewidth.readonly = true
        form.imageheight.value = 960
        form.imageheight.readonly = true

      } else {
        if (val =="tablet")
        {
          form.imagewidth.value = 1024
          form.imagewidth.readonly = true
          form.imageheight.value = 768
          form.imageheight.readonly = true
        } else {
          //custom
          form.imagewidth.readonly = false
          form.imageheight.readonly = false
        }
      }
    }

    return true;
}

document.getElementById("imagewidth").readonly = true
document.getElementById("imageheight").readonly = true

//-->
</SCRIPT>
  <title>Immrama</title>
</head>
<body>
  <div id="words">
    <p><a href="advanced.php">Advanced options</a></p>
  <form action="play.php" method="post">
    <p>


<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
</p>
  <?php
  //if file_exists("password.txt") {
    echo "<p><label for \"passwd\">Conductor password:</label>\n";
    echo "<input type=\"password\" name=\"passwd\"></p>\n";
  //}

  ?>
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
        <label for="score_size">Target Screen Geometry</label>
        <select name="score_size" onchange='OnChange(this.form);'>
          <option value="prev">Same as last time (<?php echo $conf['imagewidth']; ?>x<?php echo $conf['imageheight']; ?>)</option>
          <option value="phone">Phones (2x3 ratio)</option>
          <option value="tablet">Tablets (4x3 ratio)</option>
          <option value="custom">Custom</option>
        </select>
      </p>
      <p>
  <label for ="imagewidth">Image width:</label>
    <input type="number" name="imagewidth"  value=<?php echo $conf['imagewidth']; ?>>

  <label for ="imageheight">Image height:</label>
    <input type="number" name="imageheight" value=<?php echo $conf['imageheight']; ?>>
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
</div>
</body> </html>
