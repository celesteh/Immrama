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
      form.image_width.value = <?php echo $conf['image_width']; ?>

      form.image_width.disabled = true
      form.image_height.value = <?php echo $conf['image_height']; ?>

      form.image_height.disabled = true

    } else {
      if (val=="phone")
      {
        form.image_width.value = 640
        form.image_width.disabled = true
        form.image_height.value = 960
        form.image_height.disabled = true

      } else {
        if (val =="tablet")
        {
          form.image_width.value = 1024
          form.image_width.disabled = true
          form.image_height.value = 768
          form.image_height.disabled = true
        } else {
          //custom
          form.image_width.disabled = false
          form.image_height.disabled = false
        }
      }
    }

    return true;
}

document.getElementById("image_width").disabled = true
document.getElementById("image_height").disabled = true

//-->
</SCRIPT>
  <title>Immrama</title>
</head>
<body>
  <div id="words">

  <form action="play.php" method="post">
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
        <label for="score_size">Target Screen Geometry</label>
        <select name="score_size" onchange='OnChange(this.form);'>
          <option value="prev">Same as last time (<?php echo $conf['image_width']; ?>x<?php echo $conf['image_height']; ?>)</option>
          <option value="phone">Phones (2x3 ratio)</option>
          <option value="tablet">Tablets (4x3 ratio)</option>
          <option value="custom">Custom</option>
        </select>
      </p>
      <p>
  <label for ="image_width">Image width:</label>
    <input type="number" name="image_width"  value=<?php echo $conf['image_width']; ?>>

  <label for ="image_height">Image height:</label>
    <input type="number" name="image_height" value=<?php echo $conf['image_height']; ?>>
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
