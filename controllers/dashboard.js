var MODEL = '../models/dashboard_model.php';

var userController = {
    init: () => {

        var dt = { method: 'findDashboardData' };
        $.post(MODEL, dt,
            function(data) {                
                if (data.code == 200) {
                    var html = '';
                    $.each( data.data, function( key, value ) {                        
                        html += `<div class="col-xl-2 col-md-6 mb-4 div_rol" data-id-rol="${value.id}" style="cursor: pointer">
                                <div class="card shadow h-100 py-2" style="border-left: 0.25rem solid #b9cbff !important;">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                ${value.name}</div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="h5 mt-2 font-weight-bold text-gray-800">${value.cantidad}</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><i class="far fa-id-badge fa-2x text-gray-300"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    });
                    $("#row_roles").html(html);
                }                
                if (data.code == 440) {
                    loginTimeout();
                }
                userController.events();
            },
            "json"
        );
        //preloader('show');
    },
    events: function() {
        $(".div_rol").click(function(event){
            event.preventDefault();
            var id_role = $(this).data('id-rol');
            //window.location.href = 'xxxxxxxxx.php?r='+id_role;
        });
    }
};

$(function() {
    userController.init();
    //userController.events();
});


document.addEventListener("DOMContentLoaded", function(event) {

});