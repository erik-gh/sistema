var tabla;
// Funcion que se ejecuta al inicio
function init() {
    mostrarForm(false);
    listar();
    $("#formulario").on("submit", function (e) {
        guardarEditar(e);
    })
    //Cargamos los items al select categoria
    $.post("../ajax/articulo.ajax.php?op=select_categoria", function (r) {
        $("#idCategoria").html(r);
        $("#idCategoria").selectpicker('refresh');
    });
    $("#imgMuestra").hide();
}

//Funcion limpiar
function limpiarForm() {
    $("#idArticulo").val("");
    $("#codArticulo").val("");
    $("#idCategoria").val('').selectpicker('refresh');
    $("#nomArticulo").val("");
    $("#desArticulo").val("");
    $("#stkArticulo").val("");
    $("#imgMuestra").attr("src", "");
    $("#imgActual").val("");
    $("#print").hide();
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
            url: "../ajax/articulo.ajax.php?op=listar",
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
        url: "../ajax/articulo.ajax.php?op=guardar_editar",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
//            bootbox.alert(datos);
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
function mostrarDatos(idArt) {
    $.post("../ajax/articulo.ajax.php?op=mostrar", {idArticulo: idArt}, function (data, status) {
        dataJson = JSON.parse(data); //dataJson se convirtio tipo json de data tipo cadena
        console.log("data", dataJson);
        mostrarForm(true);
        //dataJson tiene los nombres de las columnas tabla 'categoria'
        //se obtiene los valores y se les asigna a las respectivas id del formulario
        $("#idArticulo").val(dataJson.idarticulo);
        $("#idCategoria").val(dataJson.idcategoria);
        $('#idCategoria').selectpicker('refresh'); // Se refresca despues de agregar o eliminar elementos
        $("#codArticulo").val(dataJson.codigo);
        $("#nomArticulo").val(dataJson.nombre);
        $("#stkArticulo").val(dataJson.stock);
        $("#desArticulo").val(dataJson.descripcion);
        $("#imgMuestra").show();// mostrar la etiqueta input con el id imgMuestra
        $("#imgMuestra").attr("src", "../files/articulos/" + dataJson.imagen);// mostrar la etiqueta input con el id imgMuestra
        $("#imgActual").val(dataJson.imagen);
        generarbarcode();

    })
}
// Funcion para desactivar registros
function desactivar(idArt) {
    swal({
        title: "¿Esta seguro de desactivar el articulo?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, desactivar articulo",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/articulo.ajax.php?op=desactivar",
                    {idArticulo: idArt},
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
function activar(idArt) {
    swal({
        title: "¿Esta seguro de activar el artículo?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, activar artículo",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/articulo.ajax.php?op=activar",
                    {idArticulo: idArt},
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
//Funcion Generar Barcode(codigo de Barras)
function generarbarcode() {
    codigo = $("#codArticulo").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}
// Funcion para imprimir el Codigo de Barras
function imprimir() {
    $("#print").printArea();
}
init();

