<?php
  require_once("Rest.php");
  class mapaapi extends Rest {
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

     //$fecha_ = $_REQUEST['fecha'];
     //$array_ = array(0 =>$fecha_);
     //call_user_func_array(array($this,"getcords"),$array_);

     call_user_func(array($this,"mapaapi"));



   }

   private function convertirJson($data) {
     return json_encode($data);
   }

  private function mapaapi($fecha) {
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
    }
    //$varr = 'holismapaapi';
    $arrays = array(
    "cord1"=> array("la"=>14.643033,"ln"=>-90.559975),
    "cord2"=> array("la"=>14.643049,"ln"=>-90.554115),

);

    $this->mostrarRespuesta($this->convertirJson($arrays), 200);
    /*
    select T.temperatura FROM(select D.fecha,D.temperatura FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = 'byronjl2003@gmail.com') T ORDER BY T.fecha DESC LIMIT 1;
    */

//var obj = {co1: {la:14.643002, ln:-90.5602},co2: {la:14.637345,ln:-90.54593}};
    //$query = $this->_conn->prepare("select cord1,cord2 from data2 where fecha between '2017-10-17' and '2017-10-18' limit 1");
    //$query->bindValue(1,$email, PDO::PARAM_STR)
    //$query->bindValue(2,$fecha1, PDO::PARAM_STR);
    //$query->bindValue(3,$fecha2, PDO::PARAM_STR);
    //$query->execute();

    //$filas = $query->fetchAll(PDO::FETCH_ASSOC);
    //$num = count($filas);
    //if ($num > 0) {
      //$respuesta['Data'] = $filas;
      //$this->mostrarRespuesta($this->convertirJson($respuesta), 200);
      //$this->mostrarRespuesta('{co1: {la:14.643002, ln:-90.5602},co2: {la:14.637345,ln:-90.54593}}', 200);
      //$this->mostrarRespuesta('holismapaapi', 200);
    //}
    //$this->mostrarRespuesta($this->devolverError(2), 204);
    }

 }
 $api = new mapaapi();
 $api->procesarLLamada();
 ?>
