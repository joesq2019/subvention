var MODEL = '../models/roles_model.php';
var dataTable = '';

var rolesController = {
    init: () => {
        dataTable = $('#rolesDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "rolesList"
                },
                type: "post",
                error: function() {}
            },
            "order": [
                [0, "desc"]
            ],
            columnDefs: [
                { "width": "20%", "targets": 2 }
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


        jQuery.validator.addMethod("nombres", function(value, element) {
            return this.optional(element) || /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/.test(value);
        }, "Solo letras");           
        
        $("#data_permisos").validate({
            rules: {
                add_name: {
                    required: true,
                    nombres: true
                }
            },
            messages: {
                add_name: {
                    required: "El nombre es requerido.",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //preloader('show');
    },
    events: function() {
        $("#btn_newRol").click(function(event) {
            event.preventDefault();
            rolesController.clean();
            $("#modalCreateRol").modal('show');
        });

        $("#btn_saveRole").click(function(event){
            event.preventDefault();
            var object = '';
            var formData = $("#data_permisos").serializeArray();
            let str = '';
            formData.forEach(function(value, key){
                if (key != 0) {//exceptuar el primer input
                    if (key+1 === formData.length) { ///terminó el ciclo each
                        object += `"${value.name}": "${value.value}"`;
                    }else{
                        object += `"${value.name}": "${value.value}",`;
                    }
                    str = object.replace(/(^[,\s]+)|([,\s]+$)/g, '');
                }                
            });
            var json = `{${str}}`;
            //var permisos = JSON.stringify(json);
            //console.log(permisos)
            if ($("#data_permisos").valid()) {
                var dt = {
                    method: 'saveRole',
                    id_role: $('#id_role').val(),
                    add_name: $("#add_name").val(),
                    permissions: json
                };
                preloader("show");
                $.post(MODEL, dt,
                    function(data) {                      
                        if (data.code == 200) {
                            preloader("hide",data.message,'success');
                            $("#modalCreateRol").modal('hide');
                            dataTable.draw();
                        }
                        if(data.code == 204){
                            preloader("hide",data.message,'error');
                        }
                        if (data.code == 440) {
                            loginTimeout();
                        }
                    },
                    "json"
                );
            }
        });

        $('#add_name').blur(function() {
            var parametros = {
                "method": "checkname",
                "add_name": $('#add_name').val(),
                "id": $('#id_role').val(),
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 204) {
                    $('#add_name').val('');
                    preloader("hide",data.message,'info');
                }
                if (data.code == 440) {
                    loginTimeout();
                }
            },'json');
        });
    },
    edit: function(id) {
        event.preventDefault();
        rolesController.clean();

        var dt = {
            method: 'editRol',
            id_rol: id
        };
        preloader("show");
        $.post(MODEL, dt,
            function(data) {                    
                if (data.code == 200) {
                    $("#id_role").val(id);
                    $("#add_name").val(data.name);
                    
                    var json = JSON.parse(data.permissions);                    

                    $.each(json, function(value, key) {
                       $("#"+value).prop("checked", true);
                    });

                    $("#modalCreateRol").modal('show');
                    preloader("hide");
                }
                if(data.code == 204){
                    preloader("hide",data.message,'error');
                }
                if (data.code == 440) {
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
                var dt = {
                    method: 'delRole',
                    id: id
                };
                preloader("show");
                $.post(MODEL, dt,
                    function(data) {                    
                        if (data.code == 200) {
                            preloader("hide",data.message,'success');
                            dataTable.draw();                    
                        }
                        if(data.code == 204){
                            preloader("hide",data.message,'error');
                        }
                    },
                    "json"
                );
            }
        });                
    },
    clean: function() {
        $("#id_role").val(0);
        $("#add_name").val('');
        
        $(".kick").prop("checked", false);
    },
};

$(function() {
    rolesController.init();
    rolesController.events();
});

document.addEventListener("DOMContentLoaded", function(event) {



});