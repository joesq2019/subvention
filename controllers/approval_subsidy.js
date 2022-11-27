var MODEL = '../models/approval_subsidy_model.php';
var dataTable = '';
var formCreateApprovalSubsidy = $("#formCreateApprovalSubsidy");

var approvalSubsidyController = {
    init: () => {

        dataTable = $('#approvalSubsidyDataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": !0,
            "ajax": {
                url: MODEL, // json datasource
                data: {
                    method: "approvalSubsidyList"
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
        
        $("#formCreateBudgetInformation").validate({
            rules: {
                add_no_mayor_decree: {
                    required: true
                },
                add_agreement_date: {
                    required: true
                }
            },
            messages: {
                add_no_mayor_decree: {
                    required: "El numero de certificado es requerido.",
                },
                add_agreement_date: {
                    required: "La fecha de convenio es requerida.",
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
        $("#btn_newApprovalSubsidy").click(function(event) {
            event.preventDefault();
            approvalSubsidyController.clean();
            $("#modalCreateApprovalSubsidy").modal('show');
        });      

        /////////////////////////////////////////////////////////////
        $('#approvalSubsidyDataTable tbody').on('click', '.button_edit', function () {
            var $tr = $(this).closest('tr');
            var data = dataTable.row($(this).parents($tr)).data();
            var id = data[0];
            approvalSubsidyController.edit(id)
        });

        ////////////////////////////////////////////////////////////
        $('#approvalSubsidyDataTable tbody').on('click', '.button_delete', function () {
            var $tr = $(this).closest('tr');
            var data = dataTable.row($(this).parents($tr)).data();
            var id = data[0];
            approvalSubsidyController.delete(id)
        });
    },
    edit: function(id) {
        event.preventDefault();
        approvalSubsidyController.clean();
        
        var dt = { method: 'editApprovalSubsidy', id: id };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {
                if (data.code == 200) {
                    preloader('hide');
                   
                    $("#id_approval_subsidy").val(data.id_approval_subsidy);
                    $("#add_no_mayor_decree").val(data.add_no_mayor_decree);
                    $("#add_agreement_date").val(data.add_agreement_date);
                    $("#add_no_payment_decree").val(data.add_no_payment_decree);
                    $('#add_payment_date').val(data.add_payment_date);
                    $('#add_no_payment_installments').val(data.add_no_payment_installments);
                    
                    $("#modalCreateApprovalSubsidy").modal("show");
                }
                if(data.code == 204){
                    $("#modalCreateApprovalSubsidy").modal("hide");
                    preloader("hide",data.message,'error');
                }
                if (data.code == 440) {
                    $("#modalCreateApprovalSubsidy").modal("hide");
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
                    "method": "delApprovalSubsidy",
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
 
        $("#id_approval_subsidy").val(0);
        $("#add_no_mayor_decree").val('');
        $("#add_agreement_date").val('');
        $("#add_no_payment_decree").val('');
        $('#add_payment_date').val('');
        $('#add_no_payment_installments').val('');
    },
};

$(function() {
    approvalSubsidyController.init();
    approvalSubsidyController.events();
});

document.addEventListener("DOMContentLoaded", function(event) {

});