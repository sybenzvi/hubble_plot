<?php
  /*
   * Randomly load data from a file.
   */
  $possibleFiles = glob("spectra/*.txt");
  $the_file = $possibleFiles[rand() % count($possibleFiles)];
  $img_file = str_replace("txt", "png", $the_file);
  $array = explode("\n", file_get_contents($the_file));
  $meta = explode(" ", trim($array[0]));
  /*
   * Extract spectrum metadata.
   */
  $plate = $meta[0];
  $fiber = $meta[1];
  $mjd = $meta[2];
  $z = $meta[3];
  $spec_id = $meta[4];
  $constel = $meta[5];
  $ra = $meta[6];
  $dec = $meta[7];
  $S = $meta[8];
  $E = $meta[9];
  /*
   * Serve the page.
   */
  echo " <html>";
  echo " <head>";
  echo " <script>";
  echo " window.onload = function () {";
  echo " ";
  echo " var chart = new CanvasJS.Chart(\"chartContainer\", {";
  echo "   animationEnabled: false,";
  echo "   zoomEnabled: true,";
  echo "   theme: \"light2\",";
  echo "   title:{";
  echo "     text: \"Plate " . $plate . ", Fiber " . $fiber . ", MJD " . $mjd . "\"";
  echo "   },";
  echo "   axisX:{";
  echo "     title: \"wavelength [angstrom]\"";
  echo "   },";
  echo "   axisY:{";
  echo "     title: \"flux [erg/s/cm^2/A]\",";
  echo "     includeZero: false";
  echo "   },";
  echo "   data: [{";
  echo "     click: onClick,";
  echo "     type: \"line\",";
  echo "     dataPoints: [";
  /*
   * Extract spectrum data from the file.
   */
  $array = explode("\n", file_get_contents($the_file));
  foreach ($array as $k => $entry) {
    /* Skip the first line. */
    if ($k < 1) continue;
    $xy = explode(" ", trim($entry));
    if (empty($xy[0])) {
      break;
    }
    echo "{ x: " . $xy[0] . ", y : " . $xy[1] . "},";
  }
  echo "        ]";
  echo "     }]";
  echo " });";
  echo " chart.render();";
  echo " ";
  echo " function onClick(e){";
  echo "   document.Ha.wavelength.value = e.dataPoint.x;";
  echo " }";
  echo " ";
  echo " }";
  echo " </script>";
  echo " </head>";
  echo " <body>";
  echo " ";
  echo " <div align=\"center\">";
  echo "   <table cellpadding=\"5px\">";
  echo "   <tr><td rowspan=\"6\"><img src=\"" . $img_file . "\" style=\"width:200px; height:200px\"></td><td><strong>SpecID</strong>:</td><td>" . $spec_id .  "</td></tr>";
  echo "   <tr><td><strong>Constellation</strong>:</td><td>" . $constel . "</td></tr>";
  echo "   <tr><td><strong>RA</strong>:</td><td>" . $ra . "&deg; </td></tr>";
  echo "   <tr><td><strong>Dec</strong>:</td><td>" . $dec . "&deg; </td></tr>";
  echo "   <tr><td><strong>Seen</strong><br/><strong>Brightness</strong></td><td>" . $S . " W m<sup>-2</sup> </td></tr>";
  echo "   <tr><td><strong>Emitted</strong><br/><strong>Brightness</strong></td><td>" . $E . " W </td></tr>";
  echo "   </table><br/>";
  echo "  <div style=\"width:50%\">";
  echo "   <p style=\"text-align:left\">Enter the value of the H&alpha; line for this spectrum. You can drag the mouse over part of the spectrum to zoom. Left click to pick out a wavelength. </p>";
  echo "  </div>";
  /*
   * Container displaying the spectrum.
   */
  echo "   <div id=\"chartContainer\" style=\"height: 300px; width:600px;
  border:1px solid #000;\"></div>";
  echo "   <script src=\"https://canvasjs.com/assets/script/canvasjs.min.js\"></script><br/>";
  echo " ";
  echo "   <div style=\"width:50%\">";
  echo " ";
  /*
   * Form input for spectrum Ha line, seen brightness,
   * and emitted brightness.
   */
  echo "   <form name=\"Ha\" method=\"post\" style=\"padding:10px; background-color:#eee; width:300px; border:1px solid\" action=\"index.php\">";
  echo "   <table cellpadding=\"3px\">";
  echo "     <tr><td><label for=\"wavelength\" style=\"color:#ff0000;\">H&alpha; line [angstrom]: </label></td>";
  echo "     <td><input name=\"wavelength\" type=\"number\" step=\"0.1\" min=\"6500\" max=\"9000\" value=\"6563\"></td></tr>";
  echo "     <tr><td><label for=\"brightS\" style=\"color:#ff0000;\">Brightness <em>S</em> [10<sup>-15</sup> W m<sup>-2</sup>]: </label></td>";
  echo "     <td><input name=\"brightS\" type=\"number\" step=\"0.001\" min=\"1\" max=\"1000\" value=\"" . $S * 1e15 . "\"></td></tr>";
  echo "     <tr><td><label for=\"brightE\" style=\"color:#ff0000;\">Brightness <em>E</em> [10<sup>37</sup> W]: </label></td>";
  echo "     <td><input name=\"brightE\" type=\"number\" step=\"0.001\" min=\"1\" max=\"1000\" value=\"" . $E * 1e-37 . "\"></td></tr>";
  echo "     <tr><td colspan=\"2\" align=\"center\"><br/><input type=\"submit\" value=\"Submit\"></td></tr>";
  echo "   </table>";
  echo "   </form>";
  echo "   </p>";
  echo "   </div>";
  echo " ";
  echo " </div>";
  echo " ";
  echo " </body>";
  echo " </html>";
?>
