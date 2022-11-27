var MODEL = '../models/subvention_model.php';
var dataTable = '';
var action_subvention = sessionStorage.getItem('action');
var id_subvention = sessionStorage.getItem('id_subvention');

$(function() {
    subventionViewController.init();
    //subventionViewController.view();
});


var subventionViewController = {
	init: () => {

		if (action_subvention == 2) {
            subventionViewController.view(id_subvention)
        }

        $('.btn-next-1').click(function() {                
            $('.nav-pills > .active').next('a').trigger('click');
            $('.b-1').removeClass('disabled');
        });

        $('.btn-next-2').click(function() {  
            $('.nav-pills > .active').next('a').trigger('click');
            $('.b-2').removeClass('disabled');            
        });

        $('.btn-next-3').click(function() {                
            $('.nav-pills > .active').next('a').trigger('click');
            $('.b-2').removeClass('disabled');            
        });

        $('.btn-next-4').click(function() { 
            $('.nav-pills > .active').next('a').trigger('click');
            $('.b-3').removeClass('disabled');
        });

        $('.btnPrevious').click(function() {
            $('.inner_form').prop('required',false);
            $('.nav-pills > .active').prev('a').trigger('click');
        });
	},
	view: function(id) {      
        //console.log("awdawd")
        $('#id_subvention').val(id);
        var parametros = {
            "method": "findSubvention",
            "id": id
        };
        $.post(MODEL, parametros, function(data) {
            console.log(data)
            $('#id_organitation').val(data.subvention_data.id_organitation);

            //paso 1
            $("#inputYearSubvention").val(data.subvention_data.year);
            $("#inputOrgName").val(data.subvention_data.name);
            $("#inputOrgRut").val(data.subvention_data.name);
            $("#inputAddress").val(data.subvention_data.address);
            $("#inputEmailAddress").val(data.subvention_data.email);
            $("#inputPhone").val(data.subvention_data.phone);
            $("#inputProName").val(data.subvention_data.name_proyect);
            $("#inputProObj").val(data.subvention_data.objetive_proyect);
            //paso 1

            //paso 2
            $("#id_presidente").val(data.members_data[0].id);
            $("#inputRepreName1").val(data.members_data[0].name);
            $("#inputRepreRut1").val(data.members_data[0].rut);
            $("#inputRepreAddress1").val(data.members_data[0].address);
            $("#inputReprePhone1").val(data.members_data[0].phone);

            $("#id_vicepresidente").val(data.members_data[1].id);
            $("#inputRepreName2").val(data.members_data[1].name);
            $("#inputRepreRut2").val(data.members_data[1].rut);
            $("#inputRepreAddress2").val(data.members_data[1].address);
            $("#inputReprePhone2").val(data.members_data[1].phone);

            $("#id_secretario").val(data.members_data[2].id);
            $("#inputRepreName3").val(data.members_data[2].name);
            $("#inputRepreRut3").val(data.members_data[2].rut);
            $("#inputRepreAddress3").val(data.members_data[2].address);
            $("#inputReprePhone3").val(data.members_data[2].phone);
            
            $("#id_tesorero").val(data.members_data[3].id);
            $("#inputRepreName4").val(data.members_data[3].name);
            $("#inputRepreRut4").val(data.members_data[3].rut);
            $("#inputRepreAddress4").val(data.members_data[3].address);
            $("#inputReprePhone4").val(data.members_data[3].phone);
            //paso 2

            //paso 3
            var image_holder = $("#listaEdit");
            $.each(data.financing_files_data, function( index, value ) {
                $(`<div class="col-md-2 text-center"><i class="fas fa-trash-alt delete_financing_file" title="Eliminar archivo" data-id="${value.id}" style="font-size: 25px; cursor: pointer; color: black; position: relative; top: -30px; left: 85px;"></i><img src="../assets/img/file.png" class="rounded img-fluid" style="height:75px;width:75px"><br><small>${value.name}</small></div>`).appendTo(image_holder);
            });

            $("#inputDetails_1").val(data.financing_details_data[0].details);
            $("#inputUnityPrice_1").val(data.financing_details_data[0].unit_price);
            $("#inputQuantity_1").val(data.financing_details_data[0].quantity);
            $("#inputTotalPrice_1").val(data.financing_details_data[0].total_price);

            $("#inputCantCompras").val(data.subvention_data.quantity_purchases);
            $("#inputOrgAmount").val(data.subvention_data.organization_contribution);

            $("#inputDirectAmount").val(data.subvention_data.amount_direct);
            $("#inputIndirectAmount").val(data.subvention_data.amount_indirect);
            $("#inputTotalSumBene").val(data.subvention_data.total_beneficiaries);

            var number_input = 2;
            $.each(data.financing_details_data, function( index, value ) {
                if(index > 0){
                    $('#dynamic_field').append(
                    `<tr id="row${number_input}" class="dynamic_field">
                    <td>
                        <input type="text" name="inputDetails_${number_input}" id="inputDetails_${number_input}" placeholder="Detalle inversión 1" class="form-control inner_form detalle_list" value="${value.details}" disabled/>
                    </td>
                    <td>
                        <input type="number" name="inputUnityPrice_${number_input}" id="inputUnityPrice_${number_input}" data-op="${number_input}" placeholder="Precio unitario" class="form-control inner_form"  value="${value.unit_price}" disabled/>
                    </td>
                    <td>
                        <input type="number" name="inputQuantity_${number_input}" id="inputQuantity_${number_input}" data-op="${number_input}" placeholder="Cantidad" class="form-control inner_form quantity_list"  value="${value.quantity}" disabled/>
                    </td>
                    <td>
                        <input type="text" name="inputTotalPrice_${number_input}" id="inputTotalPrice_${number_input}" data-op="${number_input}" placeholder="Precio Total" class="form-control inner_form total_price_list" readonly="" value="${value.total_price}" />
                    </td>
                    <td>
                    </td>
                    </tr>`);
                    number_input++;
                }
            });

            var inputs = $(".total_price_list");
            var tot = 0;
            for(var i = 0; i < inputs.length; i++){
                tot += parseFloat($(inputs[i]).val());                
            }
            $('#inputTotalSumPrice').val(tot);

                     
            //paso 3


            //paso 4
            $("#inputCantActivities").val(data.subvention_data.quantity_activities);

            $("#inputActivity_1").val(data.schedule_data[0].activities);
            $("#inputMonthAct_1").val(data.schedule_data[0].month);

            var number_input_schedule = 2;
            $.each(data.schedule_data, function( index, value ) {
                if(index > 0){
                    $('#dynamic_activities').append(
                    `<tr id="row${number_input_schedule}" class="dynamic_activities">
                    <td class="col-md-8">
                        <input type="text" name="inputActivity_${number_input_schedule}" id="inputActivity_${number_input_schedule}" data-op="${number_input_schedule}" placeholder="Descripción de actividad" class="form-control inner_form activity_list" value="${value.activities}" disabled/>
                    </td>
                    <td>
                        <input type="text" name="inputMonthAct_${number_input_schedule}" id="inputMonthAct_${number_input_schedule}" data-op="${number_input_schedule}" placeholder="Mes" class="form-control inner_form month_list" value="${value.month}" disabled/>
                    </td>
                    <td>
                    </td>
                    </tr>`);
                    number_input_schedule++;
                }
            });
            $(".month_list").datepicker( {
                format: "mm-yyyy",
                startView: "months", 
                minViewMode: "months"
            }); 
            //paso 4
            
            //paso 5
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
        },'json');
    }
	

}

