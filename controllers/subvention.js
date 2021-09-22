var MODEL = '../models/subvention_model.php';
var dataTable = '';
var dataTableDocuments = '';

var subventionController = {
    init: () => {      
        console.log("Init");

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

        $("#uploadNewDocument").click(function(){
            subventionController.clean();
            $("#modalUploadDocuments").modal('show');
            setTimeout(function(){
                var validator = $("#formUploadDocument").validate();
                validator.resetForm();
            },100);
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

        ///////////////////////////////////////////
        $('#subventionDataTable tbody').on('click', '.button_view', function () {
            var $tr = $(this).closest('tr');
            var data = dataTable.row($(this).parents($tr)).data();
            var id = data[0];
            subventionController.view(id)
        });
    },
    edit: function(id) {
        event.preventDefault();
        sessionStorage.setItem('id_subvention', id);
        sessionStorage.setItem('action', 2);
        window.location.href = 'subvention_add.php';
    },
    view: function (id){
        
        event.preventDefault();
        sessionStorage.setItem('id_subvention', id);
        sessionStorage.setItem('action', 2);
        window.location.href = 'subvention_view.php';
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