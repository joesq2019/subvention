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
				<button type="button" class="btn btn-primary btn-sm float-right" id="btn_newApprovalSubsidy"> <i class="fas fa-user"></i> Nuevo aprobación de subvención
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
	                    <th>N° Decreto alcaldicio</th>
	                    <th>Fecha convenio</th>
	                    <th>N° Decreto de pago</th>
	                    <th>Fecha de pago</th>
	                    <th>N° de cuotas de pago</th>
	                    <th>Acciones</th>
	                </tr>
	            </thead>
	            <tbody>
                </tbody>
	        </table>
	    </div>
	</div>
</div>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'approval_subsidy_add') OR $obj_function->validarPermiso($_SESSION['permissions'],'approval_subsidy_add')): ?>
<div class="modal fade" id="modalCreateApprovalSubsidy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titleModelApprovalSubsidy">Nueva aprovacion subvención</span></h5>
                <input type="hidden" name="id_approval_subsidy" id="id_approval_subsidy" value="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateApprovalSubsidy">
                	 <div class="row">
                    	<div class="col-md-12">
                    		<div class="form-group m-form__group"> 
		                        <label>N° Decreto alcaldicio que aprueba subvención</label>
		                        <input type="text" name="add_no_mayor_decree" id="add_no_mayor_decree" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-12">
                    		<div class="form-group m-form__group"> 
		                        <label>Fecha convenio de entrega subvención</label>
		                        <input type="date" name="add_agreement_date" id="add_agreement_date" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-12">
                    		<div class="form-group m-form__group"> 
		                        <label>N° Decreto de pago</label>
		                        <input type="text" name="add_no_payment_decree" id="add_no_payment_decree" class="form-control">
		                    </div>
                    	</div>
                    	<div class="col-md-12">
                    		<div class="form-group m-form__group"> 
		                        <label>Fecha de pago</label>
		                        <input type="date" name="add_payment_date" id="add_payment_date" class="form-control">
		                    </div>
                    	</div>
                    	<div class="col md-12">
                    		<label>N° de cuotas de pago</label>
		                    <input type="number" name="add_no_payment_installments" id="add_no_payment_installments" class="form-control" min="0" >
			            </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveApprovalSubsidy">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

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