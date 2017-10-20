
<?php
 require_once("Rest.php");
 class Api extends Rest {
   const servidor = "localhost";

   const usuario_db = "root";
   const pwd_db = "Birlolo57521814";
   const nombre_db = "Arqui2";  
   private $_conn = NULL;
   private $_metodo;
   private $_argumentos;
   public function __construct() {
     parent::__construct();
     $this->conectarDB();
   }
   private function conectarDB() {
     $dsn = 'mysql:dbname=' . self::nombre_db . ';host=' . self::servidor;
     try {
       $this->_conn = new PDO($dsn, self::usuario_db, self::pwd_db);
     } catch (PDOException $e) {
       echo 'Falló la conexión: ' . $e->getMessage();
     }
   }
   private function devolverError($id) {
     $errores = array(
       array('estado' => "error", "msg" => "petición no encontrada"),
       array('estado' => "error", "msg" => "petición no aceptada"),
       array('estado' => "error", "msg" => "petición sin contenido"),
       array('estado' => "error", "msg" => "email o password incorrectos"),
       array('estado' => "error", "msg" => "error borrando usuario"),
       array('estado' => "error", "msg" => "error actualizando nombre de usuario"),
       array('estado' => "error", "msg" => "error buscando usuario por email"),
       array('estado' => "error", "msg" => "error creando usuario"),
       array('estado' => "error", "msg" => "usuario ya existe")
     );
     return $errores[$id];
   }
   public function procesarLLamada() {
     if (isset($_REQUEST['url'])) {



        $url_ = strtolower($_REQUEST['url']);
       $comp4 = "getpronostico";
       $comp5 = "getaire";
       $comp0 = "getinfo";
        $comp1 = "getdata";
        $comp2 = "setdata";
       if($url_ == $comp5)
	{
	 call_user_func(array($this,"getaire"));
	}
	else if ($url_ == $comp4)
	{
	  call_user_func(array($this,"getpronostico"));
	}
        else if($url_ == $comp0)
        {
         $cant_ = $_REQUEST['cant'];
         $array_ = array(0 => $cant_);
         call_user_func_array(array($this,"getinfo"),$array_);
        }
        elseif($url_ == $comp1)
        {

           $var = "ES GETDATA";
            call_user_func(array($this,"getdata"));
        }
        elseif($url_ == $comp2)
        {
          // $var = "ES SETDATA";
           // call_user_func_array(array($this,"saludo"),array($var));

          //$var = "ES SETDATA";
            $cord1_ = $_REQUEST['cord1'];
            $cord2_ = $_REQUEST['cord2'];
            $ppm_ = $_REQUEST['ppm'];
            $array_ = array(0 =>$cord1_ , 1 => $cord2_, 2 => $ppm_);
             call_user_func_array(array($this,"setdata"),$array_);
        }
        else
        {
          $var = "ES SALUDO";
            call_user_func_array(array($this,"saludo"),array($var));

        }

      }
      else
      {
          $url_ = "ESTUPIDFES";
          call_user_func_array(array($this,"saludo"),array($url_));
          //$this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
      }


   }

/*

  private function getpronostico()
{
   $arraydia11 = array("time" => "m", "temp" =>"77");
   $arraydia12 = array("time" => "t", "temp" =>"88");
   $arraydia13 = array("time" => "n", "temp" =>"99");

   $arraydia1 = array($arraydia11,$arraydia12,$arraydia13);

	$arraydia21 = array("time" => "m", "temp"=> "44");
   $arraydia22 = array("time" => "t", "temp"=> "55");
   $arraydia23 = array("time" => "n", "temp"=> "66");

   $arraydia2 = array($arraydia21,$arraydia22,$arraydia23);

   $arraydia31 = array("time" => "m", "temp"=>"11");
   $arraydia32 = array("time" => "t", "temp"=> "22");
   $arraydia33 = array("time" => "n", "temp"=> "33");

   $arraydia3 = array($arraydia31,$arraydia32,$arraydia33);





	$respuesta['Pronostico'] = array($arraydia1,$arraydia2,$arraydia3);


       // $respp['Aire'] = array('calidad' =>"Buena",'ppm'=>"123");
	$this->mostrarRespuesta($this->convertirJson($respuesta),200);
}
*/

private function getpronostico()
{

	$sql = "select
	avg(case when C.hours >= 7 and C.hours <=12 then C.temp else null end ) as avgtempdia,
	avg(case when C.hours >= 13 and C.hours <=18 then C.temp else null end ) as avgtemptarde,
	avg(case when C.hours >= 19 and C.hours <=23 then C.temp else null end ) as avgtempnoche,
	stddev(case when C.hours >= 7 and C.hours <=12 then C.temp else null end ) as devtempdia,
	stddev(case when C.hours >= 13 and C.hours <=18 then C.temp else null end ) as devtemptarde,
	stddev(case when C.hours >= 19 and C.hours <=23 then C.temp else null end ) as devtempnoche,

	avg(case when C.hours >= 7 and C.hours <=12 then C.humedad else null end ) as avghumedaddia,
	avg(case when C.hours >= 13 and C.hours <=18 then C.humedad else null end ) as avghumedadtarde,
	avg(case when C.hours >= 19 and C.hours <=23 then C.humedad else null end ) as avghumedadnoche,
	stddev(case when C.hours >= 7 and C.hours <=12 then C.humedad else null end ) as devhumedaddia,
	stddev(case when C.hours >= 13 and C.hours <=18 then C.humedad else null end ) as devhumedadtarde,
	stddev(case when C.hours >= 19 and C.hours <=23 then C.humedad else null end ) as devhumedadnoche,

	avg(case when C.hours >= 7 and C.hours <=12 then C.precion else null end ) as avgpreciondia,
	avg(case when C.hours >= 13 and C.hours <=18 then C.precion else null end ) as avgpreciontarde,
	avg(case when C.hours >= 19 and C.hours <=23 then C.precion else null end ) as avgprecionnoche,
	stddev(case when C.hours >= 7 and C.hours <=12 then C.precion else null end ) as devpreciondia,
	stddev(case when C.hours >= 13 and C.hours <=18 then C.precion else null end ) as devpreciontarde,
	stddev(case when C.hours >= 19 and C.hours <=23 then C.precion else null end ) as devprecionnoche




	FROM
	(
SELECT   DATE(fecha) as dia,HOUR(fecha) AS Hours,MINUTE(fecha) AS minutes, temp,humedad,precion
FROM     Data
GROUP BY hours,dia,minutes
)C;
"
;



	 $avgtempdia;
	$avgtemptarde;
	$avgtempnoche;
	$devtempdia;
	$devtemptarde;
        $devtempnoche;
	 $avghumedaddia;
	$avghumedadtarde;
	$avghumedadnoche;
	$devhumedaddia;
	$devhumedadtarde;
	$devhumedadnoche;
	$avgpreciondia;
	$avgpreciontarde;
	$avgprecionnoche;
	$devpreciondia;
	$devpreciontarde;
	$devprecionnoche;
   foreach ($this->_conn->query($sql) as $row) {

		  $avgtempdia = $row['avgtempdia'];
		  $avgtemptarde = $row['avgtemptarde'];
		  $avgtempnoche = $row['avgtempnoche'];
		  $devtempdia = $row['devtempdia'];
		  $devtemptarde = $row['devtemptarde'];
		  $devtempnoche = $row['devtempnoche'];
		  $avghumedaddia = $row['avghumedaddia'];
		  $avghumedadtarde = $row['aavghumedadtarde'];
		  $avghumedadnoche = $row['avghumedadnoche'];
		  $devhumedaddia = $row['devhumedaddia'];
		  $devhumedadtarde = $row['devhumedadtarde'];
		  $devhumedadnoche = $row['devhumedadnoche'];
	 	  $avgpreciondia = $row['avgpreciondia'];
		  $avgpreciontarde = $row['avgpreciontarde'];
		  $avgprecionnoche = $row['avgprecionnoche'];
		  $devpreciondia = $row['devpreciondia'];
		  $devpreciontarde = $row['devpreciontarde'];
		  $devprecionnoche = $row['devprecionnoche'];
		 }


   $arraydia11 = array("time" => "m", "temp"=>"77","clima"=>"1");
   $arraydia12 = array("time" => "t", "temp"=>"88","clima"=>"2");
   $arraydia13 = array("time" => "n", "temp"=>"99","clima"=>"2");

   $arraydia1 = array($arraydia11,$arraydia12,$arraydia13);

	$arraydia21 = array("time" => "m", "temp"=>"44","clima"=>"1");
   $arraydia22 = array("time" => "t", "temp"=>"55","clima"=>"3");
   $arraydia23 = array("time" => "n", "temp"=>"66","clima"=>"3");

   $arraydia2 = array($arraydia21,$arraydia22,$arraydia23);

   $arraydia31 = array("time" => "m", "temp"=>"11","clima"=>"1");
   $arraydia32 = array("time" => "t", "temp"=>"22","clima"=>"1");
   $arraydia33 = array("time" => "n", "temp"=>"33","clima"=>"3");

   $arraydia3 = array($arraydia31,$arraydia32,$arraydia33);



	$respuesta['Pronostico'] = array($arraydia1,$arraydia2,$arraydia3);
	$respp['Pronostico'] = array($avgtempdia,$avgtemptarde,$avgtempnoche);
	$this->mostrarRespuesta($this->convertirJson($respuesta),200);
}
   private function getaire()
{
$sql = "select particulas from Data ORDER BY id DESC LIMIT 1;";
	$lastaire;
	$respp;
      foreach($this->_conn->query($sql)as $row)
    {
  		$lastaire =(int) $row['particulas'];

//		$lastaire =  "fdfdf";
	//	$this->mostrarRespuesta($this->convertirJson($lastaire),200);

        if ($lastaire<=85){

		$respp['Aire'] = array('calidad'=> "Buena",'ppm'=>$lastaire);
	  $this->mostrarRespuesta($this->convertirJson($respp),200);
	}
	elseif($lastaire>85 && $lastaire<=176)
	{
	//	$lastaire +="";
		$respp['Aire'] = array('calidad'=> "Regular",'ppm'=> $lastaire);
 		 $this->mostrarRespuesta($this->convertirJson($respp),200);
	}
	else
	{
	//	$lastaire  +="";
		$respp['Aire'] = array('calidad'=>"Mala",'ppm'=>$lastaire);
		  $this->mostrarRespuesta($this->convertirJson($respp),200);
	}
}

//	$this->mostrarRespuesta($this->convertirJson($respp),200);
}
   private function convertirJson($data) {
     return json_encode($data);
   }
   private function saludo($para)
  {
    if ($_SERVER['REQUEST_METHOD']!= "GET"){
        $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)),405);
      }
    $respuesta['estado'] = 'correcto';
    $respuesta['saludo'] = probabilidad(100);
    $respuesta['URL'] = $para;

    $this->mostrarRespuesta($this->convertirJson($respuesta),200);


  }
function probabilidad($_para1)
{
return $_para;
$z;
$E;
$A;
$S;
$A1;
$n;

$z = $_para1;
$E = 0.05;

$S=$z;
$A=$z;
$n=0;
return 1;
do
{

	$A = $A * -1 * $z * $z/($n+2);
	$n = $n+2;
	$A1 = A/($n+1);
	$S = $S+$A1;
}while(abs($A1)>$E) ;

//return $S/sqrt(2*pi());

}

  private function getdata() {
     if ($_SERVER['REQUEST_METHOD'] != "GET") {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
     }
     $query = $this->_conn->query("SELECT * FROM data2");
     $filas = $query->fetchAll(PDO::FETCH_ASSOC);
     $num = count($filas);
     if ($num > 0) {
       $respuesta['Data'] = $filas;
       $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
     }
     $this->mostrarRespuesta($this->devolverError(2), 204);
   }

 private function getinfo($cant)
 {

   if($_SERVER['REQUEST_METHOD']!= "GET"){
   	$this->mostrarRespuesta($this->convertirJson($this->devolverError(1)),405);
    }



   $query =  $this->_conn->prepare("SELECT * FROM Data ORDER BY fecha DESC LIMIT 10 ;");
  // $query->bindValue(1,$cant,PDO::PARAM_INT);
  $query->execute(array($cant));
  $filas = $query->fetchAll(PDO::FETCH_ASSOC);
  //  $respuesta['Data'] = $filas;

  // $this->mostrarRespuesta($this->convertirJson($respuesta),200);
   $array_  =  array("varCant" => $cant,"Data"=> $filas);
   $this->mostrarRespuesta($this->convertirJson($array_),200);


 }
  private function setdata($cord1,$cord2,$ppm)
  {
    if ($_SERVER['REQUEST_METHOD']!= "POST"){
        $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)),405);
      }
     $query = $this->_conn->prepare("INSERT INTO data2(fecha,cord1,cord2,ppm)VALUES(NOW()-INTERVAL 6 HOUR,?,?,?);");
     $query->bindValue(1,$cord1, PDO::PARAM_INT);
     $query->bindValue(2, $cord2, PDO::PARAM_INT);
     $query->bindValue(3, $ppm, PDO::PARAM_INT);
     $query->execute();
       //rowcount para insert, delete. update
      // $filasBorradas = $query->rowCount();
       //if ($filasBorradas == 1) {
        $resp = array('estado' => "correcto", "para1" => $cord1,"para2" => $cord2,"para3" => $ppm);
        $this->mostrarRespuesta($this->convertirJson($resp), 200);
       //} else {
        // $this->mostrarRespuesta($this->convertirJson($this->devolverError(4)), 400);
       //}


  }


   private function crearUsuario() {
     if ($_SERVER['REQUEST_METHOD'] != "POST") {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
     }
     if (isset($this->datosPeticion['nombre'], $this->datosPeticion['email'], $this->datosPeticion['pwd'])) {
       $nombre = $this->datosPeticion['nombre'];
       $pwd = $this->datosPeticion['pwd'];
       $email = $this->datosPeticion['email'];
       if (!$this->existeUsuario($email)) {
         $query = $this->_conn->prepare("INSERT into usuario (nombre,email,password,fRegistro) VALUES (:nombre, :email, :pwd, NOW())");
         $query->bindValue(":nombre", $nombre);
         $query->bindValue(":email", $email);
         $query->bindValue(":pwd", sha1($pwd));
         $query->execute();
         if ($query->rowCount() == 1) {
           $id = $this->_conn->lastInsertId();
           $respuesta['estado'] = 'correcto';
           $respuesta['msg'] = 'usuario creado correctamente';
           $respuesta['usuario']['id'] = $id;
           $respuesta['usuario']['nombre'] = $nombre;
           $respuesta['usuario']['email'] = $email;
           $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
         }
         else
           $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);
       }
       else
         $this->mostrarRespuesta($this->convertirJson($this->devolverError(8)), 400);
     } else {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);
     }
   }
 }
 $api = new Api();
 $api->procesarLLamada();
 ?>
