var MODEL = '../models/login_model.php';
//var MODELORECUPERAR = '../models/md_recuperar.php';

var form_login = $("#form_login");

var loginController = {
    init: () => {

        var token = getParameterByName('recover');
        if (token != '') {
            var dt = { method: 'findToken', token: token, key: $("#key").val() }
            preloaderTwo("Cargando... por favor espere!");
            $.post(MODEL, dt,
                function(data) {
                    console.log(data)
                    if (data.code == 200) {
                        preloader("hide","Ok, Ahora puedes cambiar tu contraseña!","success");
                        $("#old_email").val(data.email);
                        $("#user_id").val(data.id_user);
                        $("#div_change_password").show('slow');
                        $("#div_login").hide('slow');
                        $("#div_recover").hide('slow');
                    }
                    if (data.code == 204) {
                        Swal.fire({
                            text: data.message,
                            icon: 'info',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Oxk!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#div_change_password").hide('slow');
                                $("#div_login").hide('slow');
                                $("#div_recover").show('slow');
                                $("#email").val("");
                            }
                        })
                    }
                },
                "json"
            );
        }

        $("#email").val("");
        $("#password").val("");

        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
        }, "Solo letras y números");

        $("#form_login").validate({
            rules: {
                username: {
                    required: true,
                },
                password: {
                    required: true,
                }
            },
            messages: {
                username: {
                    required: "El nombre de usuario es requerido.",
                },
                password: {
                    required: "La contraseña es requerida.",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).css('border-bottom', 'solid 2px red');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).css('border-bottom', 'solid 2px #56baed');
            }
        });

        $("#form_recover").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "El correo electrónico es requerido.",
                    email: "Introduzca un correo con formato válido, tunombre@ejemplo.com",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append('<br>');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).css('border-bottom', 'solid 2px red');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).css('border-bottom', 'solid 2px #56baed');
            }
        });

        $("#form_new_password").validate({
            rules: {
                new_password: {
                    required: true,
                    minlength: 8,
                    alphanumeric: true
                },
                repeat_new_password: {
                    required: true,
                    equalTo: "#new_password"
                }
            },
            messages: {
                new_password: {
                    required: "La contraseña es requerida.",
                    minlength: "La contraseña debe contener al menos 8 caracteres.",
                    alphanumeric: "La contraseña solo puede contener letras y números."
                },
                repeat_new_password: {
                    required: "Repetir la contraseña es obligatorio.",
                    equalTo: "La contraseña repetida no coincide con la anterior"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append('<br>');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).css('border-bottom', 'solid 2px red');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).css('border-bottom', 'solid 2px #56baed');
            }
        });
    },
    events: function() {
        $("#btn_login").click(function(e) {
        	event.preventDefault();
        	e.preventDefault();
            if ($("#form_login").valid()) {
                
                var dt = {
                    method: 'login',
                    key: $("#key").val(),
                    username: $("#username").val(),
                    password: $("#password").val()
                }
                $.post(MODEL, dt,
                    function(data) {
                        //console.log(data)
                        if (data.code == 200) {
                            preloader("hide","Hola, haz iniciado sesion correctamente","success");
                            setTimeout(function(){
                                window.location.href = 'dashboard.php';
                            },2000);                        
                        }
                        if (data.code == 204) {
                            $("#username").val("");
                            $("#password").val("");
                            preloader("hide",data.message,"warning");
                        }
                        if (data.code == 900) {
                            $("#username").val("");
                            $("#password").val("");
                            Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: data.message
                            })
                        }
                    },
                    "json"
                );
            }
        });

        $("#btn_recover").click(function(e) {
            e.preventDefault();
            if ($("#form_recover").valid()) {
                var dt = {
                    method: 'recover',
                    key: $("#key").val(),
                    email: $("#email").val()
                }
                preloaderTwo("Cargando... por favor espere!");
                $.post(MODEL, dt,
                    function(data) {
                        //console.log(data)
                        if (data.code == 200) {
                            //preloader("hide",data.message,"success");
                            Swal.fire({
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#div_change_password").hide('slow');
                                    $("#div_login").show('slow');
                                    $("#div_recover").hide('slow');
                                    $("#email").val("");
                                }
                            })
                        }
                        if (data.code == 204) {
                            $("#email").val("");
                            preloader("hide",data.message,"warning");
                        }
                    },
                    "json"
                );
            }
        });

        $("#btn_save_password").click(function(e) {
            event.preventDefault();
            e.preventDefault();
            if ($("#form_new_password").valid()) {
                var dt = {
                    method: 'savePassword',
                    key: $("#key").val(),
                    user_id: $("#user_id").val(),
                    new_password: $("#new_password").val()
                }
                $.post(MODEL, dt,
                    function(data) {
                        //console.log(data)
                        if (data.code == 200) {
                            preloader("hide","Haz cambiado la contraseña de tu usuario exitosamente, ya puedes iniciar sesion.","success");
                            loginController.clean();
                            $("#div_login").show('slow');
                            $("#div_recover").hide('slow');
                            $("#div_change_password").hide('slow');                   
                        }
                        if (data.code == 204) {
                            $("#new_password").val("");
                            $("#repeat_new_password").val("");
                            preloader("hide",data.message,"warning");
                        }
                    },
                    "json"
                );
            }
        });

        $("#olvido_password").click(function(event) {
            event.preventDefault();
            $("#div_login").hide('slow');
            $("#div_recover").show('slow');
            $("#div_change_password").hide('slow');
        });

        $(".volver_login").click(function(event) {
            event.preventDefault();
            loginController.clean();
            $("#div_login").show('slow');
            $("#div_recover").hide('slow');
            $("#div_change_password").hide('slow');
        });

        $("#dropdown_test").mouseenter(function(){
            console.log("aaaaaaaa")
            $("#dropdown_roles").addClass('show');
        });
        $("#dropdown_roles").mouseleave(function(){
            console.log("bbbbbbbbbb")
            $("#dropdown_roles").removeClass('show');
        });

    },
    clean: function() {
        $("#username").val("");
        $("#old_email").val('');
        $("#persona_id").val('');
        $("#email").val("");
        $("#password").val("");
    }
};

$(function() {
    loginController.init();
    loginController.events();
});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}