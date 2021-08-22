var MODEL = '../models/users_model.php';
var dataTable = '';
var formCreateUser = $("#formCreateUser");

var userController = {
    init: () => {
        findRoles();

        dataTable = $('#usersDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "userList"
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

        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
        }, "Solo letras y números");

        jQuery.validator.addMethod("telefono", function(value, element) {
            return this.optional(element) || /^[0-9+-\s]*$/.test(value);
        }, "Solo números, espacios y guiones");

        jQuery.validator.addMethod("nombres", function(value, element) {
            return this.optional(element) || /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/.test(value);
        }, "Solo letras");        

        jQuery.validator.addMethod("alphaespacios", function(value, element) {
            return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
        }, "Solo letras y espacios");

        jQuery.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || /^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(value);
        }, "Instroduzca un rut válido");        
        
        $("#formCreateUser").validate({
            rules: {
                add_name: {
                    required: true,
                    nombres: true
                },
                add_apellido: {
                    nombres: true
                },
                add_username: {
                    required: true,
                    alphanumeric: true
                },
                add_rut: {
                    required: true,
                    rut: true
                },
                add_rol: {
                    required: true,
                },
                add_password: {
                    required: true,
                    minlength: 8,
                    alphanumeric: true
                },
                add_repeat_password: {
                    required: true,
                    minlength: 8,
                    equalTo: "#add_password"
                },
                add_email: {
                    required: true,
                    email: true
                },
                add_telefono: {
                    required: true,
                    telefono: true
                },
                add_direccion: {
                    required: true
                }
            },
            messages: {
                add_name: {
                    required: "El nombre es requerido.",
                },
                add_rut: {
                    required: "El rut es requerido.",
                },
                add_username: {
                    required: "El username es requerido.",
                },
                add_rol: {
                    required: "El rol es requerido.",
                },
                add_password: {
                    required: "La contraseña es requerida requerida.",
                },
                add_email: {
                    required: "El email es requerido.",
                    email: "Introduzca una direccion de correo valido, ejemplo: tunombre@gmail.com"
                },
                add_repeat_password: {
                    required: "Repetir la contraseña es obligatorio.",
                    equalTo: "La contraseña repetida no coincide con la anterior"
                },
                add_telefono: {
                    required: "El número de contacto es requerido."
                },
                add_direccion: {
                    required: "La dirección es requerida."
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
        $("#btn_newUser").click(function(event) {
            event.preventDefault();
            userController.clean();
            $("#modalCreateUser").modal('show');
        });

        $("#btn_saveUser").click(function(event){
            event.preventDefault();
            if ($("#formCreateUser").valid()) {
                var dt = {
                    method: 'saveNewUser',
                    id_user: $("#id_user").val(),
                    add_name: $("#add_name").val(),
                    add_last_name: $("#add_last_name").val(),
                    add_rut: $("#add_rut").val(),
                    add_username: $("#add_username").val(),
                    add_password: $("#add_password").val(),
                    add_email: $("#add_email").val(),
                    add_role: $("#add_role").val(),
                    add_phone: $("#add_phone").val(),
                };
                preloader("show");
                $.post(MODEL, dt,
                    function(data) {                      
                        if (data.code == 200) {
                            preloader("hide",data.message,'success');
                            $("#modalCreateUser").modal('hide');
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

        $("#seePass").mouseover(function() {
            $("#add_password").attr("type", "text");
            $("#add_repeat_password").attr("type", "text");
        });

        $("#seePass").mouseout(function() {
            $("#add_password").attr("type", "password");
            $("#add_repeat_password").attr("type", "password");
        });

        $('#add_username').blur(function() {
            var parametros = {
                "method": "checkUserName",
                "username": $('#add_username').val(),
                "id": $('#id_user').val(),
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 204) {
                    $('#add_username').val('');
                    preloader("hide",data.message,'info');
                }
                if (data.code == 440) {
                    loginTimeout();
                }
            },'json');
        });

        $('#add_email').blur(function() {
            var parametros = {
                "method": "checkEmail",
                "email": $('#add_email').val(),
                "id": $('#id_user').val(),
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 204) {
                    $('#add_email').val('');
                    preloader("hide",data.message,'');
                }
                if (data.code == 440) {
                    loginTimeout();
                }
            },'json');
        });

        $('#add_rut').blur(function() {
            var parametros = {
                "method": "checkRut",
                "rut": $('#add_rut').val(),
                "id": $('#id_user').val(),
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 204) {
                    $('#add_rut').val('');
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
        userController.clean();

        $( "#add_password" ).rules( "add", { required: false });
        $( "#add_repeat_password" ).rules( "add", { required: false });  
        
        var dt = { method: 'editUser', id: id };
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
                    "method": "delUser",
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

        $('#add_name').val('');
        $('#add_last_name').val('');
        $('#add_rut').val('');
        $('#add_username').val('');
        $("#add_repeat_password").val("");
        $("#add_password").val("");
        $("#add_email").val("");
        $("#add_role").val("");

        $("#add_phone").val("");

        $( "#add_password" ).rules( "add", { required: true });
        $( "#add_repeat_password" ).rules( "add", { required: true }); 
    },
};

$(function() {
    userController.init();
    userController.events();
});

function findRoles(){
    //preloader("show");
    var parametros = {
        "method": "findRoles"
    };
    $.post(MODEL, parametros, function(data) {
        // if (data.code == 200) {
        //     dataTable.draw();
        //     preloader("hide", data.message, 'success');
        // }
        $("#add_role").html(data);
    },'json');
}

document.addEventListener("DOMContentLoaded", function(event) {

    //CARGA DE FIREBASE

});