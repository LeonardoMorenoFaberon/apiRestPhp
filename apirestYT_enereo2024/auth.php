<?php
    require_once "./clases/auth.class.php";
    // require_once "./clases/respuestas.class.php";


    $_auth = new auth;
    $_respuestas = new respuestas;
    
    
    // $postBody = forzarPostJson();

    // function forzarPostJson(){
    //     // $direccion = dirname("./jsones/json1") ;
    //     $jsonData =  file_get_contents( "./jsones/json1" ); //file_get_contentes es una funcion q devuelve todo lo hay dentro de un archivo.
    //     $convertirEnArrAsosiativo = true ;
    //     return json_decode($jsonData , $convertirEnArrAsosiativo );

    // } 




    if($_SERVER['REQUEST_METHOD']=="POST"){
        //recibir datos:
        $postBody =  file_get_contents("php://input") ; 
        //enviamos estos datos al manejador :
        $datosArray =  $_auth->login(  $postBody );

        //devolvemos una respuesta :
        header('content-type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"] ;
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode( $datosArray );

    }else{
        header('content-type: application/json');
        $datosArray =  $_respuestas->error_405();
        echo json_encode($datosArray);
        

    }

?>
