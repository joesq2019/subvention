<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'budget_information_list')): ?>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Informacion presupuestaria </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'budget_information_add')): ?>
				<button type="button" class="btn btn-primary btn-sm float-right" id="btn_newBudgetInformation"> <i class="fas fa-user"></i> Nuevo cert. informacion presupuestaria
				</button>
				<?php endif; ?>
			</div>
		</div>	    		
	</div>
	<div class="card-body border-bottom-primary">
	    <div class="table-responsive">
	        <table class="table table-bordered" id="budgetInformationDataTable" cellspacing="0">
	            <thead>
	                <tr>
	                	<th>#</th>
	                    <th>N° Certificado</th>
	                    <th>Fecha de emisión</th>
	                    <th>Monto disponible</th>
	                    <th>Documento de respaldo</th>
	                    <th>Acciones</th>
	                </tr>
	            </thead>
	            <tbody>
                </tbody>
	        </table>
	    </div>
	</div>
</div>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'budget_information_add') OR $obj_function->validarPermiso($_SESSION['permissions'],'accountability_edit')): ?>
<div class="modal fade" id="modalCreateBudgetInformation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titleModelAccountability">Nuevo Cert Información Presupuestaria</span></h5>
                <input type="hidden" name="id_budget_information" id="id_budget_information" value="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateBudgetInformation">
                	 <div class="row">
                    	<div class="col-md-4">
                    		<div class="form-group m-form__group"> 
		                        <label>N° asignacion presupuestaria</label>
		                        <input type="text" name="add_budget_certificate_number" id="add_budget_certificate_number" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-4">
                    		<div class="form-group m-form__group"> 
		                        <label>Fecha de emisión</label>
		                        <input type="date" name="add_emision_date" id="add_emision_date" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-4">
                    		<div class="form-group m-form__group"> 
		                        <label>Monto disponible</label>
		                        <input type="number" name="add_amount_available" id="add_amount_available" class="form-control" step="0.1" min="0" required>		                        
		                    </div>
                    	</div>
                    	<div class="col md-12">
                    		<label>Subir documento de respaldo</label>
		                    <label class="btn btn-primary btn-file">
		                        Cargar Archivo
		                        <input type="file" id="documento_de_respaldo" name="documento_de_respaldo" style="display: none;" class="input_file" accept='file_extension|image/*'>
		                    </label> 
		                    <div class="mb-3 col-md-12 form-group row gx-3" id="lista_2">
			                    <p>Debe Cargar 1 foto minimo</p>
			                </div> 
			            </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveBudgetInformation">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase.js"></script>

<script src="../controllers/budget_information.js"></script>

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