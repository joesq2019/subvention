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
                            preloader("hide",data.message,'success');
                            $("#modalCreateAccountability").modal('hide');
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
    },
};

$(function() {
    accountabilityController.init();
    accountabilityController.events();
});


document.addEventListener("DOMContentLoaded", function(event) {

    //CARGA DE FIREBASE

});