var tabla;

// Funcion que se ejecuta al inicio
function init() {
    listar();
    $("#fecha_inicio").change(listar);
    $("#fecha_fin").change(listar);
}

// Funcion listar
function listar() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

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
            url: "../ajax/consultas.ajax.php?op=compras_fecha",
            data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
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

