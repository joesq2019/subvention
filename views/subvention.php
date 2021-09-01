<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_list')): ?>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-contract"></i> Tabla de Usuarios </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_add')): ?>
				<button type="button" class="btn btn-primary btn-sm float-right" id="btn_newSubvention"> <i class="fas fa-file-contract"></i> Nueva subvención
				</button>
				<?php endif; ?>
			</div>
		</div>	    		
	</div>
	<div class="card-body border-bottom-primary">
	    <div class="table-responsive">
	        <table class="table table-bordered" id="subventionDataTable" cellspacing="0">
	            <thead>
	                <tr>
	                	<th># Folio</th>
	                    <th>Nombre Organización</th>
	                    <th>Fecha solicitud subvención</th>
	                    <th>Fecha rendición</th>
	                    <th>Estado de rendición</th>
	                    <th>Acciones</th>
	                </tr>
	            </thead>
	            <tbody>
                </tbody>
	        </table>
	    </div>
	</div>
</div>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_add') OR $obj_function->validarPermiso($_SESSION['permissions'],'subvention_edit')): ?>


<?php endif; ?>

<script src="https://www.gstatic.com/firebasejs/6.1.0/firebase.js"></script>

<script src="../controllers/subvention.js"></script>

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