<?php
  require_once("Rest.php");
  class SetAccion extends Rest {
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


     $accion_ = $_REQUEST['accion'];
     $array_ = array(0 =>$accion_);
     call_user_func_array(array($this,"setaccion"),$array_);

     //call_user_func(array($this,"gettemp"));



   }

   private function convertirJson($data) {
     return json_encode($data);
   }

  private function setaccion($accion) {
     if ($_SERVER['REQUEST_METHOD'] != "POST") {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
     }

     $query = $this->_conn->prepare("INSERT INTO accion(accion) VALUES(?)");
     $query->bindValue(1,$accion, PDO::PARAM_STR);

     $query->execute();
        $resp = array('estado' => "correcto");
        $this->mostrarRespuesta($this->convertirJson($resp), 200);
   }

 }
 $api = new SetAccion();
 $api->procesarLLamada();
 ?>
