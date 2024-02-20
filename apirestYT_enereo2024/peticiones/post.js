    // Crear el objeto JSON con los datos que deseas enviar
    var datos = {
        DNI : "902135",
        Nombre: "Ejemplo"
      };
      
      // Convertir el objeto JSON a una cadena
      var jsonDatos = JSON.stringify(datos);
      
      // URL del servidor al que enviar la solicitud POST
      var url = "http://localhost/apirestYT_enereo2024/auth";
      
      
      // Configurar las opciones de la solicitud
      var opciones = {
        method  : 'POST', // Método de la solicitud
        headers : { 'Content-Type': 'application/json' }, // Tipo de contenido
        body    : JSON.stringify(datos) // Convertir el objeto JSON a una cadena
      };
      
      // Realizar la solicitud POST utilizando fetch
      fetch(url, opciones) 
        .then((response) => response.json())
        .then((json) => console.log(json))
        
        .catch(function(error) {
          // Acción a realizar en caso de error de red
          console.error("Error de red al intentar realizar la solicitud:", error);
        });
      

//         tarapoto mancora cuzco y arequipa

// 30  min 
// { "direccion oficina" : {
// 	"direccion" :"javier prado oeste 757 magdalena del mar"
// 	"edificio" : "securistas peru" , 
// 	"piso" : "20 20-03"
// 	}
// }

// v8041h


