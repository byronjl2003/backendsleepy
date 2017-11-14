<?php
  require_once("Rest.php");
  class GetData extends Rest {
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
     $array_ = array(0 =>$email_);
     call_user_func_array(array($this,"getdata"),$array_);

     //call_user_func(array($this,"gettemp"));



   }

   private function convertirJson($data) {
     return json_encode($data);
   }

  private function getdata($email) {
     if ($_SERVER['REQUEST_METHOD'] != "GET") {
       $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
     }
     /*
     select T.temperatura FROM(select D.fecha,D.temperatura FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = 'byronjl2003@gmail.com') T ORDER BY T.fecha DESC LIMIT 1;
     insert into data(fecha,temperatura,humedad,movimiento,luz,sonido,ronquido) VALUES('1992-10-19 11:11:11',66.66,66,5,90,72,72);
     create table data(
        id int not null AUTO_INCREMENT,
        fecha datetime not null,
        temperatura double not null,
        humedad double not null,
        movimiento int not null,
        luz double not null,
        sonido double not null,
        ronquido double not null,
        PRIMARY KEY(id)
    );
//insert into usuario(nombre,email,pass) VALUES('Byron Jose','byronjl2003@gmail.com','123456789');
    create table usuario(
      id int not null AUTO_INCREMENT,
      nombre varchar(100),
      email varchar(100),
      pass varchar(30),
      PRIMARY KEY(id)
  );

  create table master_det(

  id int not null,
  id_user int not null,
  FOREIGN KEY(id) REFERENCES data(id),
  FOREIGN KEY(id_user) REFERENCES usuario(id),
  PRIMARY KEY(id,id_user)
);

create table score(
  id int not null AUTO_INCREMENT,
  id_user int not null,
  score int not null,
  fecha date not null,
  FOREIGN KEY(id_user) REFERENCES usuario(id),
  PRIMARY KEY(id)

);

create table accion(
  id int not null AUTO_INCREMENT,
  accion char not null,
  PRIMARY KEY(id)
);



     */

     $query = $this->_conn->prepare("select T.temperatura,T.humedad,T.movimiento,T.luz,T.sonido,T.ronquido FROM(select D.fecha,D.temperatura,D.humedad,D.movimiento,D.luz,D.sonido,D.ronquido FROM master_det M INNER JOIN data D ON M.id = D.id WHERE M.correo = ?) T ORDER BY T.fecha DESC LIMIT 1;");
     $query->bindValue(1,$email, PDO::PARAM_STR);
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
 $api = new GetData();
 $api->procesarLLamada();
 ?>
