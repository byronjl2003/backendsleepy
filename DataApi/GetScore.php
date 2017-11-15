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

     $sql = "
         SELECT (Z.scoreluz + Z.scoresonido + Z.scoretemp + Z.scorehumedad) as score FROM (
          SELECT
           CASE
             WHEN P.luz <= 10 THEN (((P.luz - 0)/(10-0))*(25-18.76))+18.76
             WHEN P.luz > 10 and P.luz <=256 THEN (((P.luz - 11)/(256-11))*(18.75-12.51))+ 12.51
             WHEN P.luz > 256 and P.luz <=512 THEN (((P.luz - 257)/(512-257))*(18.75-12.51))+ 12.51
             WHEN P.luz > 512 and P.luz <=768 THEN (((P.luz-513)/(768-513))*(12.50-6.26))+ 6.26
             WHEN P.luz > 768 THEN 6.25
           END as scoreluz,
           CASE
             WHEN P.sonido <=255 THEN (((P.sonido-0)/(255-0))*(25-18.75))+18.76
             WHEN P.sonido > 255 and P.sonido <=511 THEN (((P.sonido-256)/(511-256))*(18.75-12.51))+12.51
             WHEN P.sonido > 511 and P.sonido <=767 THEN (((P.sonido-512)/(767-512))*(12.50-6.26))+6.26
             WHEN P.sonido < 768 and P.sonido <=1023 THEN (((P.sonido-768)/(1023-768))*(6.25))
           END as scoresonido,
           CASE
             WHEN P.temperatura <= 12 THEN 6.25
             WHEN P.temperatura > 12 and P.temperatura <=14 THEN (((P.temperatura - 12)/(14-12))*(12.50-6.26))+ 6.26
             WHEN P.temperatura > 14 and P.temperatura <=22 THEN (((P.temperatura - 15)/(22-15))*(25-18.76))+ 18.76
             WHEN P.temperatura > 22 and P.temperatura <=26 THEN (((P.temperatura- 23)/(26-23))*(18.75-12.51))+ 12.51
             WHEN P.temperatura > 26 THEN 6.25
           END as scoretemp,
           CASE
             WHEN P.humedad <= 25 THEN (((P.humedad - 0)/(25-0))*(6.25-0))
             WHEN P.humedad > 25 and P.humedad <=49 THEN (((P.humedad - 26)/(49-26))*(12.50-6.26))+ 6.26
             WHEN P.humedad > 49 and P.humedad <=70 THEN (((P.humedad - 50)/(70-50))*(25-18.76))+ 18.76
             WHEN P.humedad > 70 and P.humedad <=80 THEN (((P.humedad- 71)/(80-71))*(18.75-12.51))+ 12.51
             WHEN P.humedad > 80 THEN (((P.humedad- 81)/(100-81))*(6.25))
           END as scorehumedad
           FROM(SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz, avg(T.sonido) as sonido FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = ? and D.fecha between ('2017-11-15'-INTERVAL 24 HOUR) and '2017-11-15') T) P
           ) Z
";
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
