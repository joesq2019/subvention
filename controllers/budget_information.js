var MODEL = '../models/budget_information_model.php';
var dataTable = '';
var formCreateBudgetInformation = $("#formCreateBudgetInformation");

var budgetInformationController = {
    init: () => {

        dataTable = $('#budgetInformationDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "budgetInformationList"
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

        jQuery.validator.addMethod("amount", function(value, element) {
            return this.optional(element) || /^[0-9]+([.][0-9]+)?$/.test(value);
        }, "Numeros enteros ó decimales (ejemplo: 3,50)");

        jQuery.validator.addMethod("alphaespacios", function(value, element) {
            return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
        }, "Solo letras y espacios");

        jQuery.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || /^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(value);
        }, "Instroduzca un rut válido");        
        
        $("#formCreateBudgetInformation").validate({
            rules: {
                add_budget_certificate_number: {
                    required: true
                },
                add_emision_date: {
                    required: true
                },
                add_amount_available: {
                    required: true,
                    amount: true
                },
                documento_de_respaldo: {
                    required: true
                }
            },
            messages: {
                add_budget_certificate_number: {
                    required: "El numero de certificado es requerido.",
                },
                add_emision_date: {
                    required: "La fecha de emision es requerida.",
                },
                add_amount_available: {
                    required: "El monto es requerido.",
                },
                documento_de_respaldo: {
                    required: "El documento de respaldo es requerido.",
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
        $("#btn_newBudgetInformation").click(function(event) {
            event.preventDefault();
            budgetInformationController.clean();
            $("#modalCreateBudgetInformation").modal('show');
        });

        $("#btn_saveBudgetInformation").click(function(event){
            event.preventDefault();
            if ($("#formCreateBudgetInformation").valid()) {
                var dt = {
                    method: 'saveNewBudgetInformation',
                    id_budget_information: $('#id_budget_information').val(),
                    add_budget_certificate_number: $('#add_budget_certificate_number').val(),
                    add_emision_date: $('#add_emision_date').val(),
                    add_amount_available: $('#add_amount_available').val()
                };
                preloader("show");
                $.post(MODEL, dt,
                    function(data) {     
                    console.log(data)                 
                        if (data.code == 200) {
                            var file = document.getElementById("documento_de_respaldo").files;
                            if (file.length > 0) {
                                var dt = new Date();
                                var time = dt.getHours() + "" + dt.getMinutes() + "" + dt.getSeconds() + "" + dt.getDay() + "" + dt.getMonth() + "" + dt.getYear();
                                preloaderTwo("Guardando documento del usuario, por favor espere..!");
                                var storageRef = firebase.storage().ref();                                    
                                var id_user = data.id_user;
                                var ext = '.'+file[0].name.split('.').pop();
                                var new_name = id_user+'_'+time+'_'+ext;
                                var path = 'financing_role/'+new_name;
                                var name_file = file[0].name;

                                var uploadTask = storageRef.child(path).put(file[0]);
                                uploadTask.on('state_changed', function(snapshot) {
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
                                },
                                function(error) {
                                    // Handle unsuccessful uploads
                                }, function() {

                                    uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {                        
                                         $.post(MODEL, {
                                            "method": 'uploadFileCertificate',
                                            "id_budget_information" : data.id_budget_information,
                                            "id_user": id_user,
                                            "name": name_file,
                                            "path": path,
                                            "url": downloadURL
                                            },function(response) {
                                            	console.log(response)
                                                if (response.code == 440) {
                                                    loginTimeout(response.message);
                                                    return;
                                                } else if (response.code == 200) {
                                                    preloader("hide",data.message,'success');
                                                    $("#modalCreateBudgetInformation").modal('hide');
                                                    dataTable.ajax.reload();
                                                } else if (response.code == 204) {    
                                                    storageRef.child(path).delete();                         
                                                    preloader('hide', response.message, 'error');
                                                }
                                        }, "json");                                    
                                         
                                    });
                                });
                            }else{
                                preloader("hide",data.message,'success');
                                $("#modalCreateBudgetInformation").modal('hide');
                                dataTable.draw();
                            }               
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

        /////////////////////////////////////////////////////////////
        $("#documento_de_respaldo").on('change', function () {
            if(this.files.length>1){
                Swal.fire('No pueden ser mas de 1 archivo');
            }
            else {
                //Get count of selected files
                var files = $(this)[0].files;

                var imgPath = $(this)[0].value;
                var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
                var div = $("#lista_2");
                var image_holder = $("#lista_2");
                image_holder.empty();
            
                if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "pdf" || extn == "doc" || extn == "docx") {
                    if (typeof (FileReader) != "undefined") {
            
                        //loop for each file selected for uploaded.
                        for (var i = 0; i < files.length; i++) {
                           console.log(files[i].name+'_'+Date.now())
                           // var xx = ;
                            var reader = new FileReader();
                            
                            if (extn == "pdf" || extn == "doc" || extn == "docx") {
                                // reader.onload = function (e) {
                                    $(`<div class="col-md-3 text-center"><img src="../assets/img/file.png" class="rounded img-fluid" style="height:150px;width:150px"><br><small>${files[i].name}</small></div>`).appendTo(image_holder);
                                // }
                            }else{
                                reader.onload = function (e) {
                                    $("<img />", {
                                        "src": e.target.result,
                                            "class": "col-md-3 rounded img-fluid",
                                            "style": "height:150px;width:150px"
                                    }).appendTo(image_holder);
                                } 
                            }
            
                            image_holder.show();
                            reader.readAsDataURL($(this)[0].files[i]);
                        }
            
                    }
                } else {
                    Swal.fire("Por favor seleccione un archivo");
                }
            }
        });
    },
    edit: function(id) {
        event.preventDefault();
        budgetInformationController.clean();
        
        var dt = { method: 'editbudgetInformation', id: id };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {
                if (data.code == 200) {
                    preloader('hide');
                   
                    $("#id_budget_information").val(data.id_budget_information);
                    $("#add_budget_certificate_number").val(data.budget_certificate_number);
                    $("#add_emision_date").val(data.emision_date);
                    $("#add_amount_available").val(data.amount_available);

                    $('#lista_2').html('');
                    $(`<div class="col-md-3 text-center"><img src="../assets/img/file.png" class="rounded img-fluid" style="height:150px;width:150px"><br><small>${data.name_document}</small></div>`).appendTo('#lista_2');
                    
                    
                    $("#modalCreateBudgetInformation").modal("show");
                }
                if(data.code == 204){
                    $("#modalCreateBudgetInformation").modal("hide");
                    preloader("hide",data.message,'error');
                }
                if (data.code == 440) {
                    $("#modalCreateBudgetInformation").modal("hide");
                    loginTimeout();
                }
            },
            "json"
        );
    },
    cancel: function (id,amount_available){
        event.preventDefault();
        preloader("show");
        var parametros = {
            "method": "findAccumulatedAmount",
            "id": id,
        };
        $.post(MODEL, parametros, function(data) {
            if (data.code == 200) {
                var resultado = data.accumulated_amount - amount_available;
                Swal.fire({
                    title: "Estas seguro de realizar esta accion?",
                    text: `Si anulas este certificado presupuestario, el monto del mismo(${amount_available}) será descontado al monto total acumulado y este ultimo puede quedar con saldo negativo..!
                    Ahora mismo el monto total acumulado es de: ${data.accumulated_amount} y luego de anular será: ${resultado}`,
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
                            "method": "cancelBudgetInformation",
                            "id": id,
                            "resultado": resultado
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
            }
            if(data.code == 204){
                preloader("hide",data.message,'error');
            }
            if(data.code == 440) {
                loginTimeout();
            }
        },'json');
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
 
        $('#id_budget_information').val(0);
        $("#add_budget_certificate_number").val("");
        $("#add_emision_date").val("");
        $("#add_amount_available").val("");
        $("#documento_de_respaldo").val("");
        $("#lista_2").html("");
    },
};

$(function() {
    budgetInformationController.init();
    budgetInformationController.events();
});

document.addEventListener("DOMContentLoaded", function(event) {

    var firebaseConfig = {
        apiKey: "AIzaSyAO6Rdrv7ZhLayWR0QINZu48DDJyONz7YY",
        authDomain: "subvention10.firebaseapp.com",
        projectId: "subvention10",
        storageBucket: "subvention10.appspot.com",
        messagingSenderId: "755390448628",
        appId: "1:755390448628:web:7c15442186ae1b25f26fa1",
        measurementId: "G-T1HP4SMLSG"
    };

    if (!firebase.apps.length) { 
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
    }

    var email = 'subvenciones10@gmail.com';
    var password = 'subvenciones10@gmail.com';

    firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
        var errorCode = error.code;
        var errorMessage = error.message;
        console.log("Error autenticating Firebase!"+ errorCode + ' - ' + errorMessage, "error");
    });

});