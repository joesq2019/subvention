var MODEL = '../models/subvention_model.php';
var dataTable = '';
var formCreateSubvention = $("#formCreateSubvention");

var subventionAddController = {
    init: () => {
      
        // jQuery.validator.addMethod("alphanumeric", function(value, element) {
        //     return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
        // }, "Solo letras y números");

        jQuery.validator.addMethod("telefono", function(value, element) {
            return this.optional(element) || /^[0-9+-\s]*$/.test(value);
        }, "Solo números, espacios y guiones");

        // jQuery.validator.addMethod("nombres", function(value, element) {
        //     return this.optional(element) || /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/.test(value);
        // }, "Solo letras");        

        // jQuery.validator.addMethod("alphaespacios", function(value, element) {
        //     return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
        // }, "Solo letras y espacios");

        jQuery.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || /^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(value);
        }, "Instroduzca un rut válido");        
        
        $("#formCreateSubvention").validate({
            rules: {
                inputOrgRut: {
                    required: true,
                    rut: true
                }
            },
            messages: {
                inputOrgRut: {
                    required: "El rut es requerido.",
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
         $(".month_list").datepicker( {
            format: "mm-yyyy",
            startView: "months", 
            minViewMode: "months"
        });
        //Para agregar required cuando se escriba en el campo
        $("#inputRepreName1").keyup(function(){$("#inputRepreAddress1").prop("required",!0),$("#inputReprePhone1").prop("required",!0)});
        $("#inputRepreName2").keyup(function(){$("#inputRepreAddress2").prop("required",!0),$("#inputReprePhone2").prop("required",!0)});
        $("#inputRepreName3").keyup(function(){$("#inputRepreAddress3").prop("required",!0),$("#inputReprePhone3").prop("required",!0)});
        $("#inputRepreName4").keyup(function(){$("#inputRepreAddress4").prop("required",!0),$("#inputReprePhone4").prop("required",!0)});
        //preloader('show');
    },
    events: function() {
        //Wizard
        $('.btn-next-1').click(function() {   
            var fail = false, fail_log = '', name = '', $div_alert = '';
            $('#wizard1-5556').find('select, textarea, input').each(function() {
                if (!$(this).prop('required')) {
                } else {
                    if (!$(this).val()) {
                        id_input = $(this).attr('id');
                        var $label = $("label[for='" + id_input + "']").text();
                        fail = true;
                        name = $(this).attr('name');
                        fail_log += $label + " is required \n";
                        $('input:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                        $('select:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                    } else {
                        $('input:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                        $('select:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                    }
                }
            });
            if (!fail) {
                $('.nav-pills > .active').next('a').trigger('click');
                $('.b-1').removeClass('disabled');               
            } else {
                $('.b-1').addClass('disabled');
                if ($('.alert-global').length) {
                    console.log("ya exite")
                } else {
                    $div_alert = '<div class="alert alert-danger alert-global" role="alert">' +
                        '<strong>Este campo es requerido.</strong>' +
                        '</div>';
                    $('input:required:invalid').after($div_alert);
                    $('select:required:invalid').after($div_alert);
                    $(".alert-global").fadeTo(4000, 700).slideUp(700, function() {
                        $(".alert-global").slideUp(700);
                        $(".alert-global").remove();
                    });
                }
            }
        });

        $('.btn-next-2').click(function() {   
            var fail = false, fail_log = '', name = '', $div_alert = '';
            $('#wizard2').find('select, textarea, input').each(function() {
                if (!$(this).prop('required')) {
                } else {
                    if (!$(this).val()) {
                        id_input = $(this).attr('id');
                        var $label = $("label[for='" + id_input + "']").text();
                        fail = true;
                        name = $(this).attr('name');
                        fail_log += $label + " is required \n";
                        $('input:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                        $('select:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                    } else {
                        $('input:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                        $('select:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                    }
                }
            });
            if (!fail) {
                $('.nav-pills > .active').next('a').trigger('click');
                $('.b-2').removeClass('disabled');
            } else {
                if ($('.alert-global').length) {
                    console.log("ya exite")
                } else {
                    $('.b-2').addClass('disabled');
                    $div_alert = '<div class="alert alert-danger alert-global" role="alert">' +
                        '<strong>Este campo es requerido.</strong>' +
                        '</div>';
                    $('input:required:invalid').after($div_alert);
                    $('select:required:invalid').after($div_alert);
                    $(".alert-global").fadeTo(4000, 700).slideUp(700, function() {
                        $(".alert-global").slideUp(700);
                        $(".alert-global").remove();
                    });
                }
            }
        });

        $('.btn-next-3').click(function() {   
            var fail = false, fail_log = '', name = '', $div_alert = '';
            $('#wizard3').find('select, textarea, input').each(function() {
                if (!$(this).prop('required')) {
                } else {
                    if (!$(this).val()) {
                        id_input = $(this).attr('id');
                        var $label = $("label[for='" + id_input + "']").text();
                        fail = true;
                        name = $(this).attr('name');
                        fail_log += $label + " is required \n";
                        $('input:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                        $('select:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                    } else {
                        $('input:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                        $('select:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                    }
                }
            });
            if (!fail) {
                $('.nav-pills > .active').next('a').trigger('click');
                $('.b-2').removeClass('disabled');
            } else {
                if ($('.alert-global').length) {
                    console.log("ya exite")
                } else {
                    $('.b-2').addClass('disabled');
                    $div_alert = '<div class="alert alert-danger alert-global" role="alert">' +
                        '<strong>Este campo es requerido.</strong>' +
                        '</div>';
                    $('input:required:invalid').after($div_alert);
                    $('select:required:invalid').after($div_alert);
                    $(".alert-global").fadeTo(4000, 700).slideUp(700, function() {
                        $(".alert-global").slideUp(700);
                        $(".alert-global").remove();
                    });
                }
            }
        });

         $('.btn-next-4').click(function() {   
            var fail = false, fail_log = '', name = '', $div_alert = '';
            $('#wizard4').find('select, textarea, input').each(function() {
                if (!$(this).prop('required')) {
                } else {
                    if (!$(this).val()) {
                        id_input = $(this).attr('id');
                        var $label = $("label[for='" + id_input + "']").text();
                        fail = true;
                        name = $(this).attr('name');
                        fail_log += $label + " is required \n";
                        $('input:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                        $('select:focus:invalid').css({
                            'background': '#fff url(../assets/img/invalid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #d45252',
                            'border-color': '#b03535'
                        });
                    } else {
                        $('input:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                        $('select:required:valid').css({
                            'background': '#fff url(../assets/img/valid.png) no-repeat 98% center',
                            'box-shadow': '0 0 5px #5cd053',
                            'border-color': '#28921f'
                        });
                    }
                }
            });
            if (!fail) {
                $('.nav-pills > .active').next('a').trigger('click');
                $('.b-3').removeClass('disabled');
            } else {
                if ($('.alert-global').length) {
                    console.log("ya exite")
                } else {
                    $('.b-3').addClass('disabled');
                    $div_alert = '<div class="alert alert-danger alert-global" role="alert">' +
                        '<strong>Este campo es requerido.</strong>' +
                        '</div>';
                    $('input:required:invalid').after($div_alert);
                    $('select:required:invalid').after($div_alert);
                    $(".alert-global").fadeTo(4000, 700).slideUp(700, function() {
                        $(".alert-global").slideUp(700);
                        $(".alert-global").remove();
                    });
                }
            }
        });
        $('.btnPrevious').click(function() {
            $('.inner_form').prop('required',false);
            $('.nav-pills > .active').prev('a').trigger('click');
        });

        $("#imagenes").on('change', function () {
            if(this.files.length>5){
                Swal.fire('No pueden ser mas de 5 imagenes');
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

        var i=1;
        $('#add').click(function(){
            i++;
            $('#dynamic_field').append(
                `<tr id="row${i}">
                <td><input type="text" name="inputDetails_${i}" id="inputDetails_${i}" placeholder="Detalle inversión 1" class="form-control inner_form detalle_list" /></td>
                <td><input type="number" name="inputUnityPrice_${i}" id="inputUnityPrice_${i}" data-op="${i}" placeholder="Precio unitario" class="form-control inner_form " /></td>
                <td><input type="number" name="inputQuantity_${i}" id="inputQuantity_${i}" data-op="${i}" placeholder="Cantidad" class="form-control inner_form quantity_list" /></td>
                <td><input type="text" name="inputTotalPrice_${i}" id="inputTotalPrice_${i}" data-op="${i}" placeholder="Precio Total" class="form-control inner_form total_price_list" readonly="" /></td>
                <td><button type="button" name="remove" id="${i}" class="btn btn-danger btn_remove">X</button></td>
                </tr>`);           
        });
        
        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id"); 
            $('#row'+button_id+'').remove();
        });

        //calculo
        $(document).on('focusout', '.quantity_list', function(){
            var op = $(this).attr("data-op");
            var precio_unitario = $('#inputUnityPrice_'+op).val();
            var cantidad = $(this).val();        

            var total_list = parseFloat(precio_unitario) * parseInt(cantidad);
            //console.log(total_list)
            $('#inputTotalPrice_'+op).val(total_list);

            var inputs = $(".total_price_list");
            var tot = 0;
            for(var i = 0; i < inputs.length; i++){
                tot += parseFloat($(inputs[i]).val());                
            }
            $('#inputTotalSumPrice').val(tot);
            console.log(tot);
        });
 
        $(document).on('focusout', '#inputDirectAmount', function(){             
            var directos = $(this).val(); 
            var indirectos = $('#inputIndirectAmount').val();       

            var total_suma = parseFloat(directos) + parseFloat(indirectos);
            $('#inputTotalSumBene').val(total_suma);
        });

         $(document).on('focusout', '#inputIndirectAmount', function(){             
            var directos = $('#inputDirectAmount').val();
            var indirectos = $(this).val();    

            var total_suma = parseFloat(directos) + parseFloat(indirectos);
            $('#inputTotalSumBene').val(total_suma);
        });
        var j = 0;
        $('#add_act').click(function(){
            j++;
            $('#dynamic_activities').append(
                `<tr id="row${j}">
                <td class="col-md-8"><input type="text" name="inputActivity_${j}" id="inputActivity_${j}" data-op="${j}" placeholder="Descripción de actividad" class="form-control inner_form activity_list" /></td>
                <td><input type="text" name="inputMonthAct_${j}" id="inputMonthAct_${j}" data-op="${j}" placeholder="Mes" class="form-control inner_form month_list" /></td>
                <td><button type="button" name="remove" id="${j}" class="btn btn-danger btn_remove_act">X</button></td>
                </tr>`);

            $(".month_list").datepicker( {
                format: "mm-yyyy",
                startView: "months", 
                minViewMode: "months"
            });           
        });

        $(document).on('click', '.btn_remove_act', function(){
            var button_id = $(this).attr("id"); 
            $('#row'+button_id+'').remove();
        });

        $("#archivos").on('change', function () {
            if(this.files.length>10){
                Swal.fire('No pueden ser mas de 10 archivos');
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
        

        $("#btnSavedSubvention").click(function(event){
            event.preventDefault();
            // if ($("#formCreateSubvention").valid()) {
                 var form_data = new FormData();
                var step3images = document.getElementById('imagenes').files.length;
                var step5files = document.getElementById('archivos').files.length;
                for (var i = 0; i < step3images; i++) {
                      form_data.append("files[]", document.getElementById('imagenes').files[i]);
                }
                for (var j = 0; j < step5files; j++) {
                      form_data.append("files_2[]", document.getElementById('archivos').files[j]);
                } 

                var financing_array = [];
                $( "#dynamic_field" ).each(function( index ) {
                    var inputs_data = $(this).find(":input").serializeArray();
                    financing_array.push(inputs_data);
                });

                var activities_array = [];
                $( "#dynamic_activities" ).each(function( index ) {
                    var inputs_data = $(this).find(":input").serializeArray();
                    activities_array.push(inputs_data);
                });
                // var data = $('#dynamic_field').find('select, textarea, input').serializeArray(),
                // data_2 = $(' #dynamic_activities').find('select, textarea, input').serializeArray(), 
                // dataFinancingArray = [], dataActivitiesArray = [];

                // for (i = 0; i < data.length; i++) {
                //     dataFinancingArray.push({
                //         name: data[i].name,
                //         value: data[i].value
                //     });
                // }

                // for (i = 0; i < data_2.length; i++) {                     
                //     dataActivitiesArray.push({
                //         name: data_2[i].name,
                //         value: data_2[i].value
                //     });                     
                // }
                
                //  var dataSerialize = JSON.stringify(dataFinancingArray, null, 2), 
                //  dataSerialize2 = JSON.stringify(dataActivitiesArray, null, 2);
 
                var dt = {
                    method: 'saveNewSubvention',
                    id: $('#id_subvention').val() == '' ? 0 : $('#id_subvention').val(),
                    year: $("#inputYearSubvention").val(),
                    organitation:{//Step 1
                        name: $("#inputOrgName").val(),
                        rut: $("#inputOrgRut").val(),
                        address: $("#inputAddress").val(),
                        email: $("#inputEmailAddress").val(),
                        phone: $("#inputPhone").val()
                    },
                    name_proyect: $("#inputProName").val(),
                    objetive_proyect: $("#inputProObj").val(),
                    beneficiarios:[ //Step 2
                        {// 1
                            type:$("#type1").val(),
                            name:$("#inputRepreName1").val(),
                            address:$("#inputRepreAddress1").val(),
                            phone: $("#inputReprePhone1").val()
                        },
                        {// 2
                            type:$("#type2").val(),
                            name:$("#inputRepreName2").val(),
                            address:$("#inputRepreAddress2").val(),
                            phone: $("#inputReprePhone2").val()
                        },
                        {// 3
                            type:$("#type3").val(),
                            name:$("#inputRepreName3").val(),
                            address:$("#inputRepreAddress3").val(),
                            phone: $("#inputReprePhone3").val()
                        },
                        {// 4
                            type:$("#type4").val(),
                            name:$("#inputRepreName4").val(),
                            address:$("#inputRepreAddress4").val(),
                            phone: $("#inputReprePhone4").val()
                        }         
                    ],
                    //Step 3
                    
                    quantity_purchases: $('#inputCantCompras').val(),
                    financing: financing_array,          
                    amount_organitation: $('#inputOrgAmount').val(),   
                    total_sum_price: $('#inputTotalSumPrice').val(), 
                    amount_direct: $('#inputDirectAmount').val(),   
                    amount_indirect: $('#inputIndirectAmount').val(),   
                    total_sum_bene: $('#inputTotalSumBene').val(),
                    //Step 4
                    quantity_activities: $('#inputCantActivities').val(),  
                    activities: activities_array,
                    // Step 5
                    // files: form_data, // archivos tanto del step 3 como del step 5
                };
                console.log(dt);
                // preloader("show");
                $.post(MODEL, dt,
                    function(data) {                      
                        if (data.code == 200) {
                            preloader("hide",data.message,'success');
                            // $("#modalCreateUser").modal('hide');
                            window.location.href = 'subvention.php';
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
            // }
        });
       

        $('#add_rut').blur(function() {
            var parametros = {
                "method": "checkRut",
                "rut": $('#add_rut').val(),
                "id": $('#id_user').val(),
            };
            $.post(MODEL, parametros, function(data) {
                if (data.code == 204) {
                    $('#add_rut').val('');
                    preloader("hide",data.message,'info');
                }
                if (data.code == 440) {
                    loginTimeout();
                }
            },'json');
        });
    },
    edit: function(id) {
        event.preventDefault();
        userController.clean();

        $( "#add_password" ).rules( "add", { required: false });
        $( "#add_repeat_password" ).rules( "add", { required: false });  
        
        var dt = { method: 'editUser', id: id };
        preloader('show');
        $.post(MODEL, dt,
            function(data) {
                if (data.code == 200) {
                    preloader('hide');
                    $("#id_user").val(data.id_user);
                    $("#add_name").val(data.name);
                    $("#add_last_name").val(data.last_name);
                    $("#add_rut").val(data.rut);
                    $("#add_username").val(data.username);
                    $("#add_email").val(data.email);
                    $("#add_role").val(data.type_role);

                    $("#add_phone").val(data.phone);
                    
                    $("#modalCreateUser").modal("show");
                }
                if(data.code == 204){
                    $("#modalCreateUser").modal("hide");
                    preloader("hide",data.message,'error');
                }
                if (data.code == 440) {
                    $("#modalCreateUser").modal("hide");
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
        $("#id_user").val(0);

        $('#add_name').val('');
        $('#add_last_name').val('');
        $('#add_rut').val('');
        $('#add_username').val('');
        $("#add_repeat_password").val("");
        $("#add_password").val("");
        $("#add_email").val("");
        $("#add_role").val("");

        $("#add_phone").val("");

        $( "#add_password" ).rules( "add", { required: true });
        $( "#add_repeat_password" ).rules( "add", { required: true }); 
    },
};

$(function() {
    subventionAddController.init();
    subventionAddController.events();
});
 

document.addEventListener("DOMContentLoaded", function(event) {

    //CARGA DE FIREBASE

});