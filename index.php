<?php
session_start();
/*
 * Set up session variables.
 */
if (is_null($_SESSION["redshifts"])) {
  $_SESSION["redshifts"] = array();
  $_SESSION["distances"] = array();
  $_SESSION["velocities"] = array();
}
/*
 * Once session variables are set up, search for post requests from
 * chart.php.
 */
$post_commands = array_keys($_POST);
if (!(empty($_POST))) {
  if (array_key_exists("restart", $_POST)) {
    session_unset();
  }
  else {
    $wavelength = $_POST["wavelength"];
    $z = ($wavelength - 6563.0) / 6563.0;
    $S = $_POST["brightS"];
    $E = $_POST["brightE"];
    $v = 299792.458 * $z;
    $d = sqrt($E / (4 * 3.14159265359 * $S)) * 3240.78;
    /*
     * Push values into session variables.
     */
    array_push($_SESSION["redshifts"], $z);
    array_push($_SESSION["distances"], $d);
    array_push($_SESSION["velocities"], $v);
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Interactive Hubble Diagram</title>
    <style type="text/css" media="screen">
      @import "css/styles.css";
    </style>
<?php
  /*
   * If we have redshift measurements, plot them.
   */
  if (!empty($_SESSION["redshifts"])) {
    echo "<script>";
    echo "window.onload = function() {";
    echo "    var chart = new CanvasJS.Chart(\"chartContainer\", {";
    echo "	animationEnabled: false,";
    echo "	zoomEnabled: true,";
    echo "	title:{";
    echo "		text: \"Hubble Diagram\"";
    echo "	},";
    echo "	axisX: {";
    echo "		title:\"distance [Mpc]\",";
    echo "	},";
    echo "	axisY:{";
    echo "		title: \"velocity [km/s]\",";
    echo "	},";
    echo "	data: [{";
    echo "		type: \"scatter\",";
    echo "		dataPoints: [";
    for ($i=0; $i<count($_SESSION["distances"]); $i++) {
      $x = $_SESSION["distances"][$i];
      $y = $_SESSION["velocities"][$i];
      echo "    { x: " . $x . ", y: " . $y . "},";
    }
    echo "		]";
    echo "	}]";
    echo "});";
    echo "chart.render();";
    echo "";
    echo "}";
    echo "</script>";
  }
?>
  </head>
  <body>
  <div class="wrapper">

  <div class="content">

  <h1>Interactive Hubble Diagram</h1>

<?php
if (empty($_SESSION["redshifts"])) {
  echo "<h2>Instructions</h2>";
  echo "<p>In this exercise you will use the H&alpha; emission from galaxies measured by the Sloan Digital Sky Survey to estimate the recession velocities of objects in the nearby universe.</p>";
  echo "<p>Using these data, you can then construct a Hubble Diagram &#8212; a plot of recession velocity versus distance &#8212; and estimate Hubble's constant.</p>";
  echo "<h2>Get Started</h2>";
  echo "<p>To begin, click the button below. You will be taken to a page
  showing the image and spectrum of a galaxy measured by SDSS. Estimate the
  wavelength of the H&alpha; line and start building your Hubble diagram!</p>";
  
  echo "<form name=\"begin\" method=\"post\" action=\"chart.php\" width=\"100px\">";
  echo "<input type=\"submit\" name=\"submit\" value=\"Get Started!\">";
  echo "</form>";
}
else {
  echo "<h2>Results</h2>";
  echo "<div id=\"chartContainer\" style=\"height:400px; width: 50%;\"></div>";
  echo "<script src=\"https://canvasjs.com/assets/script/canvasjs.min.js\"></script>";
  /*
   * Loop through session redshifts and distances.
   */
  /*
  for ($i=0; $i<count($_SESSION["distances"]); $i++) {
    echo $_SESSION["distances"][$i] . ", " . $_SESSION["velocities"][$i] . "<br/>";
  }
  */
  echo "<p><a href=\"chart.php\">Run another spectrum!</a></p>";
  echo "<h2>Restart!</h2>";
  echo "<p>To restart the entire calculation from scratch, click below.</p>";
  echo "<form name=\"Restart\" method=\"post\" action=\"index.php\">";
  echo "<input type=\"submit\" name=\"restart\" value=\"Restart!\">";
  echo "</form>";
}
?>

  </div>

  </div>
  </body>
</html>
