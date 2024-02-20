<?php

class fecha{


    private $fechaActual;

    function __construct(){
       $this->fechaActual = new DateTime();
        /*Sólo para ver el objeto*/
        // var_dump($fechaActual);
        // /*Un ejemplo de salida de fecha con formato*/ 
        // echo "Fecha/Hora: ".$fechaActual->format('Y-m-d H:i:sP') . PHP_EOL;

        $this->fechaActual->setTimezone(new Datetimezone('America/Lima'));
        // $fechaActual->setTimeZone(new DateTimeZone('America/Lima'));
        
    }

    function getFechaActual(){
        return $this->fechaActual;
        // return $this->fechaActual->format('Y-m-d H:i:sP') . PHP_EOL;
    }
    
    function getFechaActualInString(){
        return $this->fechaActual->format("Y-m-d H:i:s");
    }

// $fechaActual=new DateTime();
// /*Sólo para ver el objeto*/
// // var_dump($fechaActual);
// // /*Un ejemplo de salida de fecha con formato*/ 
// // echo "Fecha/Hora: ".$fechaActual->format('Y-m-d H:i:sP') . PHP_EOL;

// // $fechaActual->setTimezone(new Datetimezone('America/Lima'));
// $fechaActual->setTimeZone(new DateTimeZone('America/Lima'));

// var_dump($fechaActual) ;
// var_dump($fechaActual->format('Y-m-d H:i:sP') . PHP_EOL);
}