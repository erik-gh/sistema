var tabla;
// Funcion que se ejecuta al inicio
function init() {
    mostrarForm(false);
    listar();
    $("#formulario").on("submit", function (e) {
        guardarEditar(e);
    })
    $("#imgMuestra").hide();
    // Mostramos los permisos
    $.post("../ajax/usuario.ajax.php?op=permisos&id=", function (r) {
        $("#permisos").html(r);
    });
}

//Funcion limpiar
function limpiarForm() {
    $("#idUsuario").val("");
    $("#nomUsuario").val("");
    $("#tpdUsuario").val("");
    $("#nmdUsuario").val("");
    $("#dirUsuario").val("");
    $("#tlfUsuario").val("");
    $("#emlUsuario").val("");
    $("#crgUsuario").val("");
    $("#lgnUsuario").val("");
    $("#clvUsuario").val("");
    $("#imgUsuario").attr("src", "");
    $("#imgActUsu").val("");
}
// Funcion mostrar formulario
function mostrarForm(flag) {
    limpiarForm();
    if (flag) {// si es true
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnAgregar").hide();
    } else { // si es false
        $("#listadoRegistros").show();
        $("#formularioRegistros").hide();
        $("#btnAgregar").show();
    }
}
//Funcion CancelarForm
function cancelarForm() {
    limpiarForm();
    mostrarForm(false);
}
// Funcion listar
function listar() {
    //alert("Hola");
    tabla = $("#tblListado").dataTable({
        "aProcessing": true, // Activamos el procesamiento del dataTables
        "aServerSide": true, // Paginacion y filtrado realizados por el servidor
        dom: "Bfrtip", //Definimos los elementos del control de la tabla
        buttons: [//buttons
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: "../ajax/usuario.ajax.php?op=listar",
            type: 'get',
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion
        "order": [[0, "asc"]] // Ordenar (columna, orden) , desc, asc
    }).DataTable();
}
//Funcion Guardar y Editar
function guardarEditar(e) {
    e.preventDefault(); //No se activará la accion predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    // el FormData toma los datos de los name="" del formulario
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/usuario.ajax.php?op=guardar_editar",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            swal(resp.trim(), 'Revise el cambio', 'success').then((e) => {
                if (e.value) {
                    mostrarForm(false);
                    tabla.ajax.reload();
                }
            });
        }
    });
    limpiarForm();
}
// Funcion mostrar Datos
function mostrarDatos(idUsu) {
    $.post("../ajax/usuario.ajax.php?op=mostrar", {idUsuario: idUsu}, function (data, status) {
        dataJson = JSON.parse(data); //dataJson se convirtio tipo json de data tipo cadena
        console.log("data", dataJson);
        mostrarForm(true);
        //dataJson tiene los nombres de las columnas tabla 'categoria'
        //se obtiene los valores y se les asigna a las respectivas id del formulario
        $("#idUsuario").val(dataJson.idusuario);
        $("#nomUsuario").val(dataJson.nombre);
        $("#tpdUsuario").val(dataJson.tipo_documento);
        $('#tpdUsuario').selectpicker('refresh'); // Se refresca despues de agregar o eliminar elementos
        $("#nmdUsuario").val(dataJson.num_documento);
        $("#dirUsuario").val(dataJson.direccion);
        $("#tlfUsuario").val(dataJson.telefono);
        $("#emlUsuario").val(dataJson.email);
        $("#crgUsuario").val(dataJson.cargo);
        $("#lgnUsuario").val(dataJson.login);
        $("#clvUsuario").val(dataJson.clave);
        $("#imgMuestra").show();// mostrar la etiqueta input con el id imgMuestra
        $("#imgMuestra").attr("src", "../files/usuarios/" + dataJson.imagen);// mostrar la etiqueta input con el id imgMuestra
    });
    $.post("../ajax/usuario.ajax.php?op=permisos&id=" + idUsu, function (r) {
        $("#permisos").html(r);
    });
}
// Funcion para desactivar registros
function desactivar(idUsu) {
    swal({
        title: "¿Esta seguro de desactivar el usuario?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, desactivar usuario",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/usuario.ajax.php?op=desactivar",
                    {idUsuario: idUsu},
                    function (datos) {
                        swal(datos.trim(), 'Revise el cambio', 'success').then((e) => {
                            if (e.value) {
                                tabla.ajax.reload();
                            }
                        });
                    }
            );
        }
    });
}
//Funcion activar registros
function activar(idUsu) {
    swal({
        title: "¿Esta seguro de activar el usuario?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, activar usuario",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/usuario.ajax.php?op=activar",
                    {idUsuario: idUsu},
                    function (datos) {
                        swal(datos.trim(), 'Revise el cambio', 'success').then((e) => {
                            if (e.value) {
                                tabla.ajax.reload();
                            }
                        });
                    }
            );
        }
    });
}

init();




