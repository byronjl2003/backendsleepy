  <?php
    require_once("Rest.php");
    class SaveData extends Rest {
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
        /*
          ->email: varchar
          ->temp:double
          ->hume: double
          ->luz: double
          ->sonido: double
          ->mov: int
        */
        $email = $_REQUEST['email'];
        $temp = $_REQUEST['temp'];
        $hume = $_REQUEST['hume'];
        $luz = $_REQUEST['luz'];
        $sonido = $_REQUEST['sonido'];
        $mov = $_REQUEST['mov'];
        $ronq = $_REQUEST['ronq'];
        if(!$ronq){
          $ronq = 0;
        }
        save_data($email, $temp, $hume, $luz, $sonido, $mov, $ronq);
     }

     private function convertirJson($data) {
       return json_encode($data);
     }

    private function save_data($email, $temp, $hume, $luz, $sonido, $mov, $ronq){
       if ($_SERVER['REQUEST_METHOD'] != "POST") {
         $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
       }
       $query = $this->_conn->prepare(
        "START TRANSACTION;
          SELECT @usuario := id FROM usuario WHERE email = ".$email.";
          INSERT INTO data(fecha, temperatura, humedad, movimiento, luz, sonido, ronquido)
          VALUES(NOW()-INTERVAL 6 HOUR,".$temp.",".$hume.",". $luz.",".$sonido.",". $mov.", ".$ronq.");
          SELECT @nuevadata:=LAST_INSERT_ID();
          INSERT INTO master_det(id, id_user)
          VALUES (@nuevadata,  @usuario);
          COMMIT;");
       $query->execute();
          $resp = array('estado' => "correcto");
          $this->mostrarRespuesta($this->convertirJson($resp), 200);

     }

   }
   $api = new SaveData();
   $api->procesarLLamada();
   ?>
