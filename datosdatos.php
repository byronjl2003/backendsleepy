<?php
$servername = "localhost";
$username = "root";
$password = "Birlolo57521814";
$dbname = "Arqui2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "<h1>DATOS METEREOLOGICOS</h1> <BR>";
/*
echo "<style type="text/css">";
echo ".tg  {border-collapse:collapse;border-spacing:0;}";
echo ".tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}";
echo ".tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}";
echo ".tg .tg-yw4l{vertical-align:top}";
echo "</style>";
echo "<table class="tg">"
echo "<tr>";
echo " <th class="tg-yw4l"></th>";
echo " </tr>";
echo "</table>";
*/
echo "<table>";

echo "  <tr>";

   echo " <th>Hoy</th>";

   echo " <th>Mañana</th>";

   echo " <th>Domingo</th>";

 echo " </tr>";

 echo " <tr>";

   echo " <td>Soleado</td>";

   echo " <td>Mayormente soleado</td>";

   echo " <td>Parcialmente nublado</td>";

  echo "</tr>";


  
echo "</table>";
$sql = "SELECT fecha, luz, temp, humedad,precion FROM Data";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "fecha: " . $row["fecha"]. " - luz: " . $row["luz"]. "- temp " . $row["temp"]. "- humedad " . $row["humedad"]. "- precion " . $row["precion"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
