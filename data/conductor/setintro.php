<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" href="../color.css" type="text/css" />
<link rel="stylesheet" href=" bower_components/simplemde/dist/simplemde.min.css" type="text/css" />

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
<script src=" bower_components/simplemde/dist/simplemde.min.js">
var simplemde = new SimpleMDE();
</script>
<textarea>
</textarea>
</main>
    </div>
  </body>
  </html>
