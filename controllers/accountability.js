var MODEL = '../models/accountability_model.php';
var dataTable = '';
var dataTableDocuments = '';
var formCreateAccountability = $("#formCreateAccountability");
var count_invoices = 0;
var number_invoice = 0;

var accountabilityController = {
    init: () => {
        
        dataTable = $('#accountabilityDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "accountabilityList"
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

        jQuery.validator.addMethod("monto", function(value, element) {
            return this.optional(element) || /^[0-9]+([.][0-9]+)?$/.test(value);
        }, "Solo Numeros y decimales (eje: 20.05");              
        
        $("#formCreateAccountability").validate({
            rules: {
                add_mount_delivered: {
                    required: true
                },
                add_name_represent: {
                    required: true
                },
                lista_beneficiarios: {
                    required: true
                },
                add_invoice_number:{
                    required: true
                }
            },
            messages: {
                add_mount_delivered: {
                    required: "Este campo es requerido.",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                if(element[0].id == 'add_invoice_number'){

                }else{
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                }                
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
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


        $("body").delegate(".dateAcc", "focusin", function(){
            $(this).datepicker({
                format: "dd-mm-yyyy",
                altFormat: "DD-MM-YYYY",
                changeMonth: true,
                changeYear: true,
            }).on('changeDate', function(e) {
                $(this).datepicker('hide');
            });
        });
        
        //preloader('show');
    },
    add_dynamic_input_field: function()
    {
        var output = `<tr id="row${count_invoices}" class="test invoice_${count_invoices}">
                <td class="col-md-1">
                    <div class="rotate-15 text-center mt-4">
                        <label for="">Factura ${count_invoices}</label>
                    </div>                    
                </td>
                <td class="col-md-3">
                    <div class="form-group m-form__group"> 
                        <label>Fecha</label>
                        <input type="text" name="add_date_${count_invoices}" id="add_date_${count_invoices}" class="form-control dateAcc" placeholder="Fecha" required>
                    </div>
                </td>
                <td class="col-md-3"> 
                    <div class="form-group m-form__group"> 
                        <label>Monto</label>
                        <input type="number" name="add_amount_${count_invoices}" id="add_amount_${count_invoices}" class="form-control sum_amount" min="1" placeholder="Monto" required>
                    </div>
                </td>
                <td class="col-md-4">
                    <div class="form-group m-form__group"> 
                        <label>Detalle</label>
                        <input type="text" name="add_detail_${count_invoices}" id="add_detail_${count_invoices}" class="form-control" placeholder="Detalle" required>
                    </div>
                </td>
            <td align="center">
                <button type="button" name="remove" id="${count_invoices}" class="btn btn-danger btn-xs remove_invoice mt-4">x</button>
            </td>
        </tr>`;
        $('#invoices').append(output);
        $( `#add_date_${count_invoices}` ).rules( "add", { required: true });
        $( `#add_amount_${count_invoices}` ).rules( "add", { required: true });
        $( `#add_detail_${count_invoices}` ).rules( "add", { required: true });
        
    },
    events: function() {

        $("#btn_add_invoice").on("click", function(){
            count_invoices++;
            var value = $("#add_invoice_number").val() > 0 ? $("#add_invoice_number").val() : 0;
            var new_count = parseInt(value) + 1;         
            $("#add_invoice_number").val(new_count);
            accountabilityController.add_dynamic_input_field();
        });
        
        $(document).on('click', '.remove_invoice', function(){
            var row_id = $(this).attr("id");
            console.log("row : "+row_id)
            var count_invoice = $("#add_invoice_number").val() - 1;         
            $("#add_invoice_number").val(count_invoice);
            $('#row'+row_id).remove();
        });

        $(document).on('focusout', '.sum_amount', function(){
            var sum = 0;
            $('.sum_amount').each(function(){
                if($(this).val() > 0){
                    sum += parseFloat($(this).val());
                } else {
                    sum += 0;
                }
            });
            $("#add_yielded").val(sum);
        });

        $("#btn_newAccountability").click(function(event) {
            event.preventDefault();

            sessionStorage.setItem('create', 1);
            sessionStorage.setItem('edit', 0);
            count_invoices = 0;

            accountabilityController.clean();
            $('#invoices').html('');
            
            $('#modalCreateAccountability').modal({
                backdrop: 'static',
                keyboard: false
            });
            setTimeout(function(){
                var validator = $("#formCreateAccountability").validate();
                validator.resetForm();
                $("#formCreateAccountability input").removeClass('is-invalid');
                $( `#lista_beneficiarios` ).rules( "add", { required: true });
            },100);
        });

        $("#btn_saveAccountability").click(function(event){
            event.preventDefault();
            if ($("#formCreateAccountability").valid()) {
                var invoices_array = [];
                $( ".test" ).each(function( index ) {
                    var inputs_data = $(this).find(":input").serializeArray();
                    invoices_array.push(inputs_data);
                });
                var dt = {
                    method: 'saveNewAccountability',
                    id: $("#id_accountability").val() == '' ? 0 : $('#id_accountability').val(),
                    id_subvention: $("#id_subvention").val(),
                    add_name_represent: $("#add_name_represent").val(),
                    add_phone: $("#add_phone").val(),
                    add_email: $("#add_email").val(),
                    add_invoice_number: $("#add_invoice_number").val(),
                    invoices_array: invoices_array,                    
                    add_mount_delivered: $("#add_mount_delivered").val(),
                    add_yielded: $("#add_yielded").val(),
                    add_amount_refunded: $("#add_amount_refunded").val(),
                    add_balance: $("#add_balance").val(),
                    add_date_surrender_income: $("#add_date_surrender_income").val()
                };
                preloader("show");
                $.post(MODEL, dt,
                    function(data) {                      
                        if (data.code == 200) {
                            console.log(data)
                            uploadListaBeneficiarios(data.accountability_id, function (response) {
                                uploadComprobanteRestitucion(data.accountability_id, function (response) {
                                    uploadFotografias(data.accountability_id, function (response) {
                                        preloader("hide","Rendición de cuenta guardada con éxito..!",'success');
                                        dataTable.draw();
                                        $("#modalCreateAccountability").modal('hide');
                                    });
                                });
                            });
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

        $('#add_rut_organization').blur(function() {
            var parametros = {
                "method": "checkRut",
                "rut": $('#add_rut_organization').val(),
            };
            preloader('show');
            $.post(MODEL, parametros, function(data) {
                if (data.code == 204) {
                    $('#add_rut_organization').val('');
                    preloader("hide",data.message,'info');
                }
                if(data.code == 200){
                    $('#id_subvention').val(data.id_subvention);
                    $('#add_name_organization').val(data.name);
                    $('#add_phone').val(data.phone);
                    $('#add_email').val(data.email);
                    $('#add_name_project').val(data.name_proyect);
                    preloader("hide",data.message,'success');
                }
                if (data.code == 440) {
                    loginTimeout();
                }
            },'json');
        });

        $('#add_amount_refunded').blur(function() {
            var montoreintegrado = $(this).val();
            if (montoreintegrado > 0) {
                $('#span_amount_refunded').css('display', 'block');
                $( "#comprobante_restitucion_fondos" ).rules( "add", { required: true }); 
                $("#div_comprobante_restitucion_fondos").show(); 
            }         
            if (montoreintegrado == 0) {
                $('#span_amount_refunded').css('display', 'none');
                $( "#comprobante_restitucion_fondos" ).rules( "add", { required: false });
                $( "#comprobante_restitucion_fondos" ).removeClass( "is-invalid" );
                $("#div_comprobante_restitucion_fondos").hide();  
            }
            
            var montoentregado = $('#add_mount_delivered').val();    
            var montorendido = $('#add_yielded').val();   

            var balance = parseFloat(montoentregado) - (parseFloat(montorendido) + parseFloat(montoreintegrado));
            $('#add_balance').val(balance);   
        });

        //////////////////////////////////////////////////////////////////
        $('#accountabilityDataTable tbody').on('click', '.button_uploadDocumentation', function () {
            accountabilityController.clean();
            var $tr = $(this).closest('tr');
            var data = dataTable.row($(this).parents($tr)).data();
            var id = data[0];
             
            $('#titleAddDocumentation').html('Subir Archivos');
            $('#modalAddDocumentation').modal({
                backdrop: 'static',
                keyboard: false
            });
           $('#add_id_accountability').val(id);
           
        });

        //////////////////////////////////////////////////////////////////
        /*$('#accountabilityDataTable tbody').on('click', '.button_viewDocumentation', function () {
            var $tr = $(this).closest('tr');
            var data = dataTable.row($(this).parents($tr)).data();
            var id = data[0];
            console.log(data[0])
           accountabilityController.viewDocuments(id);
        });*/
        
        ///////////////////////////////////////////////////////////////////

        $("#btnSaveNewDocument").click(function(){
            if ($("#formUploadDocument").valid()) {
                var type = $("#select_type_documents").val();
                var id_accountability = $("#id_accountability_documents").val();
                var carpeta = '';
                var parametros = {
                    "method": "checkDocumentsFiles",
                    "id_accountability": id_accountability,
                    "type": type
                }
                preloader('show');
                $.post(MODEL, parametros, function(data) {
                    if (data.code == 200) {
                        if(type == 1) carpeta = 'listabeneficiarios';
                        if(type == 2) carpeta = 'comprobantes';
                        if(type == 3) carpeta = 'fotografias';
                        var storageRef = firebase.storage().ref();
                        var file = document.getElementById("input_upload_document").files;
                        if (file.length > 0) {
                            preloaderTwo("Guardando Documentos, por favor espere..!");
                            var storageRef = firebase.storage().ref();                            

                            $.each( file, function( key, value ) {
                                console.log('KEY: '+key)

                                var randonfunction = (min, max) => Math.floor(Math.random() * (max - min)) + min;
                                var numero = randonfunction(10000, 99999);

                                var ext = '.'+value.name.split('.').pop();
                                var new_name = id_accountability+'-'+key+'-'+numero+ext;
                                var path = carpeta+'/'+new_name;
                                var name_file = value.name;

                                var uploadTask = storageRef.child(path).put(file[key]);                                

                                uploadTask.on('state_changed', function(snapshot){
                                }, function(error) {
                                    console.log(error);
                                }, function() {
                                    uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                                        var parametros = {
                                            "method": "saveDocumentFiles",
                                            "id_accountability": id_accountability,
                                            "name": value.name,
                                            "path": path,
                                            "url": downloadURL,
                                            "type": type,
                                        };
                                        $.post(MODEL, parametros, function(data2) {
                                        },'json');

                                        if (key+1 === file.length) { ///terminó el ciclo each
                                            preloader("hide","El documento fue guardado con éxito",'success');
                                            dataTableDocuments.draw();
                                            $("#modalUploadDocuments").modal('hide');

                                        }
                                    });
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
        
        $("#uploadNewDocument").click(function(){
            $("#modalUploadDocuments").modal('show');
            $("#select_type_documents").val('');
            $("#input_upload_document").val('');
            setTimeout(function(){
                var validator = $("#formUploadDocument").validate();
                validator.resetForm();
            },100);
        });
    },
    edit: function(id) {
        event.preventDefault();

        sessionStorage.setItem('create', 0);
        sessionStorage.setItem('edit', 1);
        count_invoices = 100;
        
        accountabilityController.clean();
        var validator = $("#formCreateAccountability").validate();
        validator.resetForm();
        $("#formCreateAccountability input").removeClass('is-invalid');

        $( `#lista_beneficiarios` ).rules( "add", { required: false });

        var dt = { method: 'editAccountability', id: id };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {
                if (data.code == 200) {
                    // console.log(data)
                    preloader('hide');
                    $('#titleModelAccountability').html('Editar Rendición');

                    $("#id_accountability").val(data.id_accountability);

                    $('#add_rut_organization').val(data.rut);
                    $('#id_subvention').val(data.id_subvention);
                    $('#add_name_organization').val(data.name);
                    $('#add_name_project').val(data.name_proyect);
                    $("#add_name_represent").val(data.name_represent);
                    $("#add_phone").val(data.phone);
                    $("#add_email").val(data.email);
                    $("#add_invoice_number").val(data.number_invoices);
                    $("#add_mount_delivered").val(data.amount_delivered);
                    $("#add_yielded").val(data.amount_yielded);
                    $("#add_amount_refunded").val(data.amount_refunded);
                    if (data.amount_refunded > 0) {
                        $("#div_comprobante_restitucion_fondos").show(); 
                    }else {
                        $( "#comprobante_restitucion_fondos" ).removeClass( "is-invalid" );
                        $("#div_comprobante_restitucion_fondos").hide();  
                    }
                    $("#add_balance").val(data.balance);
                    $("#add_date_surrender_income").val(data.date_admission);                    
                    $(".previsualizar1").attr("src", data.beneficiarie_url);

                    $('#id_accountability_file').val(data.id_accountability_file);

                    //$('#lista_1').html('');
                    //$(`<div class="col-md-3 text-center"><img src="../assets/img/file.png" class="rounded img-fluid" style="height:150px;width:150px"><br><small>${data.beneficiarie_name}</small></div>`).appendTo('#lista_1');

                    $('#invoices').html('');
                    

                    var number_input_edit = 1;
                    $.each(data.invoices, function( index, value ) {
                        
                            $('#invoices').append(
                            `<tr id="row${number_input_edit}" class="test invoice_${number_input_edit}">
                               <td class="col-md-1">
                                  <div class="rotate-15 text-center mt-4">
                                     <label for="">Factura ${number_input_edit}</label>
                                  </div>
                               </td>
                               <td class="col-md-3">
                                  <div class="form-group m-form__group"> 
                                     <label>Fecha</label>
                                     <input type="text" name="add_date_${number_input_edit}" id="add_date_${number_input_edit}" class="form-control dateAcc" value="${value.date}" placeholder="Fecha" required="">
                                  </div>
                               </td>
                               <td class="col-md-3">
                                  <div class="form-group m-form__group"> 
                                     <label>Monto</label>
                                     <input type="number" name="add_amount_${number_input_edit}" id="add_amount_${number_input_edit}" class="form-control sum_amount" value="${value.amount}" min="1" placeholder="Monto" required="">
                                  </div>
                               </td>
                               <td class="col-md-4">
                                  <div class="form-group m-form__group"> 
                                     <label>Detalle</label>
                                     <input type="text" name="add_detail_${number_input_edit}" id="add_detail_${number_input_edit}" class="form-control" placeholder="Detalle" value="${value.detail}" required="">
                                  </div>
                               </td>
                               <td align="center">
                                  <button type="button" name="remove" id="${number_input_edit}" class="btn btn-danger btn-xs remove_invoice mt-4">x</button>
                               </td>
                            </tr>`);
                            number_input_edit++;
                        
                    });
                    
                    $('#modalCreateAccountability').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                }
                if(data.code == 204){
                    $("#modalCreateAccountability").modal("hide");
                    preloader("hide",data.message,'error');
                }
                if (data.code == 440) {
                    $("#modalCreateAccountability").modal("hide");
                    loginTimeout();
                }
            },
            "json"
        );
    },
    viewDocuments: function (id) {
        //accountabilityController.clean();
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
                    id: id
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

        $("#id_accountability_documents").val(id);
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
    clean: function() {
        $('#titleModelAccountability').html('Nueva Rendición');
        $("#id_accountability").val(0);

        $('#add_rut_organization').val('');
        $('#id_subvention').val('');
        $('#add_name_organization').val('');
        $('#add_name_project').val('');
        $("#add_name_represent").val("");
        $("#add_phone").val("");
        $("#add_email").val("");
        $("#add_invoice_number").val("");
        $("#add_mount_delivered").val("");
        $("#add_yielded").val("");
        $("#add_amount_refunded").val("");
        $("#add_balance").val("");
        $("#add_date_surrender_income").val("");

        $('#invoices').html('');
        $('#id_accountability_file').val(0);
        //
        $('.button-cargar').css('display', 'block');
        $('#btnSaveDocumentation').css('display', 'block');
        $('#lista').html('');
        //$('#lista_1').html('');
        //$('#lista_2').html('');
        
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
                    "method": "deleteDocumentsFile",
                    "id": id,
                };
                $.post(MODEL, parametros, function(data) {
                    console.log(data)
                    if (data.code == 200) {
                        if(data.path != ''){
                            var storageRef = firebase.storage().ref();
                            var desertRef = storageRef.child(data.path);
                            console.log(data.path)
                            desertRef.delete().then(function() {
                            }).catch(function(error) {
                            });
                        }
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
};

$(function() {
    accountabilityController.init();
    accountabilityController.events();
});

function uploadComprobanteRestitucion(id_accountability, callback) {
    file = document.getElementById("comprobante_restitucion_fondos").files;

    if (file.length > 0) {
        preloaderTwo("Guardando Comprobante de Restitucion, por favor espere..!");
        var storageRef = firebase.storage().ref();

        var ext = '.'+file[0].name.split('.').pop();
        var new_name = id_accountability+ext;
        var path = 'comprobantes/'+new_name;
        
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
                    "method": "saveDocumentFiles",
                    "id_accountability": id_accountability,
                    "name": file[0].name,
                    "path": path,
                    "url": downloadURL,
                    "type": 2,
                };
                $.post(MODEL, parametros, function(data2) {
                    // if(data2.path != ''){
                    //     var desertRef = storageRef.child(data2.path);
                    //     desertRef.delete().then(function() {
                    //     }).catch(function(error) {
                    //     });
                    // }
                    callback(1);
                },'json');

                        
            });
        });
        
    } else {
        callback(0);
    }
}

function uploadListaBeneficiarios(id_accountability, callback) {
    file = document.getElementById("lista_beneficiarios").files;

    if (file.length > 0) {
        preloaderTwo("Guardando Lista de Beneficiarios, por favor espere..!");
        var storageRef = firebase.storage().ref();

        var ext = '.'+file[0].name.split('.').pop();
        var new_name = id_accountability+ext;
        var path = 'listabeneficiarios/'+new_name;
        
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
                    "method": "saveDocumentFiles",
                    "id_accountability": id_accountability,
                    "name": file[0].name,
                    "path": path,
                    "url": downloadURL,
                    "type": 1,
                };
                $.post(MODEL, parametros, function(data2) {
                    // if(data2.path != ''){
                    //     var desertRef = storageRef.child(data2.path);
                    //     desertRef.delete().then(function() {
                    //     }).catch(function(error) {
                    //     });
                    // }
                    callback(1);
                },'json');

                        
            });
        });
        
    } else {
        callback(0);
    }
}

function uploadFotografias(id_accountability, callback) {
    var file = document.getElementById("accountability_photos").files;
    if (file.length > 0) {
        preloaderTwo("Guardando Fotografías de lo adquirido, por favor espere..!");
        var storageRef = firebase.storage().ref();                            

        $.each( file, function( key, value ) {
            console.log('KEY: '+key)

            var randonfunction = (min, max) => Math.floor(Math.random() * (max - min)) + min;
            var numero = randonfunction(10000, 99999);

            var ext = '.'+value.name.split('.').pop();
            var new_name = id_accountability+'-'+key+'-'+numero+ext;
            var path = 'fotografias/'+new_name;
            var name_file = value.name;

            var uploadTask = storageRef.child(path).put(file[key]);                                

            uploadTask.on('state_changed', function(snapshot){
            }, function(error) {
                console.log(error);
            }, function() {
                uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                    var parametros = {
                        "method": "saveDocumentFiles",
                        "id_accountability": id_accountability,
                        "name": value.name,
                        "path": path,
                        "url": downloadURL,
                        "type": 3,
                    };
                    $.post(MODEL, parametros, function(data2) {
                        /*if(data2.path != ''){
                            var desertRef = storageRef.child(data2.path);
                            desertRef.delete().then(function() {
                            }).catch(function(error) {
                            });
                        }*/
                    },'json');

                    if (key+1 === file.length) { ///terminó el ciclo each
                        callback(1);
                    }
                });
            });
        });
    } else {
        callback(0);
    }
}

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

        ///////////////////////////////////////////////////////////////////////
        /*$('.inputfile').each( function(){
            var $input   = $( this ),
                $label   = $input.next( 'label' ),
                labelVal = $label.html();

            $input.on( 'change', function( e )
            {
                var fileName = '';

                if( this.files && this.files.length > 1 )
                    fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length );
                else if( e.target.value )
                    fileName = e.target.value.split( '\\' ).pop();

                if( fileName )
                    $label.find('span').html( fileName );
                else
                    $label.html( labelVal );
            });

            // Firefox bug fix
            $input
            .on( 'focus', function(){ $input.addClass('has-focus'); })
            .on( 'blur', function(){ $input.removeClass('has-focus'); });
        }); */

         // Validar y Previsualizar la imagen 1
        /////////////////////////////////////////////////////////////
        /*$("#file-1").on('change', function () {
            if(this.files.length>1){
                Swal.fire('No pueden ser mas de 1 archivo');
            }
            else {
                //Get count of selected files
                var files = $(this)[0].files;

                var imgPath = $(this)[0].value;
                var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
                var div = $("#lista_1");
                var image_holder = $("#lista_1");
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
        });*/
        /*
        $("#accountability_photos").on('change', function () {
            if(this.files.length>10){
                Swal.fire('No pueden ser mas de 10 archivos');
            }
            else {
                //Get count of selected files
                var files = $(this)[0].files;

                var imgPath = $(this)[0].value;
                var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
                var div = $("#lista");
                var image_holder = $("#lista");
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
        });*/

        /////////////////////////////////////////////////////////////
        /*$("#comprobante_restitucion_fondos").on('change', function () {
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
        });*/