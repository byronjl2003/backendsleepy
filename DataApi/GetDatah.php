<?php
  require_once("Rest.php");
  class GetDatah extends Rest {
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

     $email_ = $_REQUEST['email'];
     $fecha1_ = $_REQUEST['fecha1'];
     $fecha2_ = $_REQUEST['fecha2'];
     $array_ = array(0 =>$email_,1 =>$fecha1_,2 =>$fecha2_);
     call_user_func_array(array($this,"getdatah"),$array_);

     //call_user_func(array($this,"gettemp"));



   }

   private function convertirJson($data) {
     return json_encode($data);
   }

  private function getdatah($email,$fecha1,$fecha2) {
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
    }
    /*
    select T.temperatura FROM(select D.fecha,D.temperatura FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = 'byronjl2003@gmail.com') T ORDER BY T.fecha DESC LIMIT 1;
    */

    $query = $this->_conn->prepare("select T.fecha,T.temperatura FROM (select D.fecha,D.temperatura FROM master_det M 	INNER JOIN data D ON M.id = D.id WHERE M.correo = ? ) T WHERE T.fecha BETWEEN ? AND ?  ORDER BY T.fecha ASC");
    $query->bindValue(1,$email, PDO::PARAM_STR);
    $query->bindValue(2,$fecha1, PDO::PARAM_STR);
    $query->bindValue(3,$fecha2, PDO::PARAM_STR);
    $query->execute();
    $filas = $query->fetchAll(PDO::FETCH_ASSOC);
    $num = count($filas);
    if ($num > 0) {
      $respuesta['Data'] = $filas;
      $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
    }
    $this->mostrarRespuesta($this->devolverError(2), 204);
    }

 }
 $api = new GetDatah();
 $api->procesarLLamada();
 ?>
