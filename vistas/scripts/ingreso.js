var tabla;

// Funcion que se ejecuta al inicio
function init() {
    mostrarForm(false);
    listar();
    $("#formulario").on("submit", function (e) {
        guardarEditar(e);
    });
    //Cargamos los items al select proveedor
    $.post("../ajax/ingreso.ajax.php?op=select_proveedor", function (r) {
        $("#idProveedor").html(r);
        $('#idProveedor').selectpicker('refresh');
    });
}

//Funcion limpiar
function limpiarForm() {
    //esta codigo limpia todos los campos del formulario (form)
    $("#formulario")[0].reset();
    $("#impIngreso").val("0");
    $("#total_compra").val("");
    $(".filas").remove();
    $("#total").html("S/. 0.00");
    //Obtenemos la fecha actual
    var now = new Date();
    var dia = ("0" + now.getDate()).slice(-2);
    var mes = ("0" + (now.getMonth() + 1)).slice(-2);
    var hoy = now.getFullYear() + "-" + (mes) + "-" + (dia);
    $("#fhIngreso").val(hoy);
    /*
    $("#idIngreso").val("");
    $("#idProveedor").val('').selectpicker('refresh');
    $("#fhIngreso").val("");
    $("#tcIngreso").val("");
    $("#scIngreso").val("");
    $("#ncIngreso").val("");
    $("#impIngreso").val("");
    $("#impIngreso").val("");
    // $("#imgMuestra").attr("src", "");
    */
}

// Funcion mostrar formulario
function mostrarForm(flag) {
    limpiarForm();
    if (flag) {// si es true
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        // $("#btnGuardar").prop("disabled", false);
        $("#btnAgregar").hide();
        listarArticulos();
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
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
            url: "../ajax/ingreso.ajax.php?op=listar",
            type: 'get',
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginacion
        "order": [[0]] // Ordenar (columna, orden) , desc, asc
    }).DataTable();
}

// Funcion listar Articulos
function listarArticulos() {
    tabla = $("#tblArticulos").dataTable({
        "aProcessing": true, // Activamos el procesamiento del dataTables
        "aServerSide": true, // Paginacion y filtrado realizados por el servidor
        dom: "Bfrtip", //Definimos los elementos del control de la tabla
        buttons: [//buttons
        ],
        "ajax": {
            url: "../ajax/ingreso.ajax.php?op=listar_articulos",
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
    // $("#btnGuardar").prop("disabled", true);
    // El FormData toma los datos de los name="" del formulario
    var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url: "../ajax/ingreso.ajax.php?op=guardar_editar",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
            swal(resp.trim(),
                'Revise el cambio',
                'success'
            ).then(function (e) {
                if (e.value) {
                    mostrarForm(false);
                    listar();
                    // tabla.ajax.reload();
                }
            });
            /*
                        swal(resp.trim(),
                            'Revise el cambio',
                            'success'
                        ).then((e) => {
                            if (e.value) {
                                mostrarForm(false);
                                tabla.ajax.reload();
                            }
                        });
            */
        }
    });
    limpiarForm();
}

// Funcion mostrar Datos
function mostrarDatos(idIng) {
    $.post("../ajax/ingreso.ajax.php?op=mostrar", {idIngreso: idIng}, function (data, status) {
        dataJson = JSON.parse(data); //dataJson se convirtio tipo json de data tipo cadena
        // console.log("data", dataJson);
        mostrarForm(true);
        //dataJson tiene los nombres de las columnas tabla 'categoria'
        //se obtiene los valores y se les asigna a las respectivas id del formulario
        $("#idIngreso").val(dataJson.idingreso);
        $("#idProveedor").val(dataJson.idproveedor);
        $('#idProveedor').selectpicker('refresh'); // Se refresca despues de agregar o eliminar elementos
        $("#fhIngreso").val(dataJson.fecha);
        $("#tcIngreso").val(dataJson.tipo_comprobante);
        $('#tcIngreso').selectpicker('refresh'); // Se refresca despues de agregar o eliminar elementos
        $("#scIngreso").val(dataJson.serie_comprobante);
        $("#ncIngreso").val(dataJson.num_comprobante);
        $("#impIngreso").val(dataJson.impuesto);
        //Ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });
    $.post("../ajax/ingreso.ajax.php?op=listar_detalle&id=" + idIng, function (r) {
        $("#tblDetalles").html(r);
    })
}

// Funcion para desactivar registros
function anular(idIng) {
    swal({
        title: "¿Esta seguro de anular el ingreso?",
        text: "¡Si no lo esta puede cancelar la accion!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Si, anular Ingreso",
        cancelButtonColor: "#d33",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        // console.log(result.value);
        if (result.value) {//1, true
            $.post("../ajax/ingreso.ajax.php?op=anular",
                {idIngreso: idIng},
                function (datos) {
                    swal(datos.trim(), 'Revise el cambio', 'success').then((e) => {
                        if (e.value) {
                            tabla.ajax.reload();
                        }
                    });
                }
            );
        }
    })
    ;
}

function marcarImpuesto() {
    var tipo_comprobante = $("#tcIngreso option:selected").val();
    if (tipo_comprobante === 'FACTURA') {
        $("#impIngreso").val(impuesto);
    } else {
        $("#impIngreso").val("0");
    }
}

function agregarDetalle(id_articulo, nom_articulo) {
    var cantidad = 1;
    var precio_compra = 1;
    var precio_venta = 1;
    if (id_articulo != "") {
        // alert("Entro la funcion agregar detalle");
        var subtotal = cantidad * precio_compra;

        var fila =
            '<tr class="filas" id="fila' + cont + '">' +
            '<td><input type="hidden" name="idArticulo[]" value="' + id_articulo + '">' + id_articulo + '</td>' +
            '<td><input type="hidden" name="nomArticulo[]" value="' + nom_articulo + '">' + nom_articulo + '</td>' +
            '<td><input type="number" name="cantArticulo[]" id="cantArticulo[]" value="' + cantidad + '"></td>' +
            '<td><input type="number" name="precioCompra[]" id="precioCompra[]" value="' + precio_compra + '"></td>' +
            '<td><input type="number" name="precioVenta[]" id="precioVenta[]" value="' + precio_venta + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td style="text-align:center;">' +
            '<button style="margin-right: 1em" type="button" class="btn btn-info"onclick="modificarSubtotales()" ><i class="fa fa-refresh" ></i></button>' +
            '<button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-remove" ></i></button>' +
            '</td>' +
            '</tr>';
        cont++;
        detalles++;
        $("#tblDetalles").append(fila);
        $("#btnGuardar").show();
        // modificarSubtotales();
    } else {
        alert("Error al ingresar el detalle, revisar los datos del articulo")
    }
}

function modificarSubtotales() {
    var cant = document.getElementsByName("cantArticulo[]");
    var prec = document.getElementsByName("precioCompra[]");
    var sub = document.getElementsByName("subtotal");
    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpS = sub[i];
        inpS.value = inpC.value * inpP.value;
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }
    calcularTotales();
}

function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;
    for (var i = 0; i < sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("S/. " + total);
    $("#total_compra").val(total);
    evaluar();
}

function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    } else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    calcularTotales();
    detalles -= 1
}

//Declaracion de variables necesarios para trabajar con los campos y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
$("#btnGuardar").hide();
$("#tcIngreso").change(marcarImpuesto);
init();

