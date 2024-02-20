<?php
require_once "./clases/fecha.class.php";


// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir los métodos  GET, POST, PUT , DELETE , OPTIONS
// header("Access-Control-Allow-Methods: GET, POST, PUT , DELETE , OPTIONS");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");


// Permitir los encabezados y la credencialidad
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// header("Access-Control-Allow-Credentials: true");



class conexion{
    private $server ;  
    private $user ;  
    private $password ;  
    private $database ;  
    private $port ;  
    
    protected $fecha;

    private $conexion;

    function __construct(){
        $listadatos =  $this->datosConexion();
        foreach($listadatos as $key => $value ){
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];

            $this->fecha = new fecha;

            $this->conexion = new mysqli($this->server , $this->user , $this->password , $this->database , $this->port );
            if( $this->conexion->connect_errno ){
                    echo "algo esta mal con la conexion";
                    die() ; //para que no siga ejecutando nada mas.
            }else{
                // echo "se a conectado correctamente a la BBDD";
                // https://www.youtube.com/watch?v=qGEWyjVWVj8&list=PLIbWwxXce3VpvBT_O977da8XECEp-JTJt&index=3
                // https://github.com/waleman/curso_apirest_YT
            }   
            


        }
    }
    //-------------------------------------------------------------------------------
    public function getFecha(){
        return $this->fecha;
    }
    //-------------------------------------------------------------------------------
    private function datosConexion(){
        $direccion = dirname(__FILE__) ;
        $jsonData =  file_get_contents($direccion . "/" . "config"); //file_get_contentes es una funcion q devuelve todo lo hay dentro de un archivo.
        $convertirEnArrAsosiativo = true ;
        return json_decode($jsonData , $convertirEnArrAsosiativo );

    } 
    //-------------------------------------------------------------------------------
    
    // private function convertirUTF8($array){
    //     array_walk_recursive($array,function(&$item,$key){
    //         if(!mb_detect_encoding($item,'utf-8',true)){
    //             $item = utf8_encode($item);
    //         }
    //     });
    //     return $array;
    // }

    //-------------------------------------------------------------------------------
    private function convertirUTF8($array) {
            for($i = 0 ; $i < sizeof($array) ; $i++ ){
                $c_elemento = $array[$i];
                    $array[$i] = $c_elemento;
                unset($c_elemento);
            }
        return $array;
    }
   //-------------------------------------------------------------------------------
   public function ejecutarMetodoPrivado($array){
        $this -> convertirUTF8($array);
    }
    //-------------------------------------------------------------------------------
    public function obtenerDatos($query){
        $results =$this ->conexion->query($query);
        $resultArray = array();
        // foreach( $results as $key){
        //     $resultArray[] = $key; //todo esto es como un array push.
        // }
        if ($results) {
            while ($row = $results->fetch_assoc()) {
                $resultArray[] = $row;
            }
            // Liberar memoria del resultado
            $results->free();
        } else {
            // Manejar el error de la consulta
            echo "Error en la consulta: " . $this->conexion->error;
        }        
        $rertornable = $this -> convertirUTF8($resultArray);

        return $rertornable;
    }
    //-------------------------------------------------------------------------------
    //nos devolvieria las filas afectadas osea 1 o n filas que iva a meter la sentencia sql. 
    public function nonQuery($sqlstr , $table = 'pacientes' ){
            $this->setAuto_increment($sqlstr , $table );
            $results = $this->conexion->query($sqlstr);
            return $this->conexion->affected_rows;
    }
    //-------------------------------------------------------------------------------
    //metodo para Insertar , este metodo nos devolveria el id del ultimo registro que acaba de meter:
    public function nonQueryId($sqlstr , $table = 'pacientes' ){
        $this->setAuto_increment($sqlstr , $table );
        $results = $this->conexion->query($sqlstr);
        $filas =$this->conexion->affected_rows; 
        if($filas >= 1){
             return $this->conexion->insert_id;   
        }else{
            return 0;
        }
    }
    //-------------------------------------------------------------------------------
    public function setAuto_increment($sqlstr , $table ){
        
        if( strpos( strtoupper($sqlstr) , 'INSERT' ) !== false ){
            // si $sql trae 'INSERT'
            $sql_numFilas =  "SELECT count(*) from $table;";
            $numFilas     = $this->consulta($sql_numFilas)[0]['count(*)'];
            $numfilasAfectadas = $this->nonQuery("ALTER TABLE $table AUTO_INCREMENT = $numFilas;");
        }//if(.....)
        // caso que no trae INSERT no hace nada.
    }
    //-------------------------------------------------------------------------------
    public function consulta($sqlstr){
        $result = $this->conexion->query($sqlstr);
        $arrAsociativo = $result->fetch_all(MYSQLI_ASSOC);
        return $arrAsociativo;
        // var_dump( json_encode($result , true) );
    }
    //-------------------------------------------------------------------------------
    public function consultaAlterAuto_increment($sqlstr){
        echo $sqlstr;
        $result = $this->conexion->query($sqlstr);
        // $arrAsociativo = $result->fetch_all(MYSQLI_ASSOC);
        return $result;
        // var_dump( json_encode($result , true) );
    }
    //-------------------------------------------------------------------------------
     protected function encriptar( $string  ){
        return md5($string); // metodo para encriptar las contrañas
    }
    
    /* 
    =================================================================================
                    De otro yotuber para autenticacion
                https://www.youtube.com/watch?v=9D8JkTIMlkQ
    ================================================================================== */

    static public function jwt($id , $email){
        /*=========================================
            recibe el id y el email del usuario
        =========================================*/
        

    }




}