var MODEL = '../models/subvention_model.php';
var dataTable = '';
var dataTableDocuments = '';

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);


var subventionController = {
    init: () => {      
        console.log("Init");

        if (urlParams.get("organization") !== null) {
            var id_organization = urlParams.get("organization");
            console.log("id_organization: "+id_organization)
        }

        dataTable = $('#subventionDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "subventionsList",
                    id_organization: id_organization > 0 ? id_organization : 0
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
                },
                buttons: {
                    pageLength: {
                        _: "Mostrar %d entradas",
                        '-1': "Todas las entradas"
                    }
                }
            },
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i>',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    text: '<i class="fas fa-file-pdf"></i>',
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-danger',
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                }
            ],
            "drawCallback": function( response ) {
                if (response.json !== undefined){
                    if (response.json.code == '440') {
                        loginTimeout(response.json.message);
                        return;
                    }
                    if(response.json.name_organization != ""){
                        $("#alert_name_organization").html("A continuación se mostraran todas las subvenciones de la organización: <span class='font-weight-bold'>"+response.json.name_organization+"<span>");
                        $("#alert_name_organization").show("");
                    }
                }
            }
        });
        
        $("#formUploadDocument").validate({
            rules: {
                select_type_documents: {
                    required: true
                },
                input_upload_document: {
                    required: true
                }
            },
            messages: {
                select_type_documents: {
                    required: "El tipo es requerido.",
                },
                input_upload_document: {
                    required: "El documento es requerido.",
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

        $("#formAction").validate({
            rules: {
                select_action: {
                    required: true
                },
                textarea_reason: {
                    required: true
                },
                add_no_mayor_decree: {
                    required: false
                },
                add_agreement_date: {
                    required: false
                },
                add_no_payment_decree: {
                    required: false
                },
                add_payment_date: {
                    required: false
                },
                add_no_payment_installments: {
                    required: false
                },
                add_no_session: {
                    required: false
                },
                add_session_date: {
                    required: false
                }
            },
            messages: {
                select_action: {
                    required: "Este campo es requerido.",
                },
                textarea_reason: {
                    required: "Este campo es requerido.",
                },
                add_no_mayor_decree: {
                    required: "El numero de certificado es requerido.",
                },
                add_agreement_date: {
                    required: "La fecha de convenio es requerida.",
                },
                add_no_payment_decree: {
                    required: "El N° de Decreto es requerido.",
                },
                add_payment_date: {
                    required: "La fecha de pago es requerida.",
                },
                add_no_payment_installments: {
                    required: "el numero de cuotas es requerido.",
                },
                add_no_session: {
                    required: "el numero de la sesion es requerido.",
                },
                add_session_date: {
                    required: "La fecha de sesion es requerido.",
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

        $("#uploadNewDocument").click(function(){
            subventionController.clean();
            $("#modalUploadDocuments").modal('show');
            setTimeout(function(){
                var validator = $("#formUploadDocument").validate();
                validator.resetForm();
            },100);
        });

        $("#select_action").change(function(){
            $(".approve_input").val("");
            $("#textarea_reason").val("");

            if($("#select_action").val() == 3){
                $( "#textarea_reason" ).rules( "add", { required: false });
                $( "#textarea_reason" ).removeClass('is-invalid');

                $( "#add_no_mayor_decree" ).rules( "add", { required: true });
                $( "#add_agreement_date" ).rules( "add", { required: true });
                $( "#add_no_payment_decree" ).rules( "add", { required: true });
                $( "#add_payment_date" ).rules( "add", { required: true });
                $( "#add_no_payment_installments" ).rules( "add", { required: true });
                $( "#add_no_session" ).rules( "add", { required: true });
                $( "#add_session_date" ).rules( "add", { required: true });

                $(".div_reason").hide();
                $(".div_approve").show();
            } else {
                $(".div_reason").show();
                $(".div_approve").hide();
                $( "#textarea_reason" ).rules( "add", { required: true });

                $( "#add_no_mayor_decree" ).rules( "add", { required: false });
                $( "#add_agreement_date" ).rules( "add", { required: false });
                $( "#add_no_payment_decree" ).rules( "add", { required: false });
                $( "#add_payment_date" ).rules( "add", { required: false });
                $( "#add_no_payment_installments" ).rules( "add", { required: false });
                $( "#add_no_session" ).rules( "add", { required: false });
                $( "#add_session_date" ).rules( "add", { required: false });
            }
        });

        $("#btnSaveAction").click(function(){
            if ($("#formAction").valid()) {
                var subvention_id = $("#id_subvention_action").val();
                var parametros = {
                    "method": "updateSubventionStatus",
                    "subvention_id": subvention_id,
                    "status": $("#select_action").val(),
                    "reason": $("#textarea_reason").val(),
                    "add_no_mayor_decree": $('#add_no_mayor_decree').val(),
                    "add_agreement_date": $('#add_agreement_date').val(),
                    "add_no_payment_decree": $('#add_no_payment_decree').val(),
                    "add_payment_date": $('#add_payment_date').val(),
                    "add_no_payment_installments": $('#add_no_payment_installments').val(),
                    "add_no_session": $('#add_no_session').val(),
                    "add_session_date": $('#add_session_date').val(),
                }
                if($("#select_action").val() == 3){
                    Swal.fire({
                        title: "Estas seguro de realizar esta acción?",
                        text: "Vas a aprobar esta subvención, lo que restará el monto de la misma al monto acumulado de disponibilidad presupuestaria",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, Hazlo!",
                        cancelButtonText: 'Cancelar!',
                    }).then(result => {
                        if (result.value) {
                            preloader("show");
                            $.post(MODEL, parametros, function(data) {
                                if (data.code == 200) {
                                    preloader("hide",data.message,'success');
                                    dataTable.draw();
                                    $("#modalActions").modal('hide');
                                }
                                if(data.code == 204){
                                    preloader("hide",data.message,'error');
                                }
                                if (data.code == 440) {
                                    loginTimeout();
                                }
                            }, 'json');
                        }
                    });
                } else {
                    preloader('show');
                    $.post(MODEL, parametros, function(data) {
                        if (data.code == 200) {
                            preloader("hide",data.message,'success');
                            dataTable.draw();
                            $("#modalActions").modal('hide');
                        }
                        if(data.code == 204){
                            preloader("hide",data.message,'error');
                        }
                        if (data.code == 440) {
                            loginTimeout();
                        }
                    }, 'json');
                }
            }
        });

        $("#btnSaveNewDocument").click(function(){
            if ($("#formUploadDocument").valid()) {
                var type = $("#select_type_documents").val();
                var subvention_id = $("#id_subvention").val();
                var parametros = {
                    "method": "checkDocumentsFiles",
                    "subvention_id": subvention_id,
                    "type": type
                }
                preloader('show');
                $.post(MODEL, parametros, function(data) {
                    if (data.code == 200) {

                        var storageRef = firebase.storage().ref();
                        var file = document.getElementById("input_upload_document").files;

                        if (file.length > 0) {
                            var ext = '.'+file[0].name.split('.').pop();
                            var new_name = subvention_id+'_'+type+ext;
                            var path = 'documents/'+new_name;
                            
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
                                //$('#file_foto').val('');
                                preloader("hide","Ha ocurrido un error, intente nuevamente...",'warning');
                            }, function() {
                                uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                                    var parametros = {
                                        "method": "saveDocumentsFiles",
                                        "subvention_id": subvention_id,
                                        "type": type,
                                        "name": file[0].name,
                                        "path": path,
                                        "url": downloadURL
                                    };
                                    $.post(MODEL, parametros, function(data2) {
                                        dataTableDocuments.draw();
                                        preloader("hide",data2.message,'success');
                                        $("#modalUploadDocuments").modal('hide');
                                    },'json');                               
                                });
                            });
                        }
                    }
                    if(data.code == 204){
                        preloader("hide",data.message,'error');
                    }
                    if (data.code == 440) {
                        loginTimeout();
                    }
                }, 'json');
            } 
        });
    },
    edit: function(id) {
        event.preventDefault();
        sessionStorage.setItem('id_subvention', id);
        sessionStorage.setItem('action', 2);
        window.location.href = 'subvention_add.php';
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
    editDocuments: function(id) {
        subventionController.clean();

        $("#id_subvention").val(id);

        if ($.fn.DataTable.isDataTable($("#dataTableListDocuments"))) {
            $("#dataTableListDocuments").DataTable().clear().destroy();
        }

        dataTableDocuments = $('#dataTableListDocuments').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "documentsList",
                    id_subvention: id
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
        $("#modalListDocuments").modal('show');
    },
    actions: function(id) {
        $("#id_subvention_action").val('');
        $("#select_action").val('');
        $("#textarea_reason").val('');

        $("#id_approval_subvention_id").val('');
        $("#add_no_mayor_decree").val('');
        $("#add_agreement_date").val('');
        $("#add_no_payment_decree").val('');
        $("#add_payment_date").val('');
        $("#add_no_payment_installments").val(''); 

        $("#add_session_date").val(''); 
        $("#add_no_session").val('');

        $(".div_reason").show();
        $(".div_approve").hide();

        $('.form-control').removeClass('is-invalid');

        setTimeout(function(){
            var validator = $("#formAction").validate();
            validator.resetForm();
            $('#formAction .form-control').removeClass('is-invalid');

            $("#id_subvention_action").val(id);
        },100);

        $("#modalActions").modal('show');
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
    deleteDocument: function (id){

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
                    "method": "deleteSubventionFile",
                    "id": id,
                };
                $.post(MODEL, parametros, function(data) {
                    console.log(data)
                    if (data.code == 200) {
                        var storageRef = firebase.storage().ref();
                        var desertRef = storageRef.child(data.path);
                        console.log(data.path)
                        desertRef.delete().then(function() {
                        }).catch(function(error) {
                        });
                        dataTableDocuments.draw();
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
    clean: function() {
        $("#id_user").val(0);

        $("#select_type_documents").val('');
        $("#input_upload_document").val('');
    },
    view: function (id){
        
        event.preventDefault();
        sessionStorage.setItem('id_subvention', id);
        sessionStorage.setItem('action', 2);
        window.location.href = 'subvention_view.php';
    },
    approve: function (id){
        
        // $("#id_approval_subvention_id").val('');
        // $("#add_no_mayor_decree").val('');
        // $("#add_agreement_date").val('');
        // $("#add_no_payment_decree").val('');
        // $("#add_payment_date").val('');
        // $("#add_no_payment_installments").val(''); 

        // setTimeout(function(){
        //     // var validator = $("#formAction").validate();
        //     // validator.resetForm();
        //     $('#formCreateApprovalSubsidy .form-control').removeClass('is-invalid');

        //     $("#id_approval_subvention_id").val(id);
        // },100);

        // $("#modalCreateApprovalSubsidy").modal('show');
    },
    convenio: function(id){
        event.preventDefault();
        Swal.fire({
            title: "Deseas generar el convenio?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, Hazlo!",
            cancelButtonText: 'Cancelar!',
        }).then(result => {
            if (result.value) {
                window.location.href = '../models/convenio.php?id='+id;
            }
        });
    }
};

$(function() {
    subventionController.init();
    subventionController.events();
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