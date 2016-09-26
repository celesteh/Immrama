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

var dur = <?php echo $conf['dur']; ?>

var form = document.getElementById("config")
var input = document.createElement("input") //input element, text
input.setAttribute('type',"hidden")
input.setAttribute('name',"dur")
input.setAttribute('value', dur)
form.insertBefore(input)

var input = document.createElement("input") //input element, text
input.setAttribute('type',"number")
input.setAttribute('name',"seconds")
input.setAttribute('value', dur % 60)
form.insertBefore(input)

var label = document.createElement("label")
label.setAttribute('for', "seconds")
label.htmlFor = "Seconds"
form.insertBefore(label)

var input = document.createElement("input") //input element, text
input.setAttribute('type',"number")
input.setAttribute('name',"minutes")
input.setAttribute('value', dur / 60)
form.insertBefore(input)

var label = document.createElement("label")
label.setAttribute('for', "minutes")
label.htmlFor = "Duration: Minutes"
form.insertBefore(label)


//-->
</SCRIPT>
  <title>Immrama</title>
</head>
<body>
  <div id="words">

  <form action="play.php" method="post" id="configform">
    <p><a href="advanced.html">Advanced Options</a></p>


<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
</p>
  <p>
  <noscript>
  <label for="dur">Total Duration (in seconds):</label>
    <input type="number" name="dur" value=<?php echo $conf['dur']; ?>>
  </noscript>
  </p>
  <p>
  <label for="slide_dur">Duration of each page (in seconds):</label>
    <input type="number" name="slide_dur" value=<?php echo $conf['slide_dur']; ?>>
  </p>
  <p>
  <label for ="init_sleep">Initial pause (in seconds):</label>
    <input type="number" name="init_sleep" value=<?php echo $conf['init_sleep']; ?>>
  </p>
      <p>
        <label for="score_size">Target Screen Geometry</label>
        <select name="score_size" onchange='OnChangeSize(this.form);'>
          <option value="prev">Same as last time (<?php echo $conf['imagewidth']; ?>x<?php echo $conf['imageheight']; ?>)</option>
          <option value="phone">Phones (2x3 ratio)</option>
          <option value="tablet">Tablets (4x3 ratio)</option>
          <option value="custom">Custom</option>
        </select>
      </p>
      <p>
  <label for ="imagewidth">Image width:</label>
    <input type="number" name="imagewidth" id="imagewidth" value=<?php echo $conf['imagewidth']; ?>>

  <label for ="imageheight">Image height:</label>
    <input type="number" name="imageheight" id="imageheight" value=<?php echo $conf['imageheight']; ?>>
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
