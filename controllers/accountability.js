var MODEL = '../models/accountability_model.php';
var dataTable = '';
var formCreateAccountability = $("#formCreateAccountability");
var count = 1, number_invoice = 0;

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
        
        $("#formCreateUser").validate({
            rules: {
                add_name_represent: {
                    required: true,
                    nombres: true
                },
                add_invoice_number: {
                    required: true
                },
                add_mount_delivered: {
                    required: true
                },
                add_yielded: {
                    required: true
                },
                add_amount_refunded: {
                    required: true,
                },
                add_balance: {
                    required: true
                },
                add_date_surrender_income: {
                    required: true
                }
            },
            messages: {
                add_name_represent: {
                    required: "El nombre es requerido.",
                },
                add_invoice_number: {
                    required: "El número de factura es requerido.",
                },
                add_mount_delivered: {
                    required: "El monto entregado es requerido.",
                },
                add_yielded: {
                    required: "El monto rendido es requerido.",
                },
                add_amount_refunded: {
                    required: "El monto reintegrado es requerido.",
                },
                add_balance: {
                    required: "El balance es requerido.",
                },
                add_date_surrender_income: {
                    required: "La fecha de rendicion es obligatoria.",
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
    add_dynamic_input_field: function(count, invoice_number)
    {
        var button = '';
        if(count > 1)
        {
            button = '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove mt-4">x</button>';
        }
        else
        {
            button = '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs mt-4">+</button>';
        }
        if (count <= invoice_number ) {
            output = `<tr id="row${count}" class="test invoice_${count}">
                    <td class="col-md-1">
                        <div class="rotate-15 text-center mt-4">
                            <label for="">Factura ${count}</label>
                        </div>                    
                    </td>
                    <td class="col-md-3">
                        <div class="form-group m-form__group"> 
                            <label>Fecha</label>
                            <input type="text" name="add_date_${count}" id="add_date" class="form-control dateAcc" placeholder="Fecha" required>
                        </div>
                    </td>
                    <td class="col-md-3"> 
                        <div class="form-group m-form__group"> 
                            <label>Monto</label>
                            <input type="number" name="add_amount_${count}" id="add_amount" class="form-control sum_amount" value="0" step="0.1" placeholder="Monto" required>
                        </div>
                    </td>
                    <td class="col-md-4">
                        <div class="form-group m-form__group"> 
                            <label>Detalle</label>
                            <input type="text" name="add_detail_${count}" id="add_detail" class="form-control" placeholder="Detalle" required>
                        </div>
                    </td>
                     
                <td align="center">${button}</td>
            </tr>`;
            $('#invoices').append(output);
        }
    },
    events: function() {

        $(document).on('click', '#add_more', function(){
            count = count + 1;            
            number_invoice = $("#add_invoice_number").val();
            accountabilityController.add_dynamic_input_field(count, number_invoice);
        });

        $(document).on('click', '.remove', function(){
            var row_id = $(this).attr("id");
            count = count - 1;
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


        $("#add_invoice_number").change(function(){
            number_invoice = $("#add_invoice_number").val();
            $("#add_yielded").val(0);            
            accountabilityController.add_dynamic_input_field(1,number_invoice);        
        });



        //ESTO ES
        // var invoices_array = [];
        // $( ".test" ).each(function( index ) {
        //     var inputs_data = $(this).find(":input").serializeArray();
        //     invoices_array.push(inputs_data);
        // });
        // console.log(invoices_array)
        //ESTO ES

        $("#btn_newAccountability").click(function(event) {
            event.preventDefault();
            accountabilityController.clean();
            $('#invoices').html('');
            
            $('#modalCreateAccountability').modal({
                backdrop: 'static',
                keyboard: false
            });
                 
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
                            var file = document.getElementById("file-1").files;
                            if (file.length > 0) {
                                var dt = new Date();
                                var time = dt.getHours() + "" + dt.getMinutes() + "" + dt.getSeconds() + "" + dt.getDay() + "" + dt.getMonth() + "" + dt.getYear();
                                preloaderTwo("Guardando documentos del beneficiarios, por favor espere..!");
                                var storageRef = firebase.storage().ref();                                    
                                var id_accountability = data.accountability_id;
                                var ext = '.'+file[0].name.split('.').pop();
                                var new_name = id_accountability+'_'+time+'_'+ext;
                                var path = 'beneficiaries/'+new_name;
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
                                            "method": 'updateFileBeneficiarie',
                                            "id_accountability": id_accountability,
                                            "name": name_file,
                                            "path": path,
                                            "url": downloadURL
                                            },function(response) {
                                                if (response.code == 440) {
                                                    loginTimeout(response.message);
                                                    return;
                                                } else if (response.code == 200) {
                                                    // preloader("hide","La rendición de cuenta y la lista de beneficiarios fue guardada con éxito..!","success");
                                                    preloader("hide",data.message,'success');
                                                    $("#modalCreateAccountability").modal('hide');
                                                    dataTable.ajax.reload();
                                                } else if (response.code == 204) {    
                                                    storageRef.child(path).delete();                         
                                                    preloader('hide', response.message, 'error');
                                                }
                                        }, "json");                                    
                                         
                                    });
                                });
                            }else{
                                // preloader("hide","La rendición de cuenta fue guardada con éxito..!","success");
                                preloader("hide",data.message,'success');
                                $("#modalCreateAccountability").modal('hide');
                                dataTable.ajax.reload(); 
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
            if (montoreintegrado > 0) $('#span_amount_refunded').css('display', 'block');          
            if (montoreintegrado == 0) $('#span_amount_refunded').css('display', 'none'); 

            var montoentregado = $('#add_mount_delivered').val();    
            var montorendido = $('#add_yielded').val();   

            var balance = parseFloat(montoentregado) - (parseFloat(montorendido) + parseFloat(montoreintegrado));
            $('#add_balance').val(balance);   
        });

        ///////////////////////////////////////////////////////////////////////
        $('.inputfile').each( function(){
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
        }); 

         // Validar y Previsualizar la imagen 1
        $("#file-1").change(function(event) { //validar que sea imagen
            console.log('imagen change');
            var fileInput = document.getElementById('file-1');
            var imagen = this.files[0];
            var filePath = fileInput.value;
            var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
            if (!allowedExtensions.exec(filePath)) {
                Swal.fire("Ups..", 'Sube un archivo que tenga extensiones .jpeg/.jpg/.png/.gif Only.', "warning");
                $("#imagen1").val('');
            }
            var datosImagen = new FileReader();
            datosImagen.readAsDataURL(imagen);
            $(datosImagen).on("load", function(event) {
                var rutaImagen = event.target.result;
                $(".previsualizar1").attr("src", rutaImagen);
            });
        });

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
        });

        /////////////////////////////////////////////////////////////
        $("#comprobante_restitucion_fondos").on('change', function () {
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
        $('#accountabilityDataTable tbody').on('click', '.button_viewDocumentation', function () {
            var $tr = $(this).closest('tr');
            var data = dataTable.row($(this).parents($tr)).data();
            var id = data[0];
            console.log(data[0])
           accountabilityController.viewDocuments(id);
           
        });
        

        ///////////////////////////////////////////////////////////////////
        $('#btnSaveDocumentation').click(function (e) {
            e.preventDefault();
            var isset = 0;
            var id_accountability = $('#add_id_accountability').val();
            var file = document.getElementById("accountability_photos").files;
            uploadDocuments(id_accountability, function (response) {
                if (file.length > 0) {                    
                    preloaderTwo("Guardando documentos del beneficiarios, por favor espere..!");
                    var storageRef = firebase.storage().ref();                                          
                    $.each(file, function( key, value ) {
                        console.log('KEY: '+key)
                        var dt = new Date();
                        var time = dt.getHours() + "" + dt.getMinutes() + "" + dt.getSeconds() + "" + dt.getDay() + "" + dt.getMonth() + "" + dt.getYear();
                        var ext = '.'+value.name.split('.').pop();
                        var new_name = id_accountability+'-'+time+'_'+key+ext;
                        var path = 'photos/'+new_name;
                        var name_file = value.name;

                        var uploadTask = storageRef.child(path).put(file[key]);                                

                        uploadTask.on('state_changed', function(snapshot){
                        }, function(error) {
                            console.log(error);
                        }, function() {
                            uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                                var parametros = {
                                    "method": "saveImagesFiles",
                                    "id_accountability": id_accountability,
                                    "name": name_file,
                                    "path": path,
                                    "url": downloadURL,
                                    "type": 3,
                                };
                                $.post(MODEL, parametros, function(data2) {
                                    if(data2.path != ''){
                                        var desertRef = storageRef.child(data2.path);
                                        desertRef.delete().then(function() {
                                        }).catch(function(error) {
                                        });
                                    }
                                },'json');

                                if (key+1 === file.length) { ///terminó el ciclo each
                                    preloader("hide","La subvencion y los documentos fueron guardados con éxito..!","success");
                                    $("#modalAddDocumentation").modal('hide');
                                    dataTable.ajax.reload();
                                }
                            });
                        });
                    });
                }
                else{
                    if(response == 1){
                        preloader("hide","Las fotografias y el comprobante fueron guardados con éxito..!","success");
                        $("#modalAddDocumentation").modal('hide');
                        dataTable.ajax.reload(); 
                    } else {
                        preloader("hide","El comprobante fue guardado con éxito..!","success");
                        $("#modalAddDocumentation").modal('hide');
                        dataTable.ajax.reload(); 
                    }
                }    
            });
        });
        
    },
    edit: function(id) {
        event.preventDefault();
        accountabilityController.clean();
        
        var dt = { method: 'editAccountability', id: id };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {
                if (data.code == 200) {
                    preloader('hide');
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
                    $("#add_balance").val(data.balance);
                    $("#add_date_surrender_income").val(data.date_admission);
                    $('#invoices').html(data.invoices);
                    $(".previsualizar1").attr("src", data.beneficiarie_url);
                    $('#titleModelAccountability').html('Editar Rendición');
                    
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
        accountabilityController.clean();
        
        var dt = { method: 'viewDocuments', id: id };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {
                if (data.code == 200) {
                    preloader('hide');
                    $("#id_accountability").val(data.id_accountability);
                    $('.button-cargar').css('display', 'none');
                    $('#btnSaveDocumentation').css('display', 'none');
                    $('#lista').html(data.images);
                    $('#lista_2').html(data.documents);                    

                    $('#titleAddDocumentation').html('Ver documentos Rendición');
                    
                     $('#modalAddDocumentation').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                }
                if(data.code == 204){
                    $("#modalAddDocumentation").modal("hide");
                    preloader("hide",data.message,'error');
                }
                if (data.code == 440) {
                    $("#modalAddDocumentation").modal("hide");
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

        //
        $('.button-cargar').css('display', 'block');
        $('#btnSaveDocumentation').css('display', 'block');
        $('#lista').html('');
        $('#lista_2').html('');
    }
};

$(function() {
    accountabilityController.init();
    accountabilityController.events();
});

function uploadDocuments(id_accountability, callback) {
    var isset = 0;
    $(".input_file").each(function( index, value ) {
        console.log(value)
        if(value.files[0] !== undefined){
            isset++;
        }
    });
    // console.log(isset)
    if (isset > 0) {
        preloaderTwo("Guardando documentos, por favor espere..!");
        var storageRef = firebase.storage().ref();
        var count = 0;
        $(".input_file").each(function( index, value ) {
            if(value.files[0] !== undefined){
                var dt = new Date();
                var time = dt.getHours() + "" + dt.getMinutes() + "" + dt.getSeconds() + "" + dt.getDay() + "" + dt.getMonth() + "" + dt.getYear();
                var ext = '.'+value.files[0].name.split('.').pop();
                var new_name = id_accountability+'_'+time+'_'+ext;
                var path = 'comprobantes/'+new_name;
                
                var uploadTask = storageRef.child(path).put(value.files[0]);
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
                            "related": value.id,
                            "name": value.files[0].name,
                            "path": path,
                            "url": downloadURL,
                            "type": 2,
                        };
                        $.post(MODEL, parametros, function(data2) {
                            if(data2.path != ''){
                                var desertRef = storageRef.child(data2.path);
                                desertRef.delete().then(function() {
                                }).catch(function(error) {
                                });
                            }
                        },'json');

                        count++;

                        if (count === isset) { ///terminó el ciclo each
                            callback(1);
                        }                                  
                    });
                });
            }
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