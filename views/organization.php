<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'organization_list')): ?>
	<style>
	.error{
		max-width: 10rem;
	}	
	</style>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<div class="row">
				<div class="col-md-6">
					<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Organizaciones</h6>
				</div>
				<div class="col-md-6">
					<?php //if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_add')): ?>
					<!-- <button type="button" class="btn btn-primary btn-sm float-right" id="btn_newAccountability"> <i class="fas fa-user"></i> Nueva Rendicion
					</button> -->
					<?php //endif; ?>
				</div>
			</div>	    		
		</div>
		<div class="card-body border-bottom-primary">
		    <div class="table-responsive">
		        <table class="table table-bordered" id="organizationDataTable" cellspacing="0">
		            <thead>
		                <tr>
		                	<th>#</th>
		                    <th>Rut</th>
		                    <th>Nombre</th>
		                    <th>Dirección</th>
		                    <th>Fecha Creación</th>
		                    <th>Acciones</th>
		                </tr>
		            </thead>
		            <tbody>
	                </tbody>
		        </table>
		    </div>
		</div>
	</div>

	<?php if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_add') OR $obj_function->validarPermiso($_SESSION['permissions'],'accountability_edit')): ?>
	<div class="modal fade" id="modalCreateAccountability" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-lg" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel"><span id="titleModelAccountability">Nueva Rendicion</span></h5>
	                <input type="hidden" name="id_accountability" id="id_accountability" value="0">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
	                <form id="formCreateAccountability">
	                	<div class="row">
	                		<div class="col-md-4">
	                    		<div class="form-group m-form__group"> 
			                        <label>N° Folio de la subvencion</label>
			                        <input type="text" name="add_num_folio" id="add_num_folio" class="form-control" required>
			                        <input type="hidden" name="id_subvention" id="id_subvention">
			                    </div>
	                    	</div>
	                		<div class="col-md-4">
	                			<div class="form-group m-form__group"> 
			                        <label>Nombre de la Organizacion</label>
			                        <input type="text" name="add_name_organization" id="add_name_organization" class="form-control" readonly="">
			                    </div>
	                		</div>
	                		<div class="col-md-4">
	                			<div class="form-group m-form__group"> 
			                        <label>Nombre del Proyecto</label>
			                        <input type="text" name="add_name_project" id="add_name_project" class="form-control" readonly="">
			                    </div>
	                		</div>
	                	</div>
	                    <div class="row">
	                    	<div class="col-md-3">
	                			<div class="form-group m-form__group" title="Representante de la Rendición"> 
			                        <label>Representante</label>
			                        <input type="text" name="add_name_represent" id="add_name_represent" class="form-control">
			                    </div>
	                		</div>
	                    	<div class="col-md-3">
	                    		<div class="form-group m-form__group"> 
			                        <label>Telefono</label>
			                        <input type="text" name="add_phone" id="add_phone" class="form-control" readonly >
			                    </div>
	                    	</div>
	                    	<div class="col-md-4">
	                    		<div class="form-group m-form__group"> 
			                        <label>Email</label>
			                        <input type="text" name="add_email" id="add_email" class="form-control" readonly>
			                    </div>
	                    	</div>
	                    	<div class="">
	                    		<div class="form-group m-form__group"> 
			                        <label>Facturas</label>
			                        <input type="text" name="add_invoice_number" id="add_invoice_number" min="1" class="form-control" style="max-width: 60px" readonly>
			                    </div>
	                    	</div>
	                    	<div class="col-md-1" title="Agregar factura">
	                    		<div class="row" style="margin-top: 35px; margin-left:10px" title=>
	                    			<i class="fas fa-plus" id="btn_add_invoice" style="font-size: 28px; cursor: pointer;" title="Agregar factura"></i>
	                    		</div>                     		
	                    	</div>
	                    </div>

	                    <table class="table table-bordered" >
	                    	<tbody id="invoices">
	                    	</tbody>
	                    </table>
	                    <div class="row">
	                    	<div class="col-md-4">
	                    		<div class="form-group m-form__group"> 
			                        <label>Monto Entregado</label>
			                        <input type="number" name="add_mount_delivered" id="add_mount_delivered" class="form-control" step="0.1" required>
			                    </div>
	                    	</div>
	                    	<div class="col-md-4">
	                    		<div class="form-group m-form__group"> 
			                        <label>Monto Rendido</label>
			                        <input type="text" name="add_yielded" id="add_yielded" class="form-control" value="0" required readonly="">
			                    </div>
	                    	</div>
	                    	<div class="col-md-4">
	                    		<div class="form-group m-form__group"> 
			                        <label>Monto Reintegrado</label>
			                        <input type="number" name="add_amount_refunded" id="add_amount_refunded" class="form-control" step="0.1" required>
			                        <small id="span_amount_refunded" class="text-danger" style="display: none;">Subir comprobante de restitución de fondos</small>
			                    </div>
	                    	</div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>Saldo</label>
			                        <input type="text" name="add_balance" id="add_balance" class="form-control" readonly required>
			                    </div>
	                    	</div>
	                    	<div class="col-md-6">
	                    		<div class="form-group m-form__group"> 
			                        <label>Fecha Ingreso de Rendición</label>
			                        <input type="text" name="add_date_surrender_income" id="add_date_surrender_income" class="form-control dateAcc" required>
			                    </div>
	                    	</div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-md-4" id="div_lista_beneficiarios">
	                    		<div class="form-group m-form__group"> 
			                        <label class="pb-4">Lista de Beneficiarios</label>
				                    <input type="file" id="lista_beneficiarios" name="lista_beneficiarios" style="display: block;" class="form-control" accept='file_extension|image/*' required>
			                    </div>
			                    <div class="col-md-12 form-group row" id="lista_1" style="display:none">
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 1</small>
			                    	</div>
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 2</small>
			                    	</div>
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 3</small>
			                    	</div>
			                	</div>
	                    	</div>
	                    	<div class="col-md-4" id="div_accountability_photos">
	                    		<div class="form-group m-form__group"> 
			                        <label class="pb-4">Fotografías de lo adquirido</label>
			                        <input type="file" id="accountability_photos" name="accountability_photos[]" style="display: block;" multiple class="form-control">
			                    </div>
			                    <div class="col-md-12 form-group row" id="lista_2" style="display:none">
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 1</small>
			                    	</div>
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 2</small>
			                    	</div>
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 3</small>
			                    	</div>
			                	</div>
	                    	</div>
	                    	<div class="col-md-4" id="div_comprobante_restitucion_fondos" style="display:none">
	                    		<div class="form-group m-form__group"> 
			                        <label class="">Comprobante de restitución de fondos</label>
			                        <input type="file" id="comprobante_restitucion_fondos" name="comprobante_restitucion_fondos" class="form-control" accept='file_extension|image/*'>
			                    </div>
			                    <div class="col-md-12 form-group row" id="lista_3" style="display:none">
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 1</small>
			                    	</div>
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 2</small>
			                    	</div>
			                    	<div class="col-md-4 text-center">
			                    		<img src="../assets/img/file.png" class="rounded img-fluid" style="">
			                    		<small>test 3</small>
			                    	</div>
			                	</div>
	                    	</div>
	                    </div>
	                </form>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
	                <button type="button" class="btn btn-success" id="btn_saveAccountability">Guardar</button>
	            </div>
	        </div>
	    </div>
	</div>
	<?php endif; ?>

	<?php if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_view_list')): ?>
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
				                    <th>Nombre</th>
				                    <th>Tipo</th>
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
	            	<input type="hidden" name="id_accountability_documents" id="id_accountability_documents">
	            	<form id="formUploadDocument">
						<div class="col-md-12">
							<div class="form-group m-form__group">
								<label for="">Tipo de archivo</label>
			        			<select name="select_type_documents" id="select_type_documents" class="form-control">
			        				<option value="">Selecciona una opción</option>
			        				<option value="1">Lista de beneficiarios</option>
			        				<option value="2">Comprobante de restitución de fondos</option>
			        				<option value="3">Fotografías de lo adquirido</option>
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
	<?php endif; ?>

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
	            	<input type="hidden" name="id_accountability_action" id="id_accountability_action">
	            	<form id="formAction">
						<div class="col-md-12">
							<div class="form-group m-form__group">
								<label for="">Acción:</label>
			        			<select name="select_action" id="select_action" class="form-control">
			        				<option value="">Seleccione 1 opción</option>
			        				<option value="0">Pendiente</option>
			        				<option value="1">Aprobada</option>
			        				<option value="2">Observada</option>
			        				<option value="3">Reintegro</option>
			        			</select>
							</div>
		        		</div>
		        		<div class="col-md-12 div_reason" style="display: block;">
		        			<div class="form-group m-form__group">
		        				<label for="">Motivo:</label>
		        				<textarea name="" class="form-control" name="textarea_reason" id="textarea_reason" required></textarea>
							</div>
		        		</div>
		        		<div class="col-md-12 div_refund" style="display: none;">
			        		<div class="row">
		                    	<div class="col-md-12">
		                    		<div class="form-group m-form__group"> 
				                        <label>Archivo</label>
				                        <input type="file" name="add_file_refund" id="add_file_refund" class="form-control" accept='file_extension|image/*'>
				                    </div>
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

	<script src="../controllers/organization.js"></script>

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