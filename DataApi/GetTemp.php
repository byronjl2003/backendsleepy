<?php
  require_once("Rest.php");
  class Api extends Rest {
   const servidor = "localhost";
   const usuario_db = "root";
   const pwd_db = "Birlolo57521814";
   const nombre_db = "sf";
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

     call_user_func(array($this,"gettemp"));



   }

   private function convertirJson($data) {
     return json_encode($data);
   }
   private function saludo()
  {
    if ($_SERVER['REQUEST_METHOD']!= "GET"){
        $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)),405);
      }
    $respuesta['estado'] = 'correcto';
    $respuesta['saludo'] = 'HOLA DESDE DataApi/GetTemp';


    $this->mostrarRespuesta($this->convertirJson($respuesta),200);


  }


  private function gettemp() {
     if ($_SERVER['REQUEST_METHOD'] != "GET") {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
     }
     /*
     select T.temperatura FROM(select D.fecha,D.temperatura FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = 'byronjl2003@gmail.com') T ORDER BY T.fecha DESC LIMIT 1;
     */
     
     $query = $this->_conn->prepare("select T.temperatura FROM(select D.fecha,D.temperatura FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = ?) T ORDER BY T.fecha DESC LIMIT 1;");
     $query->bindValue(1,'byronjl2003@gmail.com', PDO::PARAM_STR);
     $query->execute();
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

 }
 $api = new Api();
 $api->procesarLLamada();
 ?>
