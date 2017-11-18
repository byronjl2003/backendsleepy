  <?php
    require_once("Rest.php");
    class SaveData extends Rest {
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
        /*
          ->email: varchar
          ->temp:double
          ->hume: double
          ->luz: double
          ->sonido: double
          ->mov: int
        */
        //http://45.55.148.193/sf/DataApi/SaveData.php?email=churro@gmail.com&temp=99.99&hume=99.99&luz=1&sonido=&,mov=1,ronq=0
        $email = $_REQUEST['email'];
        $temp = $_REQUEST['temp'];
        $hume = $_REQUEST['hume'];
        $luz = $_REQUEST['luz'];
        $sonido = $_REQUEST['sonido'];
        $mov = $_REQUEST['mov'];
        $ronq = 0;
        /*if(!$ronq){
          $ronq = 0;
        }
        */
        $array_ = array(0 =>$email,1 =>$temp,2 =>$hume,3 =>$luz,4 =>$sonido,5=>$mov,6=>$ronq);
        call_user_func_array(array($this,"save_data"),$array_);

        //save_data($email, $temp, $hume, $luz, $sonido, $mov, $ronq);
     }

     private function convertirJson($data) {
       return json_encode($data);
     }

    private function save_data($email, $temp, $hume, $luz, $sonido, $mov, $ronq){
       if ($_SERVER['REQUEST_METHOD'] != "POST") {
         $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
       }

       $query = $this->_conn->prepare(
/*
        "START TRANSACTION;
          SELECT @usuario := id FROM usuario WHERE email = ".$email.";
          INSERT INTO data(fecha, temperatura, humedad, movimiento, luz, sonido, ronquido)
          VALUES(NOW()-INTERVAL 6 HOUR,".$temp.",".$hume.",". $luz.",".$sonido.",". $mov.", ".$ronq.");
          SELECT @nuevadata:=LAST_INSERT_ID();
          INSERT INTO master_det(id, id_user)
          VALUES (@nuevadata,  @usuario);
          COMMIT;");
*/

          $query = $this->_conn->prepare("INSERT INTO data(fecha, temperatura, humedad, movimiento, luz, sonido, ronquido) values(NOW()-INTERVAL 6 HOUR,?,?,?,?,?,?)");
          $query->bindValue(1,$temp, PDO::PARAM_INT);
          $query->bindValue(2,$hume, PDO::PARAM_INT);
          $query->bindValue(3,$mov, PDO::PARAM_INT);
          $query->bindValue(4,$luz, PDO::PARAM_INT);
          $query->bindValue(5,$sonido, PDO::PARAM_INT);
          $query->bindValue(6,$ronq, PDO::PARAM_INT);

       $query->execute();
       //$last_id = $this->_conn->insert_id;
          $resp = array('estado' => "correcto",'para1'=>$email,'para12'=>$temp,'para13'=>$hume,'para14'=>$luz,'para15'=>$sonido,'para16'=>$mov,'para17'=>$ronq,'ID'=> 7777);
          $this->mostrarRespuesta($this->convertirJson($resp), 200);

     }

   }
   $api = new SaveData();
   $api->procesarLLamada();
   ?>
