<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" href="../color.css" type="text/css" />
<link rel="stylesheet" href=" bower_components/simplemde/dist/simplemde.min.css" type="text/css" />
<script src=" bower_components/simplemde/dist/simplemde.min.js">
var simplemde;

function setup(){
  simplemde = new SimpleMDE({ element: document.getElementById("inputtext") });
}

window.setTimeout(setup, 100);
</script>
<title>Set front page text</title>
</head>
<body>
<div id="words">
  <div class="dropdown">
    <button class="dropbtn">&#8801;</button>
   <div class="dropdown-content">
     <a href="../">Home</a>
     <a href="./">(Re)Start Piece</a>
     <a href="./end.php">Stop Piece</a>
    <a href="./piece.html">Show Piece as Conductor</a>
    <a href="../piece.html">Show piece as Player</a>
    <a href="../wifi.php">Show WiFi Password</a>
    <a href="./requestshutdown.php">Shut down computer</a>
    <a href="./setintro.php">Set introductory text</a>
  </div>
</div>
<main class="main">
<h1>Set Introductory Text</h1>
<p>Set the text on the <a href="../">Home</a> page.</p>
<form>
  <noscript>
    <input type="text" name="title">
  </noscript>
<textarea id="inputtext" name="textin">
</textarea>
</form>
</main>
<script> setup();</script>
    </div>
  </body>
  </html>
