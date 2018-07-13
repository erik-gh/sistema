var tabla;

// Funcion que se ejecuta al inicio
function init() {
    listar();
    //Cargamos los items al select Cliente
    $.post("../ajax/venta.ajax.php?op=select_cliente", function (r) {
        $("#id_cliente").html(r);
        $('#id_cliente').selectpicker('refresh');
    });
}

// Funcion listar
function listar() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idcliente = $("#id_cliente").val();

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
            url: "../ajax/consultas.ajax.php?op=compras_fecha_cliente",
            data: {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, id_cliente:idcliente},
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