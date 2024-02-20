<?php
class respuestas{

    public $response = [
        "status"=> "ok",
        "result"=> array()
    ];

    
    public function error_200($menssage = "Datos incorrectos"){
        $this->response['status']="error";
        $this->response['result']=array(
            "error_id"=>"200",
            "error_msg"=>$menssage
        );
        return $this->response;
    }
    public function error_204($menssage = "Solicitud Procesada con Exito pero no realizo cambios pues los datos proporcionados ya eran identicos a los almacenados."){
        $this->response['status']="error";
        $this->response['result']=array(
            "error_id"=>"204",
            "error_msg"=>$menssage
        );
        return $this->response;
    }

    public function error_400($menssage = "Datos enviados incompletos o con formato incorrecto"){
        $this->response['status']="error";
        $this->response['result']=array(
            "error_id"=>"400",
            "error_msg"=>$menssage
        );
        return $this->response;
    }

    public function error_401($menssage = "No autorizado"){
        $this->response['status']="error";
        $this->response['result']=array(
            "error_id"=>"401",
            "error_msg"=>$menssage
        );
        return $this->response;
    }

    public function error_405(){
        $this->response['status']="error";
        $this->response['result']=array(
            "error_id"=>"405",
            "error_msg"=>"Metodo no permitido"
        );
        return $this->response;
    }
    public function error_500($menssage = "Error Interno del Servidor!!"){
        $this->response['status']="error";
        $this->response['result']=array(
            "error_id"=>"500",
            "error_msg"=>$menssage
        );
        return $this->response;
    }


    //si quieres agregar un error 
    

}