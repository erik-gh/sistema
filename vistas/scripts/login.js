$("#frmAcceso").on('submit', function (e) {
    e.preventDefault();
    loginA = $("#logina").val();
    claveA = $("#clavea").val();
    $.post("../ajax/usuario.ajax.php?op=verificar",
            {"logina": loginA, "clavea": claveA},
            function (data) {
                if (data.trim() !== 'null') {
                    $(location).attr("href", "escritorio.vista.php");
                } else {
                    swal('!No se pudo Loguear¡', 'Usuario y/o Password incorrectos', 'error');
                }
            });
})

