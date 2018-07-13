var tabla;
// Funcion que se ejecuta al inicio
function init() {
    mostrarForm(false);
    listar();
}

// funcion mostrar formulario
function mostrarForm(flag) {
//    limpiarForm();
    if (flag) {// si es true
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnAgregar").hide();

    } else { // si es false
        $("#listadoRegistros").show();
        $("#formularioRegistros").hide();
        $("#btnAgregar").hide();
    }
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
            url: "../ajax/permiso.ajax.php?op=listar",
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

init();




