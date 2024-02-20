<?php
require_once "./clases/respuestas.class.php";
require_once "./clases/pacientes.class.php";




$_respuestas =  new respuestas;
$_pacientes =  new pacientes;
//o bien recibe un parametro o bien recibe otro por el else if en cada REQUEST_METHOD :
// recibe ?page=1    y asi cualquier numero
// recibe ?id=2      y asi cualquier numero.

if($_SERVER['REQUEST_METHOD'] == "GET"){
    // echo "hola GET";
    if(isset($_GET['page'] )){
        $pagina = $_GET['page'] ;
        $listaPacientes = $_pacientes->listaPacientes( $pagina );
        header("Content-Type: application/json");
        echo json_encode($listaPacientes);
        http_response_code(200);

    }else if(isset($_GET['id'])){
        
        $pacienteId = $_GET['id'];
        $datosPaciente = $_pacientes->obtenerPaciente( $pacienteId );
        header("Content-Type : application/json");
        echo json_encode($datosPaciente) ; 
        http_response_code(200);
    }
// ..................................................................

}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados 
    $postBody =  file_get_contents("php://input");
    //enviamos los datos al manejador:
    $datosArray = $_pacientes->post($postBody);

          //devolvemos una respuesta :
        header('content-type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"] ;
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode( $datosArray );

// ..................................................................
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){

    $postBody =  file_get_contents("php://input");
    //enviamos los datos al manejador:
    $datosArray = $_pacientes->put($postBody);
     //devolvemos una respuesta :
     header('content-type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"] ;
         http_response_code($responseCode);
     }else{
         http_response_code(203);
     }
     echo json_encode( $datosArray );

// ..................................................................
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    
    //para obtener todos los headers que han sido enviados para aca.
    $headers =  getallheaders();
    // print_r($headers);
    if($headers['token'] && $headers['pacienteid']){
        //Estamos recibiendo datos enviados por el header
            $send = [
                    "token" => $headers['token'] , 
                    "pacienteid" => $headers['pacienteid']
                ] ;
        $postBody = json_encode($send);        
    }else{
        // obtenga los datos del body
        $postBody =  file_get_contents("php://input");
    }
    
    // enviamos los datos al manejador:
    $datosArray = $_pacientes->delete($postBody);
     //devolvemos una respuesta :
     header('content-type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"] ;
         http_response_code($responseCode);
     }else{
         http_response_code(203);
     }
     echo json_encode( $datosArray );

    // ..................................................................
}else{
    header('content-type: application/json');
    $datosArray =  $_respuestas->error_405();
    echo json_encode($datosArray);
}


?>