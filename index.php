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
  $wavelength = $_POST["wavelength"];
  $z = ($wavelength - 6563.0) / 6563.0;
  $S = $_POST["brightS"];
  $E = $_POST["brightE"];
  $d = sqrt($E / (4 * 3.14159265359 * $S));
  /*
   * Push values into session variables.
   */
  array_push($_SESSION["redshifts"], $z);
  array_push($_SESSION["distances"], $d);
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Interactive Hubble Diagram</title>
    <style type="text/css" media="screen">
      @import "css/styles.css";
    </style>
  </head>
  <body>
  <div class="wrapper">

  <div class="content">

  <h1>Interactive Hubble Diagram</h1>

  <h2>Instructions</h2>
  
  <p>In this exercise you will use the H&alpha; emission from galaxies measured
  by the Sloan Digital Sky Survey to estimate the recession velocities of
  objects in the nearby universe.</p>

  <p>Using these data, you can then construct a Hubble Diagram &#8212; a plot
  of recession velocity versus distance &#8212; and estimate Hubble's
  constant.</p>

<?php
if (empty($_SESSION["redshifts"])) {
  echo "<h2>Get Started</h2>";
  echo "<p>To begin, click the button below. You will be taken to a page
  showing the image and spectrum of a galaxy measured by SDSS. Estimate the
  wavelength of the H&alpha; line and start building your Hubble diagram!</p>";
  
  echo "<form name=\"begin\" method=\"post\" action=\"chart.php\" width=\"100px\">";
  echo "<input type=\"submit\" name=\"submit\" value=\"Get Started!\">";
  echo "</form>";
}
else {
  echo "<p><em>z</em> = " . $z . "</p>";
  echo "<p><em>d</em> = " . $d . "</p>";
}
?>

  </div>

  </div>
  </body>
</html>
