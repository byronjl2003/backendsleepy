<?php
  require_once("Rest.php");
  class GetScore extends Rest {
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

     //$email_ = $_REQUEST['email'];
     //$array_ = array(0 =>$email_);
     //call_user_func_array(array($this,"getluz"),$array_);

     //call_user_func(array($this,"getscore"));
     $email_ = $_REQUEST['email'];

     $array_ = array(0 =>$email_);
     call_user_func_array(array($this,"getscore"),$array_);



   }

   private function convertirJson($data) {
     return json_encode($data);
   }

  private function getscore($email) {
     if ($_SERVER['REQUEST_METHOD'] != "GET") {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
     }

     //$this->mostrarRespuesta('SCORE', 200);

     $sql = "SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz, avg(T.sonido) as sonido,avg(T.movimiento) as movimiento FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido,D.movimiento FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = 'churro@gmail.com' and D.fecha between CAST((NOW()-INTERVAL 30 HOUR) AS char) and CAST((NOW()-INTERVAL 6 HOUR) AS char)) T";

$query = $this->_conn->prepare($sql);
$query->bindValue(1,$email, PDO::PARAM_STR);

$query->execute();
$filas = $query->fetchAll(PDO::FETCH_ASSOC);
$num = count($filas);
if ($num > 0) {
  $respuesta['Data'] = $filas;
  $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
}
$this->mostrarRespuesta($this->devolverError(2), 204);
     /*
     select T.temperatura FROM(select D.fecha,D.temperatura FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = 'byronjl2003@gmail.com') T ORDER BY T.fecha DESC LIMIT 1;
     */
/*
     $query = $this->_conn->prepare("select T.luz FROM(select D.fecha,D.luz FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = ?) T ORDER BY T.fecha DESC LIMIT 1;");
     $query->bindValue(1,$email, PDO::PARAM_STR);
     $query->execute();
     $filas = $query->fetchAll(PDO::FETCH_ASSOC);
     $num = count($filas);
     if ($num > 0) {
       $respuesta['Data'] = $filas;
       $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
     }
     $this->mostrarRespuesta($this->devolverError(2), 204);
     */
   }

 }
 $api = new GetScore();
 $api->procesarLLamada();
 ?>
