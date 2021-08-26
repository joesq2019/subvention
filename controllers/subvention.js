var MODEL = '../models/subvention_model.php';
var dataTable = '';

var subventionController = {
    init: () => {      

        dataTable = $('#subventionDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "subventionsList"
                },
                type: "post",
                error: function() {}
            },
            "order": [
                [0, "asc"]
            ],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        //preloader('show');
    },
    events: function() {
        $("#btn_newSubvention").click(function(event) {
            event.preventDefault();
            subventionController.clean();
            sessionStorage.setItem('id_subvention', 0);
            sessionStorage.setItem('action', 1);
            window.location.href = 'subvention_add.php';
            // $("#btn_newSubvention").modal('show');
        });

       
    },
    edit: function(id) {
        event.preventDefault();
        subventionController.clean();
        
        var dt = { method: 'editSubvention', id: id };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {
                if (data.code == 200) {
                    preloader('hide');
                    $("#id_user").val(data.id_user);
                    $("#add_name").val(data.name);
                    $("#add_last_name").val(data.last_name);
                    $("#add_rut").val(data.rut);
                    $("#add_username").val(data.username);
                    $("#add_email").val(data.email);
                    $("#add_role").val(data.type_role);

                    $("#add_phone").val(data.phone);
                    
                    $("#modalCreateUser").modal("show");
                }
                if(data.code == 204){
                    $("#modalCreateUser").modal("hide");
                    preloader("hide",data.message,'error');
                }
                if (data.code == 440) {
                    $("#modalCreateUser").modal("hide");
                    loginTimeout();
                }
            },
            "json"
        );
    },
    deleted: function (id){

        event.preventDefault();
        Swal.fire({
            title: "Estas seguro de realizar esta accion?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, Hazlo!",
            cancelButtonText: 'Cancelar!',
        }).then(result => {
            if (result.value) {
                preloader("show");
                var parametros = {
                    "method": "delSubvention",
                    "id": id,
                };
                $.post(MODEL, parametros, function(data) {
                    if (data.code == 200) {
                        dataTable.draw();
                        preloader("hide", data.message, 'success');
                    }
                    if(data.code == 204){
                        preloader("hide",data.message,'error');
                    }
                    if(data.code == 440) {
                        loginTimeout();
                    }
                },'json');
            }
        });
    },
    status: function(id) {
        var parametros = {
            "method": "changeStatus",
            "id": id
        }
        preloader('show');
        $.post(MODEL, parametros, function(data) {
            if (data.code == 200) {
                preloader('hide', data.message, 'success');
                dataTable.draw();  
            }
            if(data.code == 204){
                preloader("hide",data.message,'error');
            }
            if (data.code == 440) {
                loginTimeout();
            }
        }, 'json');
    },
    clean: function() {
        $("#id_user").val(0);

    },
};

$(function() {
    subventionController.init();
    subventionController.events();
});
 

document.addEventListener("DOMContentLoaded", function(event) {

    //CARGA DE FIREBASE

});