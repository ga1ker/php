function getUsuarios(){
    $.ajax({
        url: "../php/getUsuarios.php",
        type: "GET",
        contentType: 'application/json', 
        success: function(response){
            console.log("función hecha", response)
        },
        error: function(e){
            console.error(e);
        }
    })
    
}

function setUsuario(){
    $.ajax({
        url: "../php/setUsuario.php",
        type: "POST",
        contentType: 'application/json',
        data: JSON.stringify({
            "nombre": "Gadiel",
            "apellidos": "Alcazaer bernasl"
        }),
        success: function(response){
            console.log("función hecha", response)
            getUsuarios();
        },
        error: function(e){
            console.error(e);
        }
    })
    
}


getUsuarios()