(function($) {
	"use strict"; // Start of use strict

	$("#open_options").mouseenter(function(){
        $("#abrir_drop").addClass('show');
    });
    $("#open_options").mouseleave(function(){
        $("#abrir_drop").removeClass('show');
    });

	$("#logout").click(function(event) {
		event.preventDefault();

		Swal.fire({
			title: 'Estas seguro?',
			text: "tu sesion va a cerrarse!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			confirmButtonText: 'Si, hazlo!',
  			cancelButtonText: 'Cancelar!',
		}).then((result) => {
			if (result.isConfirmed) {
				$.post("../models/login_model.php", {"method":"logout"},
	                function(data) {
	                    console.log(data)
	                    if (data.code == 200) {
	                        window.location.href = 'login.php';	                        
	                    }
	                },
	                "json"
	            );
			}
		})
	});

	console.log("F2021")

	$("#switch_theme").click(function(event) {
		var status = '';
		if( $('#switch_theme').is(':checked') ) {
			var status = 1;
		    $(".estilo").attr("href","../assets/css/sb-admin-2-dark.min.css")
		}else{
			var status = 0;
			$(".estilo").attr("href","../assets/css/sb-admin-2.min.css")
		}

		$.post("../models/dashboard_model.php", {method: "change_theme", status: status},
            function(data) {
                
            },
            "json"
        );
	});

})(jQuery); // End of use strict


function preloader(type, message = '', status = '') {
	Swal.fire({
		title: message == '' ? "Cargando..!" : message,
		showConfirmButton: false,
		showCancelButton: false,
		allowOutsideClick: false,
		onOpen: function() {
			if (type == 'show') {
				swal.showLoading()
			} else {
				if (message != '') {
					Swal.fire({
						icon: status,
						title: message,
						type: status,
						timer: 2000
					});
				} else {
					swal.close()
				}
			}

		}
	}).then(function(e) {
		"timer" === e.dismiss && console.log("I was closed by the timer")
	})
	//Swal.fire('Any fool can use a computer')
}

function preloaderTwo(message){
	Swal.fire({
		title: message == '' ? "Cargando..!" : message,
		showConfirmButton: false,
		showCancelButton: false,
		allowOutsideClick: false,
		onOpen: function() {
			swal.showLoading()
		}
	}).then(function(e) {
		"timer" === e.dismiss && console.log("I was closed by the timer")
	})
}


function loginTimeout() {
	Swal.fire({
		title: 'La sesion ha expirado!',
		text: 'Vuelva a iniciar sesion por favor.',
		imageUrl: '../assets/img/loginTimeout.png',
		imageWidth: 200,
		imageHeight: 200,
		imageAlt: 'Custom image',
	})
    setTimeout(function(){
        $.post("../../models/login_model.php", {"method":"logout"},
            function(data) {
                if (data.code == 200) {
                    window.location.href = 'login.php';	                        
                }
            },
            "json"
        );
    }, 3000);
}