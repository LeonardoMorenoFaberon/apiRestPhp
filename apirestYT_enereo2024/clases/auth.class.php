<?php
require_once './clases/conexion.php';
require_once './clases/respuestas.class.php';

// https://www.youtube.com/watch?v=aT8_upy-AtU&list=PLIbWwxXce3VpvBT_O977da8XECEp-JTJt&index=6 
// en el min  9:41


class auth extends conexion{
    //al heredar de una clase pudeo usar todos sus metodos menos los metodos privados.
    public function login( $json ){
        $_respuestas = new respuestas;
        $datos = json_decode( $json , true );
        if(!isset($datos['usuario']) || !isset($datos['password'])){
            //error en los campos.
            return  $_respuestas->error_400() ;  
        }else{  
            //Todo esta bien  
            $usuario  = $datos['usuario' ]  ; 
            $password = $datos['password']  ; 
            $password =  parent::encriptar($password); 
            $datos = $this->obtenerDatosUsuario($usuario); 
            if($datos){ 
                // el usuario si existe entonces :
                // verificar si la contraseña es igual
                // return ["password_recuperado" => $datos[0]['Password']  , "password_introducid" => $password ];
                // return $datos;

                if( $password == $datos[0]['Password'] ){
                    if($datos[0]['Estado'] == 'Activo'){
                        //crear token
                        $verificar = $this->insertarToken($datos[0]['UsuarioId']);    
                        if($verificar){
                            //si se ha guardado:
                            $result = $_respuestas->response;
                                // "status"=> "ok",
                                // "result"=> array()
                            $result['status'] = "ok";
                            $result['result'] =  array( "token" => $verificar );
                            return $result;

                        }else{
                            //error al guardar:
                            return $_respuestas->error_500("Error interno , no hemos podido Guardarlo!");
                        }
                    }else{
                        // el usuario esta inactivo:
                        return $_respuestas->error_200("El usuario no esta activo o es lo mismo password es invalido !");

                    }
                }else{
                    // la contraseña no es igual
                }
            }else{
                //no existe el usuario:
                return $_respuestas->error_200("el usuario $usuario no existe");
            }
            // return  ["resultado"=>"los datos los enviaste correctamente si hubiera error no sera por datos"  ] ;

        }
    }

    // -----------------------------------------------------------------------------
    private function obtenerDatosUsuario($correo){
        // Obs : el password que el usuario tipea tiene que llegar aca ya  encriptado ( en la BBDD esta encriptado ).  
        $query =  "SELECT  UsuarioId , Password ,Estado FROM usuarios WHERE usuario = '$correo';" ;
        //como auth hereda de conexion entonces usemos sus metodos publicos:
        $datos = parent::obtenerDatos( $query );
        
        if(  isset(  $datos[0]['UsuarioId']  )  ){
                return $datos;
        }else{
                return 0     ;
        }
    }
    // -----------------------------------------------------------------------------
   public function insertarToken($usuario_id){
    $val = true;    
    $token = bin2hex(openssl_random_pseudo_bytes( 16 , $val )); //devuelve los numero del 1al 90 y letras de A  a la F
    // $date = date("Y-m-d H:i");
    // como instancie la clase fecha en la clase conexion y auth.class heredad de conexion  en auth.class puedo llamar a los metodos de  fecha con parent::
    $date = parent::getFecha()->getFechaActualInString();
    $estado = "Activo";
    $query = "INSERT INTO usuarios_token (UsuarioId , Token , Estado , Fecha) VALUES( '$usuario_id' , '$token' , '$estado' , '$date' )";
    $verifica = parent::nonQuery($query , 'usuarios_token');
    if($verifica){
        // ojo el token se crea en esta funcion sin la bbdd pero solo si se inserto el token en la BBDD devolvemos el token de otro modo  seria un problema
        return $token;
    }else{
        return false ;
    }

   }

   public function darDeBajaToken($usuario_id){
        $query =  "UPDATE usuarios_token SET estado =  'inactivo' WHERE fecha < DATE_SUB( NOW(), INTERVAL 60 MINUTE );";
        $numFilasAfectadas = parent::nonQuery($query , 'usuarios_token');
        if($numFilasAfectadas>=1){
            return $numFilasAfectadas;
        }else{
            return 0; // a nunguna fila se le cambio el estado del  token  a inactivo ; 
        }

   }
}

?>