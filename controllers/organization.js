var MODEL = '../models/organization_model.php';
var dataTable = '';
var dataTableDocuments = '';

var organizationController = {
    init: () => {
        
        dataTable = $('#organizationDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "organizationList"
                },
                type: "post",
                error: function() {}
            },
            "order": [
                [0, "asc"]
            ],
            columnDefs: [
                {
                    targets: [0],
                    visible: false,
                },
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

        jQuery.validator.addMethod("archivo", function(value, element) {
            return this.optional(element) || /^.+\.(([pP][dD][fF])|([jJ][pP][gG])|([pP][nN][gG])|([jJ][pP][eE][gG]))$/.test(value);
        }, "Solo formatos png, jpg, jpeg o pdf");     

        
        //preloader('show');
    },
    events: function() {

        $("#select_action").change(function(){
            $("#add_file_refund").val("");
            $("#textarea_reason").val("");

            if($("#select_action").val() == 3){
                $( "#add_file_refund" ).rules( "add", { required: true });
                $(".div_refund").show();
            } else {
                $( "#add_file_refund" ).rules( "add", { required: false });
                $(".div_refund").hide();
            }
        });

        $("#btnSaveAction").click(function(){
            if ($("#formAction").valid()) {
                var accountability_id = $("#id_accountability_action").val();
                var parametros = {
                    "method": "updateAccountabilityStatus",
                    "accountability_id": accountability_id,
                    "status": $("#select_action").val(),
                    "reason": $("#textarea_reason").val()
                }
                uploadRefund(accountability_id, function (response) {
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
                });
            }
        });

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
            accountabilityController.clean();

            $("#add_num_folio").prop("readonly",false);

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

        //$('#add_rut_organization').focusout(function() {
        $('#add_num_folio').focusout(function() {
            if($('#add_num_folio').val() != ""){
                var parametros = {
                    "method": "checkNumFolio",
                    "num_folio": $('#add_num_folio').val(),
                };
                //preloader('show');
                $.post(MODEL, parametros, function(data) {
                    if (data.code == 204) {
                        accountabilityController.clean();
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
            }
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
        accountabilityController.clean();

        $("#add_num_folio").prop("readonly",true); 

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
                    $("#add_num_folio").val(data.id_subvention);
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
    approve: function(id) {
        event.preventDefault();
        Swal.fire({
            title: "Estas seguro de realizar esta accion?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, aprobar!",
            cancelButtonText: 'Cancelar!',
        }).then(result => {
            if (result.value) {
                preloader("show");
                var parametros = {
                    "method": "approveAccountability",
                    "id": id,
                };
                $.post(MODEL, parametros, function(data) {
                    console.log(data)
                    if (data.code == 200) {
                        preloader("hide",data.message,'success');
                        dataTable.draw();
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
        $('#titleModelAccountability').html('Nueva Rendición');
        $("#id_accountability").val(0);

        $("#add_num_folio").val("");

        //files
        $("#lista_beneficiarios").val("");
        $("#accountability_photos").val("");
        $("#comprobante_restitucion_fondos").val("");

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
                            //
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
    actions: function(id){
        $("#id_accountability_action").val('');
        $("#select_action").val('');
        $("#textarea_reason").val('');

        $("#id_approval_subvention_id").val('');
        $("#add_no_mayor_decree").val('');
        $("#add_agreement_date").val('');
        $("#add_no_payment_decree").val('');
        $("#add_payment_date").val('');
        $("#add_no_payment_installments").val(''); 

        $(".div_reason").show();
        $(".div_refund").hide();

        $('.form-control').removeClass('is-invalid');

        setTimeout(function(){
            var validator = $("#formAction").validate();
            validator.resetForm();
            $('#formAction .form-control').removeClass('is-invalid');

            $("#id_accountability_action").val(id);
        },100);

        $("#modalActions").modal('show');
    },
    certificate: function(id){
        event.preventDefault();
        Swal.fire({
            title: "Deseas generar el certificado?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, Hazlo!",
            cancelButtonText: 'Cancelar!',
        }).then(result => {
            if (result.value) {
                window.location.href = '../models/certificado.php?id='+id;
            }
        });
    }
};

$(function() {
    organizationController.init();
    organizationController.events();
});

document.addEventListener("DOMContentLoaded", function(event) {
});