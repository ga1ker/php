function getUsuarios(){
    const contenedorTabla = document.getElementById("bodyTable");
    $.ajax({
        url: "../php/getCustomer",
        type: "GET",
        contentType: 'application/json', 
        success: function(response){
            usuarios = response.usuarios;
            contenedorTabla.innerHTML = ''; // Limpiar tabla antes de agregar filas
            
            usuarios.forEach(usuario => {
                contenedorTabla.innerHTML += `
                    <tr>
                        <td>${usuario.id}</td>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.apellidos}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal-${usuario.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deleteCustomer(${usuario.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal-${usuario.id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar a usuario ${usuario.id}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-grid p-2">
                                    <label>Nombre</label>
                                    <input type="text" id="nombreAct-${usuario.id}" value="${usuario.nombre}"/>
                                    <label>Apellidos</label>
                                    <input type="text" id="apellidosAct-${usuario.id}" value="${usuario.apellidos}"/>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" onclick="updateUsuario(${usuario.id})">Actualizar</button>
                            </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        },
        error: function(e){
            console.error(e);
        }
    })
    
}

function addUsuario(){
    const nombre = document.getElementById("nombre").value
    const apellidos = document.getElementById("apellidos").value
    $.ajax({
        url: "../php/setCustomer",
        type: "POST",
        contentType: 'application/json',
        data: JSON.stringify({
            "nombre": nombre,
            "apellidos": apellidos
        }),
        success: function(response){
            document.getElementById("nombre").value = "";
            document.getElementById("apellidos").value = "";
            getUsuarios();
        },
        error: function(e){
            console.error(e);
        }
    })
}

function updateUsuario (id){
    const nombreAct = document.getElementById("nombreAct-" + id).value
    const apellidosAct = document.getElementById("apellidosAct-" + id).value

    $.ajax({
        url: "../php/updateCustomer",
        type: "PUT",
        contentType: "application/json",
        data: JSON.stringify({
            nombre: nombreAct,
            apellidos: apellidosAct,
            id: id
        }),
        success: function(response){
            const modal = document.getElementById("exampleModal-" + id);
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
            getUsuarios();
        },
        error: function(e){
            console.error(e);
        }
    })
}

function deleteCustomer (id){
    $.ajax({
        url: "../php/deleteCustomer",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            id: id
        }),
        success: function(response){
            getUsuarios();
        },
        error: function(e){
            console.error(e);
        }
    })
}


getUsuarios()