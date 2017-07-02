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

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

// perform math for WCAG2
function getLuminance (rgb){

  for (var i =0; i<rgb.length; i++) {
    if (rgb[i] <= 0.03928) {
      rgb[i] = rgb[i] / 12.92;
    } else {
      rgb[i] = Math.pow( ((rgb[i]+0.055)/1.055), 2.4 );
    }
  }
  var l = (0.2126 * rgb[0]) + (0.7152 * rgb[1]) + (0.0722 * rgb[2]);
  return l;
};


function color_checker() {
  // perform math for WCAG1
	var brightnessThreshold = 125;
	var colorThreshold = 500;

  var bg = hexToRgb(form.foreground.value);
  var fg = hexToRgb(form.background.value);

	var bY=((bg.r * 299) + (bg.g * 587) + (bg.b * 114)) / 1000;
	var fY=((fg.r * 299) + (fg.g * 587) + (fg.b * 114)) / 1000;
	var brightnessDifference = Math.abs(bY-fY);

  var colorDifference = (Math.max (fg.r, bg.r) - Math.min(fg.r, bg.r)) +
                        (Math.max (fg.g, bg.g) - Math.min(fg.g, bg.g)) +
                        (Math.max (fg.b, bg.b) - Math.min(fg.b, bg.b));

  var ratio = 1;
  var l1 = getLuminance([fg.r/255, fg.g/255, fg.b/255]);
  var l2 = getLuminance([bg.r/255, bg.g/255, bg.b/255]);

  if (l1 >= l2) {
  	ratio = (l1 + .05) / (l2 + .05);
  } else {
  	ratio = (l2 + .05) / (l1 + .05);
  }

  if (ratio < 3 ) {
    alert("Colours are too similar");
    return false;
  } else {
    return true;
  }
}

//-->
</SCRIPT>
  <title>Immrama</title>
</head>
<body>
  <div id="words">
    <div class="dropdown">
      <button class="dropbtn">&#8801;</button>
     <div class="dropdown-content">
       <a href="../">Home</a>
      <a href="./piece.html">Show Piece</a>
      <a href="./end.php">Stop Piece</a>
      <a href="../wifi.php">Show WiFi Password</a>
      <a href="./advanced.html">Change WiFi Password</a>
      <a href="./requestshutdown.php">Shut down computer</a>
      <a href="./setintro.php">Set introductory text</a>
    </div>
  </div>
  <main class="main">
    <noscript>
      <h1>Error</h1>
      <p>This device does not understand javascript. Try to enable javascript via your web browser and reload this page.</p>
      <p>If this error persists, you may use this device to view the score, using the
        'old devices' link on the previous page. But this
        device may have problems with some of the Conductor pages.</p>
      </noscript>
  <form action="play.php" method="post" id="configform" class="cond">


<BUTTON name="submit" value="submit" type="submit">Start</BUTTON>
</p>
<h3>Durations</h3>
  <p>
  <label for="dur">Total duration: minutes</label>
    <input type="number" name="min"   maxlength="4"  value=<?php echo floor($conf['dur'] / 60); ?>></p><p>
    <label for="dur">seconds</label>
      <input type="number" name="sec"   maxlength="4"  value=<?php echo floor($conf['dur'] % 60); ?>>
      <!-- Make sure there is a $_POST['dur'] -->
      <input type="hidden" name="dur" value=<?php echo $conf['dur']?> />
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
</main>
</div>
</body> </html>
