<!doctype html>
<html lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Default Page Title</title>
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" href="favicon.ico">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<BODY>
<H2>DATOS METEREOLOGICOS</H2>
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
?>
<table  BORDER="3" CELLSPACING="5" >
  <tr>
    <th scope="col">Fecha</th>
    <th scope="col">Luminosidad</th>
    <th scope="col">Temperatura</th>
    <th scope="col">Humedad</th>
    <th scope="col">Precion</th>
    <th scope="col">Altitud</th>
    <th scope="col">Particulas(Aire)</th>
  </tr>
 
  


<?php
$sql = "SELECT fecha, luz, temp, humedad,precion,altitud,particulas FROM Data";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      
        //echo "fecha: " . $row["fecha"]. " - luz: " . $row["luz"]. "- temp " . $row["temp"]. "- humedad " . $row["humedad"]. "- precion " . $row["precion"]. "<br>";
        echo "<tr>" ."<td>". $row["fecha"]."</td>" . "<td>". $row["luz"]."</td>" . "<td>". $row["temp"]."</td>" . "<td>". $row["humedad"]."</td>". "<td>". $row["precion"]."</td>"."<td>". $row["altitud"]."</td>"."<td>". $row["particulas"]."</td>" . "</tr>";   
    
    
    
    
  
  
    }
} else {
    echo "0 results";
}
$conn->close();
?>
</table>
</BODY>
</HTML>
