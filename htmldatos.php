<HTML>

<HEAD>

<TITLE>Un Titulo para el Browser de turno </TITLE>

</HEAD>

<BODY>

<!-- Aqui va todo lo chachi -->

<H1>DATOS METEREOLOGICOS</H1>


<table border="1" cellspacing=1 cellpadding=2 style="font-size: 8pt"><tr>
<td><font face="verdana"><b>Fecha y Hora</b></font></td>
<td><font face="verdana"><b>Luminosidad</b></font></td>
<td><font face="verdana"><b>Temperatura</b></font></td>
<td><font face="verdana"><b>Humedad</b></font></td>
<td><font face="verdana"><b>Precion</b></font></td>
</tr>

<?php
include("conexion.php");
  
$link = Conectarse();  
//se envia la consulta  
$result = mysql_query("SELECT fecha,luz,temp,humedad,precion FROM Data;", $link);
while ($row = mysql_fetch_row($result)){   
    echo "<tr>";  
    echo "<td>$row[0]></td>";  
    echo "<td>$row[1]</td>";  
    echo "<td>$row[2]</td>";
    echo "<td>$row[3]</td>";
    echo "<td>$row[4]</td>";    
    echo "</tr>";  
}
?>  
</table>
</BODY>

</HTML>
