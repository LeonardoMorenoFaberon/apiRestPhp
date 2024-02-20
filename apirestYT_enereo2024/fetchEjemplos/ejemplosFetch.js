//metodo get:
fetch("http://localhost/apirestYT_enereo2024/auth")
.then((response) => response.json())
.then((json) => console.log(json));



// ========================================================================
//metodo post:
fetch("http://localhost/apirestYT_enereo2024/auth", {
  method: "POST",
  body: JSON.stringify({  
    usuario : "usuario6@gmail.com",
    password:"123456",  
  }),
  headers: {
    "Content-type": "application/json; charset=UTF-8",
    "Access-Control-Allow-Headers": "Content-Type, Authorization",
    // "Access-Control-Allow-Methods": "POST, GET, PUT , DELETE , OPTIONS",
    // "Access-Control-Allow-Origin": "*",
  },
})
.then((response) => response.json())
.then((json) => console.log(json));


// ========================================================================
//metodo post:
fetch("http://localhost/apirestYT_enereo2024/pacientes.php", {
  method: "POST",
  body: JSON.stringify({  
    nombre : "Leonardo Moreno Faberón" ,  
    dni : "10234568" ,  
    correo : "lemofa777@gmail.com" ,  
    token  : "6f707925a30436e82a6b601b5f8ec10e" ,
    direccion : "jr castilla 520" ,  
    codigopostal : "180060" ,  
    telefono : "966445577" ,  
    genero : "M" ,  
    fechanacimiento : "2024-08-14" ,  
  }),
  headers: {
    "Content-type": "application/json; charset=UTF-8",
    "Access-Control-Allow-Headers": "Content-Type, Authorization",
    // "Access-Control-Allow-Methods": "POST, GET, PUT , DELETE , OPTIONS",
    // "Access-Control-Allow-Origin": "*",
  },
})
.then((response) => response.json())
.then((json) => console.log(json));



// ========================================================================
//metodo PUT:
fetch("http://localhost/apirestYT_enereo2024/pacientes.php", {
  method: "PUT",
  body: JSON.stringify({  
    pacienteid : "27",     // necesita el pacienteid  asi esta construida la clase que lo recibe. 
    nombre : "Leonardo Moreno Faberón" ,  
    dni : "10234568" ,  
    correo : "lemofa777@gmail.com" ,  
    token  : "6f707925a30436e82a6b601b5f8ec10e" ,
    direccion : "jr bolognesi 150 3er piso xxx" ,  
    codigopostal : "180060" ,  
    telefono : "966445577" ,  
    genero : "M" ,  
    fechanacimiento : "2024-08-14" ,  
  }),
  headers: {
    "Content-type": "application/json; charset=UTF-8",
    // "Access-Control-Allow-Headers": "Content-Type, Authorization",
    // "Access-Control-Allow-Methods": "POST, GET, PUT , DELETE , OPTIONS",
    // "Access-Control-Allow-Origin": "*",
  },
})
.then((response) => response.json())
.then((json) => console.log(json));

// ========================================================================
//metodo post:
fetch("http://localhost/apirestYT_enereo2024/pacientes.php", {
  method: "DELETE",
  body: JSON.stringify({
    "pacienteid": "27",
    "token":"1c3d9c5978240e02fb6345850b291f6f"
  }),
  headers: {
    "Content-type": "application/json; charset=UTF-8",
    "Access-Control-Allow-Headers": "Content-Type, Authorization",
    // "Access-Control-Allow-Methods": "POST, GET, PUT , DELETE , OPTIONS",
    // "Access-Control-Allow-Origin": "*",
  },
})
.then((response) => response.json())
.then((json) => console.log(json));

// ========================================================================

fetch("http://localhost/apirestYT_enereo2024/auth.php" ,{
    method: "POST",
    body: JSON.stringify({  
    usuario : "usuario4@gmail.com" ,  
    password : "123456" ,    
}),
headers: {
  "Content-type": "application/json; charset=UTF-8",
},
})
.then((response) => response.json())
.then((json) => console.log(json));

// ========================================================================
