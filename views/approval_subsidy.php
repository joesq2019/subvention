<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'approval_subsidy_list')): ?>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Informacion presupuestaria </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'approval_subsidy_add')): ?>
				<!-- <button type="button" class="btn btn-primary btn-sm float-right" id="btn_newApprovalSubsidy"> <i class="fas fa-user"></i> Nuevo aprobación de subvención -->
				</button>
				<?php endif; ?>
			</div>
		</div>	    		
	</div>
	<div class="card-body border-bottom-primary">
	    <div class="table-responsive">
	        <table class="table table-bordered" id="approvalSubsidyDataTable" cellspacing="0">
	            <thead>
	                <tr>
	                	<th>#</th>
	                	<th>N° Folio Subvencion </th>
	                    <th>N° Decreto alcaldicio</th>
	                    <th>Fecha convenio</th>
	                    <th>N° Decreto de pago</th>
	                    <th>Fecha de pago</th>
	                    <!-- <th>N° de cuotas de pago</th> -->
	                    <th>Acciones</th>
	                </tr>
	            </thead>
	            <tbody>
                </tbody>
	        </table>
	    </div>
	</div>
</div>


<script src="../controllers/approval_subsidy.js"></script>

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