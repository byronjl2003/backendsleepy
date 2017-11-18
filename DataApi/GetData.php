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
     CREATE TABLE estados(id int not null,esonido varchar(5),etemp varchar(5),eluz varchar(5),emov varchar(5),PRIMARY KEY (id));



     SELECT SUM(
        CASE
          WHEN P.luz <= 10 THEN (((P.luz - 0)/(10-0))*(25-18.76))+18.76
          WHEN P.luz > 10 and P.luz <=256 THEN (((P.luz - 11)/(256-11))*(18.75-12.51))+ 12.51
          WHEN P.luz > 256 and P.luz <=512 THEN (((P.luz - 257)/(512-257))*(18.75-12.51))+ 12.51
          WHEN P.luz > 512 and P.luz <=768 THEN (((P.luz-513)/(768-513))*(12.50-6.26))+ 6.26
          WHEN P.luz > 768 THEN 6.25
          WHEN P.luz <= 10 THEN (((P.luz - 0)/(10-0))*(25-18.76))+18.76
          WHEN P.luz > 10 and P.luz <=256 THEN (((P.luz - 11)/(256-11))*(18.75-12.51))+ 12.51
          WHEN P.luz > 256 and P.luz <=512 THEN (((P.luz - 257)/(512-257))*(18.75-12.51))+ 12.51
          WHEN P.luz > 512 and P.luz <=768 THEN (((P.luz-513)/(768-513))*(12.50-6.26))+ 6.26
          WHEN P.luz > 768 THEN 6.25
          WHEN P.sonido <=255 THEN (((P.sonido-0)/(255-0))*(25-18.75))+18.76
          WHEN P.sonido > 255 and P.sonido <=511 THEN (((P.sonido-256)/(511-256))*(18.75-12.51))+12.51
          WHEN P.sonido > 511 and P.sonido <=767 THEN (((P.sonido-512)/(767-512))*(12.50-6.26))+6.26
          WHEN P.sonido < 768 and P.sonido <=1023 THEN (((P.sonido-768)/(1023-768))*(6.25))
          WHEN P.temperatura <= 12 THEN 6.25
          WHEN P.temperatura > 12 and P.temperatura <=14 THEN (((P.temperatura - 12)/(14-12))*(12.50-6.26))+ 6.26
          WHEN P.temperatura > 14 and P.temperatura <=22 THEN (((P.temperatura - 15)/(22-15))*(25-18.76))+ 18.76
          WHEN P.temperatura > 22 and P.temperatura <=26 THEN (((P.temperatura- 23)/(26-23))*(18.75-12.51))+ 12.51
          WHEN P.temperatura > 26 THEN 6.25
          WHEN P.humedad <= 25 THEN (((P.humedad - 0)/(25-0))*(6.25-0))
          WHEN P.humedad > 25 and P.humedad <=49 THEN (((P.humedad - 26)/(49-26))*(12.50-6.26))+ 6.26
          WHEN P.humedad > 49 and P.humedad <=70 THEN (((P.humedad - 50)/(70-50))*(25-18.76))+ 18.76
          WHEN P.humedad > 70 and P.humedad <=80 THEN (((P.humedad- 71)/(80-71))*(18.75-12.51))+ 12.51
          WHEN P.humedad > 80 THEN (((P.humedad- 81)/(100-81))*(6.25))
          END
     ) as score
     FROM(SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz, avg(T.sonido) as sonido FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = 'churro@gmail.com' and D.fecha between ('2017-11-15'-INTERVAL 24 HOUR) and '2017-11-15') T) P;



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
      FROM(SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz, avg(T.sonido) as sonido FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = 'churro@gmail.com' and D.fecha between ('2017-11-15'-INTERVAL 24 HOUR) and '2017-11-15') T) P;























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
      FROM(SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz, avg(T.sonido) as sonido,avg(T.movimiento) as movimiento FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido,D.movimiento FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = 'churro@gmail.com' and D.fecha between CAST((NOW()-INTERVAL 30 HOUR) AS char) and CAST((NOW()-INTERVAL 6 HOUR) AS char)) T) P
      ) Z ;


SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz, avg(T.sonido) as sonido FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = 'churro@gmail.com' and D.fecha between CAST((NOW()-INTERVAL 30 HOUR) AS char) and CAST((NOW()-INTERVAL 6 HOUR) AS char)) T

SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz, avg(T.sonido) as sonido FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = 'churro@gmail.com' and D.fecha between ('2017-11-17'-INTERVAL 24 HOUR) and '2017-11-17') T;



menor >mayor

      SELECT
      CASE
        WHEN P.sonido <=255 THEN (((P.sonido-0)/(255-0))*(25-18.75))+18.76
        WHEN P.sonido > 255 and P.sonido <=511 THEN (((P.sonido-256)/(511-256))*(18.75-12.51))+12.51
        WHEN P.sonido > 511 and P.sonido <=767 THEN (((P.sonido-512)/(767-512))*(12.50-6.26))+6.26
          ELSE 1
      END as scoresonido
      FROM(SELECT avg(T.temperatura) as temperatura,avg(T.humedad) as humedad,avg(T.luz) as luz,avg(T.sonido) as sonido FROM(select D.fecha,D.temperatura,D.humedad,D.luz,D.sonido FROM master_det M 	INNER JOIN data D ON M.id = D.id INNER JOIN usuario U ON U.id = M.id_user WHERE U.email = 'churro@gmail.com' and D.fecha between ('2017-11-15'-INTERVAL 24 HOUR) and '2017-11-15') T) P;


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
