<!DOCTYPE html>
<?php
$file = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['file']);

if(empty($file)) { $file="current"; };

if(strcmp($file, "new")==0) {
  $newfile = true;
} else {
  $newfile = false;
  $file = "intro/" . $file . ".md";
  if (! file_exists($file)) {
    $file = "intro/current.md";
  }
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" href="../color.css" type="text/css" />
<link rel="stylesheet" href=" bower_components/simplemde/dist/simplemde.min.css" type="text/css" />
<link rel="stylesheet" href=" font-awesome.min.css" type="text/css" />

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
  </div>
</div>
<main class="main">
<h1>Set Introductory Text</h1>
<p>Set the text on the <a href="../">Home</a> page.</p>
<form class="intro">
  <noscript>
    <label for="title">Title:</label>
    <input type="text" name="title">
    <label for="textin">Text:</label> < br/>
  </noscript>
<textarea id="inputtext" name="textin">
</textarea>
</form>
<script src=" bower_components/simplemde/dist/simplemde.min.js"></script>
<script>
try{
  new SimpleMDE({
  		element: document.getElementById("inputtext"),
      spellChecker: false,
      <?php
      if($newfile){
        echo "placeholder: \"## Header\n\nText...\",\n";
      }
      ?>
      toolbar: [
        {
  		      name: "heading",
  		      action: toggleHeadingSmaller,
  		      className: "fa fa-header",
            title: "Heading",
            default: true
        },
        {
		        name: "bold",
            action: toggleBold,
            className: "fa fa-bold",
            title: "Bold",
            default: true
        },
        {
		        name: "italic",
            action: toggleItalic,
            className: "fa fa-italic",
            title: "Italic",
            default: true
        }, "|",
        {
		        name: "quote",
		        action: toggleBlockquote,
            className: "fa fa-quote-left",
            title: "Quote",
            default: true
        },
        {
		        name: "unordered-list",
            action: toggleUnorderedList,
            className: "fa fa-list-ul",
            title: "Generic List",
            default: true
	       },
         {
		         name: "ordered-list",
             action: toggleOrderedList,
             className: "fa fa-list-ol",
             title: "Numbered List",
             default: true
           }, "|",
           {
		           name: "preview",
               action: togglePreview,
               className: "fa fa-eye no-disable",
               title: "Toggle Preview",
               default: true
          }, "|",
          {
		          name: "guide",
		          action: "https:../help/setintro.html",
              className: "fa fa-question-circle",
              title: "Guide",
              default: true
          }
      ]
    });
  } catch (err){}; // Don't die if MDE not installed
</script>
</main>
    </div>
  </body>
  </html>
