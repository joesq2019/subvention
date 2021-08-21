$("#logout").click(function(e) {
    e.preventDefault();
    alertify.confirm('Alerta', 'Desea Salir?', function() {
        alertify.success('Ok');
        var dt = {
            method: 'logout'
        }
        $.post("../models/md_login.php", dt,
            function(data) {
                if (data.serverStatus == 200) {
                    window.location.href = 'login.php';
                }
            },
            "json"
        );
    }, function() { alertify.error('Cancel') });
});


function loadNotification() {
    var dt = {
        method:'loadNotification'
    };
    $.post('../models/md_noti.php', dt, function(data) {
        if (data.code == 200) {
            $("#notificar").html(' Usted Tiene Derivaciones: '+ data.cantidad);
        }
        if (data.code == 404) {
            $("#notificar").html("");
        }
    },'json');
}

loadNotification();


/**
 * show_preloader
 * @param {String} type
 * @param {String} message
 * @param {String} status
 */
function preloader(type, message = '', status = '') {
    // console.log('preloader');
    swal({
        title: "Cargando",
        text: "Por favor espere",
        showConfirmButton: false,
        showCancelButton: false,
        allowOutsideClick: false,
        onOpen: function() {
            if (type == 'show') {
                swal.showLoading()
            } else {
                if (message != '') {
                    swal({
                        title: message,
                        type: status
                    });
                } else {
                    swal.close()
                }
            }

        }
    }).then(function(e) {
        "timer" === e.dismiss && console.log("I was closed by the timer")
    })
}
