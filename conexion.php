<?php  
function Conectarse()  
{  
   if (!($link=mysql_connect("localhost","root","Birlolo57521814")))  
   {  
      echo "Error conectando a la base de datos.";  
      exit();  
   }  
   if (!mysql_select_db("Arqui2",$link))  
   {  
      echo "Error seleccionando la base de datos.";  
      exit();  
   }  
   return $link;  
}  
?>  
