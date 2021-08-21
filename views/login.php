<?php
session_start();
require_once '../common/function.php';

$obj_function = new coFunction();

$_SESSION['key'] = $obj_function->randString(20); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Subvenciones</title>

	<link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon.png">
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="../assets/css/logincss.css" rel="stylesheet" />
    <link href="../assets/css/alertify.min.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet"> 

    <script src="../assets/js/sweetalert2.all.min.js"></script>

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
</head>
<style>
body, html {
  height: 100%;
  margin: 0;
}

.bg {
  /* The image used */
  background-image: url('../assets/img/loginbg.jpg');

  /* Full height */
  height: 100%; 

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

.test {
	border-bottom: 2px solid red;
}
</style>
<body class="bg">
	<div class="wrapper fadeInDown">

		<div id="formContent" style="background-color: transparent;">
			<input type="hidden" name="key" id="key" value="<?php echo $_SESSION['key'];?>">

			<div class="row mb-4">
				<div class="col-md-9">
					<h3 class="ml-6" style="margin-left:120px; color:white">Inicio de sesion</h2>
				</div>
			</div>

			<div id="div_login" style="display: block">
				<!-- Login Form -->
				<form id="form_login">
					<input type="text" id="username" class="fadeIn second" name="username" placeholder="Nombre de usuario">
					<input type="password" id="password" class="fadeIn third" name="password" placeholder="Contraseña">
					<input type="submit" class="fadeIn fourth" value="Iniciar Session" id="btn_login">
				</form>

				<!-- Remind Passowrd -->
				<div id="formFooter" style="background-color: transparent;">
					<a class="underlineHover" id="olvido_password" style="color: white; cursor: pointer">Olvidó su contraseña?</a>
				</div>
			</div>


			<div id="div_recover" style="display: none">
				<!-- Login Form -->
				<form id="form_recover">
					<label for="" style="color:white">Por favor introduzca su correo electronico</label>
					<div class="form-group">
						<input type="text" id="email" class="fadeIn second" name="email" placeholder="tunombre@ejemplo.com">
					</div>

					<input type="submit" class="fadeIn fourth" value="Recuperar Contraseña" id="btn_recover">
				</form>

				<!-- Remind Passowrd -->
				<div id="formFooter" style="background-color: transparent;">
					<a class="underlineHover volver_login" style="color: white; cursor: pointer">Volver al login</a>
				</div>
			</div>

			<div id="div_change_password" style="display: none">
				<!-- Login Form -->
				<form id="form_new_password">
					<input type="hidden" id="user_id" class="fadeIn second" name="user_id">
					<label for="" style="color:white">Ingrese su nueva contraseña</label>
					<div class="form-group">
						<input type="text" id="new_password" class="fadeIn second" name="new_password" placeholder="********">
					</div>
					<label for="" style="color:white">Repita su nueva contraseña</label>
					<div class="form-group">
						<input type="text" id="repeat_new_password" class="fadeIn second" name="repeat_new_password" placeholder="********">
					</div>

					<input type="submit" class="fadeIn fourth" value="Guardar Cambios" id="btn_save_password">
				</form>

				<!-- Remind Passowrd -->
				<div id="formFooter" style="background-color: transparent;">
					<a class="underlineHover volver_login" style="color: white; cursor: pointer">Volver al login</a>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="../controllers/login.js" type="text/javascript"></script>
<script src="../assets/scripts/function.js" type="text/javascript"></script>
</html>