var MODEL = '../models/subvention_model.php';
var dataTable = '';
var formCreateSubvention = $("#formCreateSubvention");
var action_subvention = sessionStorage.getItem('action');
var id_subvention = sessionStorage.getItem('id_subvention');

var subventionAddController = {
    init: () => {
        
        if (action_subvention == 2) {
            subventionAddController.edit(id_subvention)
        }

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

        $("#financing_files").on('change', function () {
            if(this.files.length>5){
                Swal.fire('No pueden ser mas de 5 archivos');
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
                                    $(`<div class="col-md-3 text-center"><img src="../assets/img/file.png" class="rounded img-fluid" style="height:75px;width:75px"><br><small>${files[i].name}</small></div>`).appendTo(image_holder);
                                // }
                            }else{
                               reader.onload = function (e) {
                                    $("<img />", {
                                        "src": e.target.result,
                                            "class": "col-md-3 rounded img-fluid",
                                            "style": "height:75px;width:75px"
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

        var i1 = 1;
        var i2 = 100;
        $('#add').click(function(){
            if (action_subvention == 2) {
                i2++;
                $('#dynamic_field').append(
                `<tr id="row${i2}" class="dynamic_field">
                <td><input type="text" name="inputDetails_${i2}" id="inputDetails_${i2}" placeholder="Detalle inversión 1" class="form-control inner_form detalle_list" /></td>
                <td><input type="number" name="inputUnityPrice_${i2}" id="inputUnityPrice_${i2}" data-op="${i2}" placeholder="Precio unitario" class="form-control inner_form " /></td>
                <td><input type="number" name="inputQuantity_${i2}" id="inputQuantity_${i2}" data-op="${i2}" placeholder="Cantidad" class="form-control inner_form quantity_list" /></td>
                <td><input type="text" name="inputTotalPrice_${i2}" id="inputTotalPrice_${i2}" data-op="${i2}" placeholder="Precio Total" class="form-control inner_form total_price_list" readonly="" /></td>
                <td><button type="button" name="remove" id="${i2}" class="btn btn-danger btn_remove">X</button></td>
                </tr>`);
            }else{
                i1++;
                $('#dynamic_field').append(
                `<tr id="row${i1}" class="dynamic_field">
                <td><input type="text" name="inputDetails_${i1}" id="inputDetails_${i1}" placeholder="Detalle inversión 1" class="form-control inner_form detalle_list" /></td>
                <td><input type="number" name="inputUnityPrice_${i1}" id="inputUnityPrice_${i1}" data-op="${i1}" placeholder="Precio unitario" class="form-control inner_form " /></td>
                <td><input type="number" name="inputQuantity_${i1}" id="inputQuantity_${i1}" data-op="${i1}" placeholder="Cantidad" class="form-control inner_form quantity_list" /></td>
                <td><input type="text" name="inputTotalPrice_${i1}" id="inputTotalPrice_${i1}" data-op="${i1}" placeholder="Precio Total" class="form-control inner_form total_price_list" readonly="" /></td>
                <td><button type="button" name="remove" id="${i1}" class="btn btn-danger btn_remove">X</button></td>
                </tr>`);  
            }
         
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

        var j1 = 1;
        var j2 = 100;

        $('#add_act').click(function(){
            if (action_subvention == 2) {
                j2++;
                $('#dynamic_activities').append(
                    `<tr id="row${j2}" class="dynamic_activities">
                    <td class="col-md-8"><input type="text" name="inputActivity_${j2}" id="inputActivity_${j2}" data-op="${j2}" placeholder="Descripción de actividad" class="form-control inner_form activity_list" /></td>
                    <td><input type="text" name="inputMonthAct_${j2}" id="inputMonthAct_${j2}" data-op="${j2}" placeholder="Mes" class="form-control inner_form month_list" /></td>
                    <td><button type="button" name="remove" id="${j2}" class="btn btn-danger btn_remove_act">X</button></td>
                    </tr>`);

                $(".month_list").datepicker( {
                    format: "mm-yyyy",
                    startView: "months", 
                    minViewMode: "months"
                }); 
            }else{
                j1++;
                $('#dynamic_activities').append(
                    `<tr id="row${j1}" class="dynamic_activities">
                    <td class="col-md-8"><input type="text" name="inputActivity_${j1}" id="inputActivity_${j1}" data-op="${j1}" placeholder="Descripción de actividad" class="form-control inner_form activity_list" /></td>
                    <td><input type="text" name="inputMonthAct_${j1}" id="inputMonthAct_${j1}" data-op="${j1}" placeholder="Mes" class="form-control inner_form month_list" /></td>
                    <td><button type="button" name="remove" id="${j1}" class="btn btn-danger btn_remove_act">X</button></td>
                    </tr>`);

                $(".month_list").datepicker( {
                    format: "mm-yyyy",
                    startView: "months", 
                    minViewMode: "months"
                }); 
            }
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

            if ($("#formCreateSubvention").valid()) {
                $("#btnSavedSubvention").attr('disabled','disabled');//deshabilitar el boton
                // var form_data = new FormData();
                // var step3images = document.getElementById('financing_files').files.length;
                // var step5files = document.getElementById('archivos').files.length;
                // for (var i = 0; i < step3images; i++) {
                //       form_data.append("files[]", document.getElementById('imagenes').files[i]);
                // }
                // for (var j = 0; j < step5files; j++) {
                //       form_data.append("files_2[]", document.getElementById('archivos').files[j]);
                // } 

                var financing_array = [];
                $( ".dynamic_field" ).each(function( index ) {
                    var inputs_data = $(this).find(":input").serializeArray();
                    financing_array.push(inputs_data);
                });

                var activities_array = [];
                $( ".dynamic_activities" ).each(function( index ) {
                    var inputs_data = $(this).find(":input").serializeArray();
                    activities_array.push(inputs_data);
                });
 
                var dt = {
                    method: 'saveNewSubvention',
                    id_subvention: $('#id_subvention').val() == '' ? 0 : $('#id_subvention').val(),
                    id_organitation: $('#id_organitation').val() == '' ? 0 : $('#id_organitation').val(),
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
                            console.log("200")
                            console.log(data)
                            uploadDocuments(data.subvention_id, function (response) {
                                var file = document.getElementById("financing_files").files;
                                if (file.length > 0) {
                                    preloaderTwo("Guardando documentos del financiamiento, por favor espere..!");
                                    var storageRef = firebase.storage().ref();                            

                                    $.each( file, function( key, value ) {
                                        console.log('KEY: '+key)

                                        var randonfunction = (min, max) => Math.floor(Math.random() * (max - min)) + min;
                                        var numero = randonfunction(10000, 99999);

                                        var ext = '.'+value.name.split('.').pop();
                                        var new_name = data.subvention_id+'-'+key+'-'+numero+ext;
                                        var path = 'financing/'+new_name;
                                        var name_file = value.name;

                                        var uploadTask = storageRef.child(path).put(file[key]);                                

                                        uploadTask.on('state_changed', function(snapshot){
                                        }, function(error) {
                                            console.log(error);
                                        }, function() {
                                            uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                                                var parametros = {
                                                    "method": "saveFinancingFiles",
                                                    "subvention_id": data.subvention_id,
                                                    "name": name_file,
                                                    "path": path,
                                                    "url": downloadURL
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
                                                    preloader("hide","La subvencion y los documentos fueron guardados con éxito..!","success");
                                                    setTimeout(function(){
                                                        window.location.href = 'subvention.php';
                                                    },2000);
                                                }
                                            });
                                        });
                                    });
                                } else {
                                    if(response == 1){
                                        preloader("hide","La subvencion y los documentos fueron guardados con éxito..!","success");
                                        setTimeout(function(){
                                            window.location.href = 'subvention.php';
                                        },2000);
                                    } else {
                                        preloader("hide","La subvencion fue guardada con éxito..!","success");
                                        setTimeout(function(){
                                            window.location.href = 'subvention.php';
                                        },2000);
                                    }
                                }
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
        //$( "#financing_files" ).rules( "add", { required: false });
        //$( "#add_repeat_password" ).rules( "add", { required: false });
        $(".btn-next-4").hide();//hide siguiente
        $("#btnSavedSubventionEdit").show(); //show guardar
        $("#wizard5-tab").remove();

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
            $("#inputRepreAddress1").val(data.members_data[0].address);
            $("#inputReprePhone1").val(data.members_data[0].phone);

            $("#id_vicepresidente").val(data.members_data[1].id);
            $("#inputRepreName2").val(data.members_data[1].name);
            $("#inputRepreAddress2").val(data.members_data[1].address);
            $("#inputReprePhone2").val(data.members_data[1].phone);

            $("#id_secretario").val(data.members_data[2].id);
            $("#inputRepreName3").val(data.members_data[2].name);
            $("#inputRepreAddress3").val(data.members_data[2].address);
            $("#inputReprePhone3").val(data.members_data[2].phone);
            
            $("#id_tesorero").val(data.members_data[3].id);
            $("#inputRepreName4").val(data.members_data[3].name);
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
                        <input type="text" name="inputDetails_${number_input}" id="inputDetails_${number_input}" placeholder="Detalle inversión 1" class="form-control inner_form detalle_list" value="${value.details}"/>
                    </td>
                    <td>
                        <input type="number" name="inputUnityPrice_${number_input}" id="inputUnityPrice_${number_input}" data-op="${number_input}" placeholder="Precio unitario" class="form-control inner_form"  value="${value.unit_price}"/>
                    </td>
                    <td>
                        <input type="number" name="inputQuantity_${number_input}" id="inputQuantity_${number_input}" data-op="${number_input}" placeholder="Cantidad" class="form-control inner_form quantity_list"  value="${value.quantity}"/>
                    </td>
                    <td>
                        <input type="text" name="inputTotalPrice_${number_input}" id="inputTotalPrice_${number_input}" data-op="${number_input}" placeholder="Precio Total" class="form-control inner_form total_price_list" readonly="" value="${value.total_price}" />
                    </td>
                    <td>
                        <button type="button" name="remove" id="${number_input}" class="btn btn-danger btn_remove">X</button>
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

            setTimeout(function(){
                subventionAddController.eventsDinamic();
            },1000);            
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
                        <input type="text" name="inputActivity_${number_input_schedule}" id="inputActivity_${number_input_schedule}" data-op="${number_input_schedule}" placeholder="Descripción de actividad" class="form-control inner_form activity_list" value="${value.activities}"/>
                    </td>
                    <td>
                        <input type="text" name="inputMonthAct_${number_input_schedule}" id="inputMonthAct_${number_input_schedule}" data-op="${number_input_schedule}" placeholder="Mes" class="form-control inner_form month_list" value="${value.month}"/>
                    </td>
                    <td>
                        <button type="button" name="remove" id="${number_input_schedule}" class="btn btn-danger btn_remove_act">X</button>
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
        },'json');
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
    eventsDinamic: function() {
        $(".delete_financing_file").click(function(){
            event.preventDefault();
            var id_file = $(this).data('id');
            var element = $(this);
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
                        "method": "deleteFinancingFile",
                        "id_file": id_file,
                    };
                    $.post(MODEL, parametros, function(data) {
                        console.log(data)
                        if (data.code == 200) {
                            element.parents("div:first").remove();
                            var storageRef = firebase.storage().ref();
                            var desertRef = storageRef.child(data.path);
                            console.log(data.path)
                            desertRef.delete().then(function() {
                            }).catch(function(error) {
                            });
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
        });

        $("#btnSavedSubventionEdit").click(function(event){
            event.preventDefault();
            if ($("#formCreateSubvention").valid()) {
                $("#btnSavedSubventionEdit").attr('disabled','disabled');//deshabilitar el boton
                var financing_array = [];
                $( ".dynamic_field" ).each(function( index ) {
                    var inputs_data = $(this).find(":input").serializeArray();
                    financing_array.push(inputs_data);
                });

                var activities_array = [];
                $( ".dynamic_activities" ).each(function( index ) {
                    var inputs_data = $(this).find(":input").serializeArray();
                    activities_array.push(inputs_data);
                });
 
                var dt = {
                    method: 'saveNewSubvention',
                    id_subvention: $('#id_subvention').val() == '' ? 0 : $('#id_subvention').val(),
                    id_organitation: $('#id_organitation').val() == '' ? 0 : $('#id_organitation').val(),
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
                            var file = document.getElementById("financing_files").files;
                            if (file.length > 0) {
                                preloaderTwo("Guardando documentos del financiamiento, por favor espere..!");
                                var storageRef = firebase.storage().ref();                            

                                $.each( file, function( key, value ) {
                                    console.log('KEY: '+key)

                                    var randonfunction = (min, max) => Math.floor(Math.random() * (max - min)) + min;
                                    var numero = randonfunction(10000, 99999);

                                    var ext = '.'+value.name.split('.').pop();
                                    var new_name = data.subvention_id+'-'+key+'-'+numero+ext;
                                    var path = 'financing/'+new_name;
                                    var name_file = value.name;

                                    var uploadTask = storageRef.child(path).put(file[key]);                                

                                    uploadTask.on('state_changed', function(snapshot){
                                    }, function(error) {
                                        console.log(error);
                                    }, function() {
                                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                                            var parametros = {
                                                "method": "saveFinancingFiles",
                                                "subvention_id": data.subvention_id,
                                                "name": name_file,
                                                "path": path,
                                                "url": downloadURL
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
                                                preloader("hide","La subvencion y los documentos fueron guardados con éxito..!","success");
                                                setTimeout(function(){
                                                    window.location.href = 'subvention.php';
                                                },2000);
                                            }
                                        });
                                    });
                                });
                            } else {
                                preloader("hide","La subvencion fue guardada con éxito..!","success");
                                setTimeout(function(){
                                    window.location.href = 'subvention.php';
                                },2000);
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
    }
};

$(function() {
    subventionAddController.init();
    subventionAddController.events();
});

function uploadDocuments(id_subvention, callback) {
    var isset = 0;
    $(".input_file").each(function( index, value ) {
        if(value.files[0] !== undefined){
            isset++;
        }
    });

    if (isset > 0) {
        preloaderTwo("Guardando documentos, por favor espere..!");
        var storageRef = firebase.storage().ref();
        var count = 0;
        $(".input_file").each(function( index, value ) {
            if(value.files[0] !== undefined){
                var ext = '.'+value.files[0].name.split('.').pop();
                var new_name = id_subvention+'_'+value.id+ext;
                var path = 'documents/'+new_name;
                
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
                            "method": "saveDocumentsFiles",
                            "subvention_id": id_subvention,
                            "type": value.id,
                            "name": value.files[0].name,
                            "path": path,
                            "url": downloadURL
                        };
                        $.post(MODEL, parametros, function(data2) {
                            // if(data2.path != ''){
                            //     var desertRef = storageRef.child(data2.path);
                            //     desertRef.delete().then(function() {
                            //     }).catch(function(error) {
                            //     });
                            // }
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