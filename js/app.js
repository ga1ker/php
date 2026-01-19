function getUsuarios(){
    $.ajax({
        url: "../php/getUsuarios.php",
        type: "GET",
        contentType: "application/json",
        success: function(response){
            console.log("función hecha", response)
        },
        error: function(e){
            console.error(e);
        }
    })
    
}


getUsuarios()