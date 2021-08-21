<?php require_once '../common/header.php'; ?>

<?php if($obj_function->validarPermiso($_SESSION['permissions'],'roles_list')): ?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="far fa-id-badge"></i> Tabla de Roles </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'roles_add')): ?>
				<button type="button" class="btn btn-primary btn-sm float-right" id="btn_newRol"> <i class="far fa-id-badge"></i> Nuevo Rol
				</button>
				<?php endif; ?>
			</div>
		</div>	    		
	</div>
	<div class="card-body border-bottom-primary">
	    <div class="table-responsive">
	        <table class="table table-bordered" id="rolesDataTable" cellspacing="0">
	            <thead>
	                <tr>
	                	<th>#</th>
	                    <th>Nombre</th>
	                    <th>Acciones</th>
	                </tr>
	            </thead>
	            <tbody>
                </tbody>
	        </table>
	    </div>
	</div>
</div>

<?php if($obj_function->validarPermiso($_SESSION['permissions'],'roles_add')): ?>
<div class="modal fade" id="modalCreateRol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Usuario</h5>
                <input type="hidden" name="id_role" id="id_role" value="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id='data_permisos'>
                	<div class="row ">
                		<div class="col-md-4 form-group">
                			<label for="">Nombre del Rol</label>
                		<input type="text" class="form-control" name="add_name" id="add_name">
                		</div>
                		
                	</div>
				    <div class="row">
				        <?php foreach($obj_function->users_permissions() as $key => $value): ?>
				        	<div class="col-md-6 card shadow pl-1 mb-1">
			                    <div class="header mt-3 ml-3">
			                        <h6 class="title"><?php echo $value['icon'].' '.$value['title']; ?></h6>
			                    </div><hr>
			                    <div class="form-check">
			                        <?php foreach($value['keys'] as $k => $v): ?>			                        	
			                        	<input type="checkbox" value="true" class="kick" checked="checked" name="<?php echo $k; ?>" id="<?php echo $k; ?>">
			                            <label for="<?php echo $k; ?>" style="font-size: 90%"><?php echo $v; ?></label>
			                            <br>
			                        <?php endforeach; ?>
			                    </div>
				            </div>
				        <?php endforeach; ?>
				    </div>
				</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveRole">Guardar</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="../controllers/roles.js"></script>

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

<?php require_once '../common/footer.php' ?>