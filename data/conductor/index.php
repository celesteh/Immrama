<!DOCTYPE html>
<?php

$conf = parse_ini_file("config.ini");

?>
<html>
<head>
  <link rel="stylesheet" href="../style.css" type="text/css" />
  <link rel="stylesheet" href="../color.css" type="text/css" />
  <SCRIPT LANGUAGE="javascript">
<!--
function OnChangeSize(form)
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
          if (val == "portrait")
          {
            form.imagewidth.value = 768
            form.imagewidth.readonly = true
            form.imageheight.value = 1024
            form.imageheight.readonly = true
          } else {
            //custom
            form.imagewidth.readonly = false
            form.imageheight.readonly = false
          }
        }
      }
    }

    return true;
};

function readOnly() {
  document.getElementById("imagewidth").readonly = true;
  document.getElementById("imageheight").readonly = true;
};

try {
  readOnly();
} catch (err){}
// render the window before trying to make elements readonly
window.setTimeout(readOnly,300);


//-->
</SCRIPT>
  <title>Immrama</title>
</head>
<body>
  <div id="words">

  <form action="play.php" method="post" id="configform">


<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
</p>
<h3>Durations</h3>
  <p>
  <label for="dur">Total duration: minutes</label>
    <input type="number" name="min"   maxlength="4"  value=<?php echo floor($conf['dur'] / 60); ?>></p><p>
    <label for="dur">seconds</label>
      <input type="number" name="sec"   maxlength="4"  value=<?php echo floor($conf['dur'] % 60); ?>>
      <!-- Make sure there is a $_POST['dur'] -->
      <input type="hidden" name="dur" value=<?php echo $conf['dur']?>>
  </p>
  <p>
  <label for="slide_dur">Duration of each page (in seconds):</label>
    <input type="number" name="slide_dur"    maxlength="4"  value=<?php echo $conf['slide_dur']; ?>>
  </p>
  <p>
  <label for ="init_sleep">Initial pause (in seconds):</label>
    <input type="number" name="init_sleep"    maxlength="4"  value=<?php echo $conf['init_sleep']; ?>>
  </p>
  <h3>Geometry</h3>
      <p>
        <label for="score_size">Target Screen Geometry</label>
        <select name="score_size" onchange='OnChangeSize(this.form);'>
          <option value="prev">Same as last time (<?php echo $conf['imagewidth']; ?>x<?php echo $conf['imageheight']; ?>)</option>
          <option value="phone">Phones (2x3 ratio)</option>
          <option value="tablet">Tablets (4x3 ratio)</option>
          <option value="portrait">Potrait (3x4 ratio)</option>
          <option value="custom">Custom</option>
        </select>
      </p>
      <p>

  <label for ="imagewidth">Image width:</label>
    <input type="number" name="imagewidth"    maxlength="4"  id="imagewidth" value=<?php echo $conf['imagewidth']; ?>>
</p><p>
  <label for ="imageheight">Image height:</label>
    <input type="number" name="imageheight"    maxlength="4"  id="imageheight" value=<?php echo $conf['imageheight']; ?>>
  </p>
  <p>

<h3>Colors</h3>
  <label for ="foreground">Foreground color:</label>
    <input type="color" name="foreground" value=<?php echo $conf['foreground']; ?>>


  <label for ="background">Background color:</label>
    <input type="color" name="background" value=<?php echo $conf['background']; ?>>  </p>
      <p>


<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
</p>
  </form>
  <p><a href="advanced.html">Advanced Options</a></p>
</div>
</body> </html>
