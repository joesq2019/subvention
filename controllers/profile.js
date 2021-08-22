var MODEL = '../models/profile_model.php';

var dataTable = '';

var userController = {
    init: () => {

        var dt = { method: 'findUser' };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {                
                preloader('hide');

                $("#id_persona").val(data.id);
                $("#add_name").val(data.name);
                $("#add_last_name").val(data.last_name);
                $("#add_rut").val(data.rut);
                $("#add_username").val(data.username);
                $("#add_email").val(data.email);
                $("#add_phone").val(data.phone);
                $("#add_role").val(data.role_name);

                if (data.url != '' && data.url != null) {
                    $("#img_perfil").attr("src",data.url);
                    $("#path_foto").val(data.path);
                }else{
                    $("#img_perfil").attr("src","../assets/img/avatar.png");
                }

                if (data.code == 440) {
                    loginTimeout();
                }
            },
            "json"
        );
       
        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
        }, "Solo letras y números");

        jQuery.validator.addMethod("telefono", function(value, element) {
            return this.optional(element) || /^[0-9-\s]*$/.test(value);
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
       
        jQuery.validator.addMethod("imagen", function(value, element) {
            return this.optional(element) || /\.(jpg|png|gif|jpeg)$/.test(value);
        }, "La imagen debe ser tipo jpg, png, gif, jpeg"); 
        
        $("#formProfile").validate({
            rules: {
                add_name: {
                    required: true,
                    nombres: true
                },
                add_last_name: {
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
                add_email: {
                    required: true,
                    email: true
                },
                add_phone: {
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
                add_role: {
                    required: "El rol es requerido.",
                },
                add_password: {
                    required: "La contraseña es requerida requerida.",
                },
                add_email: {
                    required: "El email es requerido.",
                    email: "Introduzca una direccion de correo valido, ejemplo: tunombre@gmail.com"
                },
                add_phone: {
                    required: "El telefono es requerido."
                },
                add_direccion: {
                    required: "La dirección es requerido."
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

        $("#formPassword").validate({
            rules: {
                old_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                    minlength: 8,
                    alphanumeric: true
                },
                confirm_password: {
                    required: true,
                    minlength: 8,
                    equalTo: "#new_password"
                }
            },
            messages: {
                old_password: {
                    required: "La contraseña anterior es requerida."
                },
                new_password: {
                    required: "La contraseña es requerida.",
                    minlength: "La contraseña debe tener al menos 8 digitos.",
                },
                confirm_password: {
                    required: "Repetir la contraseña es obligatorio.",
                    equalTo: "La contraseña repetida no coincide con la anterior"
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

        $("#formFoto").validate({
            rules:{
                edit_foto_perfil:{
                    required:true,
                    imagen: "jpg|jpeg|png|JPG|JPEG|PNG"
                }
            },
            messages: {  // <-- you must declare messages inside of "messages" option
                edit_foto_perfil:{
                    required:"La imagen es requerida"
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

        $("#info-tab").click(function(event) {
            $("#div_informacion_personal").show();
            $("#div_contraseña").hide();
        });

        $("#contraseña-tab").click(function(event) {
            $("#div_contraseña").show();
            $("#div_informacion_personal").hide();
        });

        $('.date-picker').datepicker( {
            format: "yyyy-mm",
            viewMode: "months", 
            minViewMode: "months"
        });

        $("#btn_save_profile").click(function(event){
            event.preventDefault();
            if ($("#formProfile").valid()) {
                var dt = {
                    method: 'saveProfile',
                    add_rut: $("#add_rut").val(),
                    add_name: $("#add_name").val(),
                    add_last_name: $("#add_last_name").val(),                    
                    add_username: $("#add_username").val(),
                    add_phone: $("#add_phone").val(),
                    add_email: $("#add_email").val()
                };
                preloader("show");
                $.post(MODEL, dt,
                    function(data) {                      
                        if (data.code == 200) {
                            preloader("hide",data.message,'success');
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

        $("#btn_save_password").click(function(event){
            if ($("#formPassword").valid()) {
                var dt = { 
                    method: 'savePassword',
                    old_password: $("#old_password").val(),
                    new_password: $("#new_password").val()
                };
                preloader('show');
                $.post(MODEL, dt,
                    function(data) {
                        if (data.code == 200) {
                            $("#old_password").val("");
                            $("#new_password").val("");
                            $("#confirm_password").val("");
                            preloader('hide',data.message,'success');
                        }
                        if(data.code == 204){
                            $("#old_password").val("");
                            $("#new_password").val("");
                            $("#confirm_password").val("");
                            preloader('hide',data.message,'warning');
                        }
                        if (data.code == 440) {
                            loginTimeout();
                        }
                    },
                    "json"
                );
            }
        });

        $('#add_username').blur(function() {
            var parametros = {
                "method": "checkUserName",
                "username": $('#add_username').val()
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 200) {
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
                "email": $('#add_email').val()
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 200) {
                    $('#add_email').val('');
                    preloader("hide",data.message,'info');
                }
                if (data.code == 440) {
                    loginTimeout();
                }
            },'json');
        });

        $('#add_rut').blur(function() {
            var parametros = {
                "method": "checkRut",
                "rut": $('#add_rut').val()
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 200) {
                    $('#add_rut').val('');
                    preloader("hide",data.message,'info');
                }
                if (data.code == 440) {
                    loginTimeout();
                }
            },'json');
        });

        $("#btn_save_foto_perfil").click(function(){
            if ($("#formFoto").valid()) {
                //console.log(firebase.apps.length)
                if (!firebase.apps.length) { 
                    preloader("hide","Ha ocurrido un error y la pagina necesita refrescarse",'info');
                    setTimeout(function(){
                        window.location.href = 'profile.php';
                    },2000);  
                } else{
                    preloaderTwo("Cargando la foto, por favor espere..!");
                    if ($("#path_foto").val() != '') {
                        var storageRef = firebase.storage().ref();
                        var desertRef = storageRef.child($("#path_foto").val());

                        desertRef.delete().then(function() {
                        }).catch(function(error) {
                        });
                    }                    

                    var storageRef = firebase.storage().ref();
                    var file = document.getElementById("edit_foto_perfil").files;
                    var ext = '.'+file[0].name.split('.').pop();
                    var new_name = $("#id_user").val()+ext;
                    var path = 'perfiles/'+new_name;
                    
                    var uploadTask = storageRef.child(path).put(file[0]);
                    uploadTask.on('state_changed', function(snapshot){
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        console.log('Upload is ' + progress + '% done');
                        switch (snapshot.state) {
                            case firebase.storage.TaskState.PAUSED: // or 'paused'
                                console.log('Upload is paused');
                            break;
                            case firebase.storage.TaskState.RUNNING: // or 'running'
                                console.log('Upload is running');
                            break;
                        }
                    }, function(error) {
                        console.log(error);
                        $('#file_foto').val('');
                        preloader("hide","Ha ocurrido un error, intente nuevamente...",'warning');
                    }, function() {
                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            var parametros = {
                                "method": "saveFoto",
                                "path": path,
                                "url": downloadURL,
                                "id_user": $("#id_user").val()
                            };
                            $.post(MODEL, parametros, function(data) {
                                if (data.code == 200) {
                                    $("#img_perfil").attr("src",downloadURL);
                                    $("#path_foto").val(path);
                                    $("#edit_foto_perfil").val('');
                                    preloader("hide",data.message,'success');
                                }
                                if (data.code == 204) {
                                    preloader("hide",data.message,'warning');
                                }
                                if (data.code == 440) {
                                    loginTimeout();
                                }
                            },'json');                            
                        });
                    });
                }                
            }else{
                console.log("no");
            }
        });

        $("#btn_buscar_foto").click(function(){
            $("#file_foto").click(); 
        });

        $("#file_foto").change(function(event) {
            if (!$("#formFoto").valid()) {
                $("#file_foto").val("")
            }
        });
    }
};

$(function() {
    userController.init();
    userController.events();
});


document.addEventListener("DOMContentLoaded", function(event) {

    //CARGAR FIREBASE

});