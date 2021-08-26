<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'user_list')): ?>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Rendicion de Cuentas </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'user_add')): ?>
				<button type="button" class="btn btn-primary btn-sm float-right" id="btn_newUser"> <i class="fas fa-user"></i> Nueva Rendicion
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nueva Rendicion</h5>
                <input type="hidden" name="id_user" id="id_user" value="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateUser">
                	<div class="row">
                		<div class="col-md-4">
                    		<div class="form-group m-form__group"> 
		                        <label>Rut</label>
		                        <input type="text" name="add_rut_organization" id="add_rut_organization" class="form-control" required>
		                    </div>
                    	</div>
                		<div class="col-md-4">
                			<div class="form-group m-form__group"> 
		                        <label>Nombre de la Organizacion</label>
		                        <input type="text" name="add_name_organization" id="add_name_organization" class="form-control" required="">
		                    </div>
                		</div>
                		<div class="col-md-4">
                			<div class="form-group m-form__group"> 
		                        <label>Nombre del Proyecto</label>
		                        <input type="text" name="add_name_project" id="add_name_project" class="form-control">
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
		                        <input type="text" name="add_phone" id="add_phone" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-4">
                    		<div class="form-group m-form__group"> 
		                        <label>Email</label>
		                        <input type="text" name="add_email" id="add_email" class="form-control" required>
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
                    	<!-- <div class="col-md-1">
                    		<div class="form-group m-form__group" style="margin-top: 37px"> 
		                        <i class="fas fa-plus pr-2" style="cursor: pointer" id="more_invoice"></i>
		                        <i class="fas fa-minus" style="cursor: pointer" id="less_invoice"></i>
		                    </div>
                    	</div> -->
                    </div>

                    <div id="invoices">

                    </div>
                    <div class="row">
                    	<div class="col-md-4">
                    		<div class="form-group m-form__group"> 
		                        <label>Monto Entregado</label>
		                        <input type="email" name="add_email" id="add_email" class="form-control" required>
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
		                        <input type="text" name="add_phone" id="add_phone" class="form-control" required>
		                    </div>
                    	</div>
                    </div>
                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Saldo</label>
		                        <input type="text" name="add_phone" id="add_phone" class="form-control" required>
		                    </div>
                    	</div>
                    	<div class="col-md-6">
                    		<div class="form-group m-form__group"> 
		                        <label>Ingreso de Rendición</label>
		                        <input type="text" name="add_phone" id="add_phone" class="form-control" required>
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