<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'user_list')): ?>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Tabla de Usuarios </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'user_add')): ?>
				<button type="button" class="btn btn-primary btn-sm float-right" id="btn_newUser"> <i class="fas fa-user"></i> Nuevo usuario
				</button>
				<?php endif; ?>
			</div>
		</div>	    		
	</div>
	<div class="card-body border-bottom-primary">
	    <div class="table-responsive">
	        <table class="table table-bordered" id="usersDataTable" cellspacing="0">
	            <thead>
	                <tr>
	                	<th>#</th>
	                    <th>Rut</th>
	                    <th>Nombre</th>
	                    <th>Email</th>
	                    <th>Rol</th>
	                    <th>Acciones</th>
	                </tr>
	            </thead>
	            <tbody>
                </tbody>
	        </table>
	    </div>
	</div>
</div>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'user_add') OR $obj_function->validarPermiso($_SESSION['permissions'],'user_edit')): ?>
<div class="modal fade" id="modalCreateUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Usuario</h5>
                <input type="hidden" name="id_user" id="id_user" value="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateUser">
                	<div class="row">
                		<div class="col-md-6">
                			<div class="form-group m-form__group"> 
		                        <label>Nombres</label>
		                        <input type="text" name="add_name" id="add_name" class="form-control" required="">
		                    </div>
                		</div>
                		<div class="col-md-6">
                			<div class="form-group m-form__group"> 
		                        <label>Apellidos</label>
		                        <input type="text" name="add_last_name" id="add_last_name" class="form-control">
		                    </div>
                		</div>
                	</div>
                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Rut</label>
		                        <input type="text" name="add_rut" id="add_rut" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Nombre de usuario</label>
		                        <input type="text" name="add_username" id="add_username" class="form-control" required>
		                    </div>
                    	</div>
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Contraseña</label><button type="button" class="btn btn-outline-metal m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill m-btn--air" id="seePass">
		                            <i class="fa fa-eye"></i>
		                        </button>
		                        <input type="password" name="add_password" id="add_password" class="form-control" required>               
		                    </div>		                    
                    	</div>
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Repite la contraseña</label>
		                        <input type="password" name="add_repeat_password" id="add_repeat_password" class="form-control" required>
		                    </div>
                    	</div>
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Correo</label>
		                        <input type="email" name="add_email" id="add_email" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Número de contacto</label>
		                        <input type="text" name="add_phone" id="add_phone" class="form-control" required>
		                    </div>
                    	</div>
                    </div>
                    <div class="row">
                    	<div class="col-md-12">
                    		<div class="form-group m-form__group"> 
		                        <label>Rol</label>
		                        <select name="add_role" id="add_role" class="form-control" required="">
		                        	<option value="">Selecciona una opcion</option>
		                        	<option value="1">Administrador</option>
		                        	<option value="2">Directorio</option>
		                        	<option value="3">Gerente General</option>
		                        	<option value="4">Staff Tecnico</option>
		                        	<option value="5">Area Medica</option>
		                        	<option value="6">Administracion</option>
		                        	<option value="7">Comunicación</option>
		                        </select>
		                    </div>
                    	</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveUser">Guardar</button>
            </div>
        </div>
    </div>
</div>


<?php endif; ?>

<script src="https://www.gstatic.com/firebasejs/6.1.0/firebase.js"></script>

<script src="../controllers/users.js"></script>

<?php else: ?>
    <div class="alert alert-danger" role="alert">
    	<i class="fas fa-exclamation-triangle ml-5" style="font-size: 75px"></i>
    	<br><br>
        No tienes acceso a esta área. <br>
        Serás enviado al inicio del sistema.
    </div>
    <script>
        setTimeout(function(){
            window.location.href = 'dashboard.php';
        },3000);     
    </script>
<?php endif; ?>

<?php require_once '../common/footer.php'; ?>         