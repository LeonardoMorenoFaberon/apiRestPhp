<?php
require_once "./clases/conexion.php";
require_once "./clases/respuestas.class.php";



class pacientes extends conexion{
  
  private $table              = "pacientes";
  private $PacienteId         ="";
  private $DNI                ="";       
  private $Nombre             ="";       
  private $Direccion          ="";       
  private $CodigoPostal       ="";
  private $Telefono           ="";      
  private $Genero             ="";       
  private $FechaNacimiento    ="0000-00-00";       
  private $Correo             ="";
  private $token              ="";
  //721a38cc92f43c54ef0f2c4d14c329ef
  //------------------------------------------------------
  public function listaPacientes($pagina = 1)  {
      // si tubieras 3000 pacientes demoraria mucho en cargar entonceds los tramos de 100 en 100
      $inicio     =   0 ; 
      $cantidad   = 100 ;
      if($pagina > 1){
          $inicio = (  $cantidad * ($pagina - 1)  ) + 1 ;
          $cantidad = $cantidad * $pagina;
      }
      $query =  "SELECT PacienteId , Nombre , DNI , Telefono , Correo FROM " . $this->table  .  " limit $inicio ,  $cantidad";
      // print_r($query);
      $datos = parent::obtenerDatos($query);
      return ($datos);
  }
  //------------------------------------------------------
  public function obtenerPaciente($id){
      $query =  "SELECT * FROM  $this->table   WHERE PacienteId = '$id'";   
      return parent::obtenerDatos($query); 
  }
  //------------------------------------------------------
  public function post($json){
      $_respuestas = new respuestas;
      $datos = json_decode($json , true );
      // aca validamos el token.............................
      // o existe o no existe el token  , si existe validar , si no existe error de no autorizacion 401 no autorizado 
      if(!isset($datos['token'])){ // si no existe token
        return $_respuestas->error_401();
      }else{        //si existe el token
        // necesitamos dos funciones que vamos a crear buscarToken()
        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();
        // var_dump($arrayToken);
        if($arrayToken){
                // validemos que los datos que nos envian contienen los campos requeridos para  un insert
                // en la tabla pacientes dni , nombre y correo son campos impresindibles para esta tabla. 
                if( !isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])  ){
                    // 
                  return $_respuestas->error_400();        
                }else{
                    //si estas aca ya podrian estar completos los datos y los insertaremos:
                    $this->Nombre   = $datos['nombre'] ;
                    $this->DNI      = $datos['dni']    ;
                    $this->Correo   = $datos['correo'];
                    // if (isset($datos['pacienteid'])) { $this->PacienteId = $datos['pacienteid']; }
                    if( isset($datos['telefono'])  ){ $this->Telefono = $datos['telefono'];}
                    if (isset($datos['direccion'])) { $this->Direccion = $datos['direccion']; }
                    if (isset($datos['codigopostal'])) { $this->CodigoPostal = $datos['codigopostal']; }
                    if (isset($datos['genero'])) { $this->Genero = $datos['genero']; }
                    if (isset($datos['fechanacimiento'])) { $this->FechaNacimiento = $datos['fechanacimiento']; }
                    $resp = $this->insertarPaciente();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"]= array( 
                                                    "PacienteId" => $resp
                                                    );
                        return $respuesta;                            
                    }else{
                        return $_respuestas->error_500();
                    }
                }
        }else{
          return $_respuestas->error_401("El token que se envio es invalido o ha caducado");
        }
      }

      //....................................................
  }//post($json)
  //------------------------------------------------------
  public function insertarPaciente(){
      
      $queryResetAutoIncrement = $this->resetarAuto_increment();
      parent::nonQueryId($queryResetAutoIncrement , $this->table);

      //este codigo debes hacerlo con tu  herramienta de github. 
      $query = "INSERT INTO $this->table (  DNI , Nombre , Direccion , CodigoPostal , Telefono , Genero , FechaNacimiento , Correo )
      VALUES  
    ('$this->DNI' , '$this->Nombre' , '$this->Direccion' , '$this->CodigoPostal' , '$this->Telefono' , '$this->Genero' , '$this->FechaNacimiento' , '$this->Correo');";

      $resp = parent::nonQueryId($query , $this->table);
    if($resp){
      return $resp;
    }else{
      return 0;
    }
  }
  //------------------------------------------------------
  public function put($json){
      $_respuestas = new respuestas;
      $datos = json_decode($json , true );

      
      // aca validamos el token.............................
      // o existe o no existe el token  , si existe validar , si no existe error de no autorizacion 401 no autorizado 
      if(!isset($datos['token'])){ // si no existe token
        return $_respuestas->error_401();
      }else{        //si existe el token
        // necesitamos dos funciones que vamos a crear buscarToken()
        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();
        // var_dump($arrayToken);
        if($arrayToken){
          // validemos que los datos que nos envian contienen los campos requeridos para  un insert
          // en la tabla pacientes dni , nombre y correo son campos impresindibles para esta tabla. 
          if(  !isset($datos['pacienteid']) ){
              // 
            return $_respuestas->error_400();   //Datos enviados incompletos o con formato incorrecto
          }else{
              //si estas aca ya podrian estar completos los datos y los insertaremos:
              $this->PacienteId   = $datos['pacienteid'];
              if (isset($datos['dni']))           { $this->DNI = $datos['dni']; }
              if (isset($datos['nombre']))        { $this->Nombre = $datos['nombre']; }
              if (isset($datos['direccion']))     { $this->Direccion = $datos['direccion']; }
              if (isset($datos['codigopostal']))  { $this->CodigoPostal = $datos['codigopostal']; }
              if (isset($datos['telefono']))      { $this->Telefono = $datos['telefono']; }
              if (isset($datos['genero']))        { $this->Genero = $datos['genero']; }
              if (isset($datos['fechanacimiento'])) { $this->FechaNacimiento = $datos['fechanacimiento']; }
              if (isset($datos['correo']))        { $this->Correo = $datos['correo']; }
              $numRowsAfected = $this->modificarPaciente();
              
              // echo $numRowsAfected;
              if( $numRowsAfected >=1  ){
                  $respuesta = $_respuestas->response;
                  $respuesta["result"]= array( 
                                              "Campos_pacientes_afectados" => $numRowsAfected
                                              );
                  return $respuesta;                            
              }else{
                  // si no se hizo modificacion por que los datos del servidor son los mismos que haz recibido.
                  $real = false;
                  if(!$real){
                    return $_respuestas->error_500("Error interno del Servidor , realmente no haz metido datos nuevos a la tabla  $this->table por ese motivo no no se ha hecho nada");
    
                  }else{
                    $respuesta =  $_respuestas->error_204("todos los datos enviados yala en la BBDD asi q fijate que tipeas!!!");
                    return $respuesta;
                  }
              }
          }
        }else{
          return $_respuestas->error_401("El token que se envio es invalido o ha caducado");
        }
      }

      //....................................................


  }//put()
          //------------------------------------------------------
    public function modificarPaciente(){
        //este codigo debes hacerlo con tu  herramienta de github. 
        $query = "UPDATE  $this->table SET  
        DNI = '$this->DNI', 
        Nombre = '$this->Nombre' , 
        Direccion = '$this->Direccion' , 
        CodigoPostal = '$this->CodigoPostal' , 
        Telefono = '$this->Telefono' , 
        Genero ='$this->Genero' , 
        FechaNacimiento = '$this->FechaNacimiento' , 
        Correo = '$this->Correo' 
        WHERE PacienteId = '$this->PacienteId'; ";
        //nonQueryId solo es para guardar.
        $numRowsAfected = parent::nonQuery($query , $this->table);
        if($numRowsAfected >= 1){  //nonQuery devuelve el numero de filas afectadas osea 1 o mayor.
          return $numRowsAfected; //devuelve numero >=1
        }else{
          // return $numRowsAfected;  //devuelve 0
          $queryIfYaHay = "SELECT COUNT(*) FROM PACIENTES WHERE DNI = '$this->DNI' AND Nombre = '$this->Nombre' AND Direccion = '$this->Direccion' AND CodigoPostal = '$this->CodigoPostal' AND Telefono = '$this->Telefono' AND  Genero = '$this->Genero' AND FechaNacimiento = '$this->FechaNacimiento' AND Correo = '$this->Correo'";

            $numRowsWhitThisInfo = parent::nonQuery($queryIfYaHay , $this->table);
            if($numRowsWhitThisInfo!=0){
                 //los datos que quiere meter ya estan identicos en su fila asi q no hay nada que hacer en la BBDD.
            } ;
        }
    }//modificar
    //------------------------------------------------------
  public function delete($json){
    $_respuestas = new respuestas;
    $datos = json_decode($json , true );

      // aca validamos el token.............................
      // o existe o no existe el token  , si existe validar , si no existe error de no autorizacion 401 no autorizado 
      if(!isset($datos['token'])){ // si no existe token
        return $_respuestas->error_401();
      }else{        //si existe el token
        // necesitamos dos funciones que vamos a crear buscarToken()
        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();
        // var_dump($arrayToken);
        if($arrayToken){
              // validemos que los datos que nos envian contienen los campos requeridos para  un insert
              // en la tabla pacientes dni , nombre y correo son campos impresindibles para esta tabla. 
              if(  !isset($datos['pacienteid']) ){
                return $_respuestas->error_400();   //Datos enviados incompletos o con formato incorrecto     
              }else{
                  //en este punto o devuevuelve un 1 si ejecuto el delte o devuevelve un 0 s no pudo borrar.
                  $this->PacienteId   = $datos['pacienteid'];        
                  $numRowsAfected = $this->eliminarPaciente();
          
                  if( $numRowsAfected >= 1 ){
                      $respuesta = $_respuestas->response;
                      $respuesta["result"]= array( 
                                                  "Campos_pacientes_afectados" => $numRowsAfected
                                                  );
                      return $respuesta;                            
                  }else{
                      // si $numRowsAfected = 0 ,  no se hizo eliminacion por que ese id no existe.
                      $real = false;
                      if(!$real){
                        return $_respuestas->error_500("Error interno del Servidor , dicho id en la tabla $this->table no existe!!");
          
                      }else{
                        return $_respuestas->error_204("todos los datos enviados ya existen en la BBDD asi q fijate que tipeas!!!");
                        
                      }
                  }
              }
        }else{
          return $_respuestas->error_401("El token que se envio es invalido o ha caducado");
        }
      }

      //....................................................
  }//delete
  //------------------------------------------------------
  private function eliminarPaciente(){
    $query ="DELETE FROM $this->table  WHERE PacienteId = '$this->PacienteId'";

    $resp = parent::nonQuery($query , $this->table);
    if($resp>=1){ //nonQuery devuelve el num de filas afectadas.
      return $resp;
    }else{
      return 0; // no se deleteo ninguna fila
    } 
  }//elminarPaciente().
  //------------------------------------------------------
  function resetarAuto_increment(){
      // este codigo hace mediante una sola consulta al servidor la tarea de buscar el max id de la tabla y la guarda para concatenar el dato con el alter table auto_increment

      $query = "SELECT MAX(PacienteId) + 1 INTO @max_id FROM $this->table ;
      SET @query = CONCAT('ALTER TABLE pacientes AUTO_INCREMENT = ', @max_id);
      PREPARE stmt FROM @query;
      EXECUTE stmt;
      DEALLOCATE PREPARE stmt;
      SET @max_id = NULL;";

      return $query;
  }//resetarAuto_increment()
  //------------------------------------------------------
   private function buscarToken(){
    $query = "SELECT tokenid , usuarioid , estado FROM usuarios_token WHERE token = '$this->token' AND  estado = 'Activo'";
    // echo "/n en la linea: ".__LINE__. " " .$query . "\n";
    $resp = parent::obtenerDatos($query);
    if($resp){
      return $resp;
    }else{
      return 0;
    }
  }//buscarToken()
  //------------------------------------------------------
  private function actualizarToken($tokenId){
    $date = date("Y m d H:i");
    $query = "UPDATE usuarios_token SET Fecha = '$date'  WHERE TokenId = '$tokenId'";
    $resp = parent::nonQuery($query);  
    if($resp>=1){ 
      return $resp;
    }else{
      //devolvio 0
      return 0;
    }
  }  


}