<?php
  require_once("Rest.php");
  class SetTrafico extends Rest {
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

     $cord1_ = $_REQUEST['cord1'];
     $cord2_ = $_REQUEST['cord2'];
     $array_ = array(0 =>$cord1_,1 =>$cord2_);
     call_user_func_array(array($this,"settrafico"),$array_);

     //call_user_func(array($this,"settrafico"));



   }

   private function convertirJson($data) {
     return json_encode($data);
   }

  private function settrafico($cord1,$cord2) {
     if ($_SERVER['REQUEST_METHOD'] != "POST") {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
     }
     $query = $this->_conn->prepare("INSERT INTO datatrafic(fecha,cord1,cord2)VALUES(NOW()-INTERVAL 6 HOUR,?,?)");
     $query->bindValue(1,$cord1, PDO::PARAM_INT);
     $query->bindValue(2, $cord2, PDO::PARAM_INT);
     $query->execute();
        $resp = array('estado' => "correcto",'cord2'=> $cord2);
        $this->mostrarRespuesta($this->convertirJson($resp), 200);

   }

 }
 $api = new SetTrafico();
 $api->procesarLLamada();
 ?>
