<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_list')): ?>
<div class="alert alert-success text-center" id="alert_name_organization" role="alert" style="display:none">
  
</div>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-contract"></i> Tabla de Subvenciones </h6>
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
	        <table class="table table-bordered" id="subventionDataTable" cellspacing="0" width="100%">
	            <thead>
	                <tr>
	                	<th># Folio</th>
	                    <th>Nombre Organización</th>
	                    <th>Fecha solicitud subvención</th>
	                    <th>Fecha rendición</th>
	                    <th>Estado de rendición</th>
	                    <th>Estado de de la Subvención</th>
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

<div class="modal fade" id="modalListDocuments" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	<div class="col-md-6">
            		<h5 class="modal-title" id="exampleModalLabel">Lista de Documentos</h5>
            	</div>
            	<div class="col-md-5">
            		<button class="btn btn-success btn-sm float-right" id="uploadNewDocument"><i class="fas fa-file-contract"></i> Cargar nuevo documento</button>
            	</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
			    <div class="table-responsive">
			        <table class="table table-bordered" id="dataTableListDocuments" cellspacing="0" width="100%">
			            <thead>
			                <tr>
			                	<th>#</th>
			                    <th>Tipo</th>
			                    <th>Nombre del archivo</th>
			                    <th>Acciones</th>
			                </tr>
			            </thead>
			            <tbody>
		                </tbody>
			        </table>
			    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveUser">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUploadDocuments" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	<div class="col-md-6">
            		<h5 class="modal-title" id="exampleModalLabel">Cargar nuevo Documento</h5>
            	</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" name="id_subvention" id="id_subvention">
            	<form id="formUploadDocument">
					<div class="col-md-12">
						<div class="form-group m-form__group">
							<label for="">Tipo de archivo</label>
		        			<select name="select_type_documents" id="select_type_documents" class="form-control">
		        				<option value="">Selecciona una opción</option>
		        				<option value="certificado_persolidad_juridica">Certificado de Personalidad Jurídica Vigentes</option>
		        				<option value="certificado_directorio">Certificado de Directorio Vigente</option>
		        				<option value="estatutos">Estatutos</option>
		        				<option value="certificado_inscripcion">Certificado de Inscripción en el Registro Central de Colaboradores del Estado y Municipalidades</option>
		        				<option value="fotocopia_rut">Fotocopia del RUT de la organización</option>
		        				<option value="fotocopia_cedula">Fotocopia Cédula de Identidad del representante legal</option>
		        				<option value="fotocopia_libreta">Fotocopia de la libreta de ahorro o cuenta corriente a nombre de la organización</option>
		        				<option value="fotocopia_registro">Fotocopia de registro de propiedad o comodato de Sede Comunitaria (cuando corresponda)</option>
		        				<option value="fotocopia_caratula">Fotocopia de la carátula de la rendición de cuentas de la última subvención (si corresponde)</option>
		        				<option value="antecedentes">Otros Antecedentes</option>
		        			</select>
						</div>
	        		</div>
	        		<div class="col-md-12">
	        			<div class="form-group m-form__group">
	        				<label for="">Tipo de archivo</label>
	        				<input type="file" class="form-control" name="input_upload_document" id="input_upload_document">
						</div>
	        		</div>
            	</form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnSaveNewDocument">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalActions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	<div class="col-md-6">
            		<h5 class="modal-title" id="number_folio">Subvención</h5>
            	</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" name="id_subvention_action" id="id_subvention_action">
            	<form id="formAction">
					<div class="col-md-12">
						<div class="form-group m-form__group">
							<label for="">Acción:</label>
		        			<select name="select_action" id="select_action" class="form-control">
		        				<option value="">Seleccione 1 opción</option>
		        				<option value="0">Error</option>
		        				<option value="1">En evaluación</option>
		        				<option value="2">Pre-Aprobada</option>
		        				<option value="3">Aprobada</option>
		        				<option value="4">Rechazada</option>
		        			</select>
						</div>
	        		</div>
	        		<div class="col-md-12 div_reason" style="display: block;">
	        			<div class="form-group m-form__group">
	        				<label for="">Motivo:</label>
	        				<textarea name="" class="form-control" name="textarea_reason" id="textarea_reason"></textarea>
						</div>
	        		</div>
	        		<div class="col-md-12 div_approve" style="display: none;">
		        		<div class="row">
	                    	<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>N° Decreto alcaldicio que aprueba subvención</label>
			                        <input type="text" name="add_no_mayor_decree" id="add_no_mayor_decree" class="form-control approve_input" required>
			                    </div>
	                    	</div>
	                    	<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>Fecha convenio de entrega subvención</label>
			                        <input type="date" name="add_agreement_date" id="add_agreement_date" class="form-control approve_input" required>
			                    </div>
	                    	</div>
                    		<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>N° Decreto de pago</label>
			                        <input type="text" name="add_no_payment_decree" id="add_no_payment_decree" class="form-control approve_input">
			                    </div>
	                    	</div>
	                    	<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>Fecha de pago</label>
			                        <input type="date" name="add_payment_date" id="add_payment_date" class="form-control approve_input">
			                    </div>
	                    	</div>				            
	                    	<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>N° de session</label>
			                        <input type="text" name="add_no_session" id="add_no_session" class="form-control approve_input">
			                    </div>
	                    	</div>
	                    	<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>Fecha de sesion</label>
			                        <input type="date" name="add_session_date" id="add_session_date" class="form-control approve_input">
			                    </div>
	                    	</div>
				            <div class="col md-12">
	                    		<label>N° de cuotas de pago</label>
			                    <input type="number" name="add_no_payment_installments" id="add_no_payment_installments" class="form-control approve_input" min="0" >
				            </div>
	                    </div>
	        		</div>
            	</form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnSaveAction">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateApprovalSubsidy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titleModelApprovalSubsidy">Nueva aprobación subvención</span></h5>
                <input type="hidden" name="id_approval_subsidy" id="id_approval_subsidy" value="0">
                <input type="hidden" name="id_approval_subvention_id" id="id_approval_subvention_id" value="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateApprovalSubsidy">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveApprovalSubsidy">Guardar</button>
            </div>
        </div>
    </div>
</div>

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