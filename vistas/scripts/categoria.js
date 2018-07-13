var tabla;
// Funcion que se ejecuta al inicio
function init() {
    mostrarForm(false);
    listar();
    $("#formulario").on("submit", function (e) {
        guardarEditar(e);
    })
}
//Funcion limpiar
function limpiarForm() {
    $("#formulario")[0].reset();
    /*
    $("#idCategoria").val("");
    $("#nomCategoria").val("");
    $("#desCategoria").val("");
    */
}
// funcion mostrar formulario
function mostrarForm(flag) {
    limpiarForm();
    if (flag) {// si es true
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#btnGuardar").prop("disabled", false);
    } else { // si es false
        $("#listadoRegistros").show();
        $("#formularioRegistros").hide();
    }
}
//Funcion CancelarForm
function cancelarForm() {
    limpiarForm();
    mostrarForm(false);
}
// Funcion listar
function listar() {
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
            url: "../ajax/categoria.ajax.php?op=listar",
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
        url: "../ajax/categoria.ajax.php?op=guardar_editar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            swal(datos.trim(), 'Revise el cambio', 'success').then((e) => {
                if (e.value) {
                    mostrarForm(false);
                    tabla.ajax.reload();
                }
            });

        }
    });
    limpiarForm();
}
// funcion mostrar Datos
function mostrarDatos(idcat) {
    $.post("../ajax/categoria.ajax.php?op=mostrar", {idCategoria: idcat}, function (data, status) {
        dataJson = JSON.parse(data); //dataJson se convirtio tipo json de data tipo cadena
        console.log("data", dataJson);
        mostrarForm(true);
        //dataJson tiene los nombres de las columnas tabla 'categoria'
        //se obtiene los valores y se les asigna a las respectivas id del formulario
        $("#nomCategoria").val(dataJson.nombre);
        $("#desCategoria").val(dataJson.descripcion);
        $("#idCategoria").val(dataJson.idcategoria)
    })
}
// Funcion para desactivar registros
function desactivar(idcat) {
    swal({
        title: "¿Esta seguro de desactivar la categoria?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, desactivar categoria",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/categoria.ajax.php?op=desactivar",
                    {idCategoria: idcat},
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
function activar(idcat) {
    swal({
        title: "¿Esta seguro de activar la categoria?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, activar categoria",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
//        console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/categoria.ajax.php?op=activar",
                    {idCategoria: idcat},
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

