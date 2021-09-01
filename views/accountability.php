<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_list')): ?>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Rendicion de Cuentas </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_add')): ?>
				<button type="button" class="btn btn-primary btn-sm float-right" id="btn_newAccountability"> <i class="fas fa-user"></i> Nueva Rendicion
				</button>
				<?php endif; ?>
			</div>
		</div>	    		
	</div>
	<div class="card-body border-bottom-primary">
	    <div class="table-responsive">
	        <table class="table table-bordered" id="accountabilityDataTable" cellspacing="0">
	            <thead>
	                <tr>
	                	<th>#</th>
	                    <th>Rut</th>
	                    <th>Organización</th>
	                    <th>Representante</th>
	                    <th>Monto Entregado</th>
	                    <th>Monto Rendido</th>
	                    <th>Fecha Ingreso</th>
	                    <th>Status</th>
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
		                        <label>Rut</label>
		                        <input type="text" name="add_rut_organization" id="add_rut_organization" class="form-control" required>
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
		                        <input type="text" name="add_phone" id="add_phone" class="form-control" readonly required>
		                    </div>
                    	</div>
                    	<div class="col-md-4">
                    		<div class="form-group m-form__group"> 
		                        <label>Email</label>
		                        <input type="text" name="add_email" id="add_email" class="form-control" readonly required>
		                    </div>
                    	</div>
                    	<div class="col-md-2">
                    		<div class="form-group m-form__group"> 
		                        <label>Facturas</label>
		                        <select name="add_invoice_number" id="add_invoice_number" class="form-control" required="">
		                        	<option value="0">0</option>
		                        	<option value="1">1</option>
		                        	<option value="2">2</option>
		                        	<option value="3">3</option>
		                        	<option value="4">4</option>
		                        	<option value="5">5</option>
		                        	<option value="6">6</option>
		                        	<option value="7">7</option>
		                        	<option value="8">8</option>
		                        	<option value="9">9</option>
		                        	<option value="10">10</option>
		                        	<option value="11">11</option>
		                        	<option value="12">12</option>
		                        	<option value="13">13</option>
		                        	<option value="14">14</option>
		                        	<option value="15">15</option>
		                        	<option value="16">16</option>
		                        	<option value="17">17</option>
		                        	<option value="18">18</option>
		                        	<option value="19">19</option>
		                        	<option value="20">20</option>
		                        </select>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveAccountability">Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAddBeneficiaries">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Subir Archivo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class='form-group text-center'>
                    <input type="hidden" id="id_related">
                   <input type="file" name="file-1" id="file-1" class="inputfile inputfile-1" />
                    <label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Escoger Archivo &hellip;</span></label>
                    <div class="position-relative form-group">
                        <img src="" id="imagen1" class="img-thumbnail previsualizar1" width="150px" alt="imagen default">
                        <input type="hidden" class="form-control" id="add_id_accountability">
                	</div>
            	</div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSaveBeneficiarie">Subir</button>
            </div>
        </div>         
    </div>        
</div>
 
<div class="modal fade" id="modalAddDocumentation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Subir Archivos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<input type="hidden" class="form-control" id="add_id_accountability1">
            	<div class="row">
                <div class='form-group col-md-5'>
                   <input type="file" name="file-2" id="file-2" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple/>
                    <label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Fotografías de lo adquirido  &hellip;</span></label>
                    
                </div>
                <div class="position-relative form-group col-md-7 row">

                        <img src="" id="imagen2" class="img-thumbnail previsualizar2 col-md-4" width="150px" alt="imagen default">
                        
                	</div>
                	</div>
                <div class='form-group text-center'>
                    <input type="file" name="file-3" id="file-3" class="inputfile inputfile-1" />
                    <label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Comprobante de restitución de fondos  &hellip;</span></label>
                    <div class="position-relative form-group">
                        <img src="" id="imagen3" class="img-thumbnail previsualizar3" width="150px" alt="imagen default">
                        
                	</div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSaveDocumentation">Subir</button>
            </div>
        </div>         
    </div>        
</div>

<?php endif; ?>

<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase.js"></script>

<script src="../controllers/accountability.js"></script>

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