<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../style.css" type="text/css" />
  <link rel="stylesheet" href="../color.css" type="text/css" />
  <script>
  //<!--

  function clearColors(pass1, pass2, message, m2, defaultColor) {
    pass2.style.backgroundColor = defaultColor;
    pass1.style.backgroundColor = defaultColor;
    message.innerHTML="&nbsp;";
    m2.innerHTML="&nbsp;";
  }
  function checkPass()
  {
    //Store the password field objects into variables ...
    var pass1 = document.getElementById('pass1');
    var pass2 = document.getElementById('pass2');
    //Store the Confimation Message Object ...
    var message = document.getElementById('confirmMessage');
    var pass = document.getElementById('testPass');
    //Set the colors we will be using ...
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    var defaultColor = "#ffffff"; // this is set in the style sheet
    //Compare the values in the password field
    //and the confirmation field

    // check bad chars first
    re = /[^a-zA-Z0-9]/;
    if ((re.test(pass1.value)) && (pass1.value !="")) {
      pass1.style.backgroundColor = badColor;
      pass.style.color = badColor;
      pass.innerHTML = "Use numbers and letters only";
    } else {
      if ((pass1.value.length < 8) && (pass1.value.length !=0)) {
        //pass1.style.backgroundColor = badColor;
        //clearColors(pass1);// they're on the way
        //pass1.style.backgroundColor = defaultColor;
        clearColors(pass1, pass2, message, pass, defaultColor);
        pass.style.color = badColor;
        pass.innerHTML = "Too Short";

      } else {
        if (pass1.value.length > 63) {
          pass1.style.backgroundColor = badColor;
          pass.style.color = badColor;
          pass.innerHTML = "Too Long";

        } else {

          if (pass2.value != ""){
            if(pass1.value == pass2.value){
              //The passwords match.
              //Set the color to the good color and inform
              //the user that they have entered the correct password
              pass2.style.backgroundColor = goodColor;
              message.style.color = goodColor;
              message.innerHTML = "Passwords Match!";
            }else{
              //The passwords do not match.
              //Set the color to the bad color and
              //notify the user.
              pass2.style.backgroundColor = badColor;
              message.style.color = badColor;
              message.innerHTML = "Passwords Do Not Match!";
            }
          } else {
            clearColors(pass1, pass2, message, pass, defaultColor);
          }
        }
      }
    }
  };


function clearJunk() {
  //Store the password field objects into variables ...
  var pass1 = document.getElementById('pass1');
  var pass2 = document.getElementById('pass2');
  //Store the Confimation Message Object ...
  var message = document.getElementById('confirmMessage');
  clearColors(pass1, pass2, message);
  pass1.value="";
  pass2.value="";

};


function validate(form) {

    if(form.pass1.value != form.pass2.value) {
        alert('Passwords do not match');
        return false;
    }
    else {
        if(form.pass1.value== "") {
          return confirm('Are you sure you want to have no wifi password?');
        } else {
          if (form.pass1.value.length < 8) {
            alert('Password is too short');
            return false;
          } else {
            if (form.pass1.value.length > 63) {
              alert('Password is too long');
              return false;
            } else {
              re = /[^a-zA-Z0-9]/;
              if (re.test(pass1.value)) {
                alert('Only numbers and letters allowed');
                return false;
              } else {
                return true;
              }
            }

        }
      }
    }
}
//-->
</script>
  <title>Shutdown</title>
  </head>
  <body>
  <div id="words">
  </form>
  <form action="shutdown.php" method="post">
    <h2>Shutdown the computer</h2>
    <p>After you hit submit, your browser should fail to load the next page.</p>
    <p>There will be a short delay before the comuter turns off.
      Please wait a few moments before unplugging the power supply.
      The shutdown computer may have a solid red light.
      Some Pis may reboot instead of shutting down. If this happens,
      try to unplug the machine while the red light is on.
    </p>
    <p>To Restart the machine after shutting down, unplug it and then plug it back in.</p>
    <p><input type="hidden" name="timestamp" value=<?php $date=date_create();
    echo date_timestamp_get($date); ?> /></p>
    <p><BUTTON name="submit" value="submit" type="submit">Shutdown</BUTTON></p>
    <hr />
  </form>
    </div>
  </body>
  </html>
