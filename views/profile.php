<?php require_once '../common/header.php' ?>

<div class="card">
	<div class="container mt-4 mb-2">
		<div class="row">
	        <div class="col-md-4">
	            <div class="card shadow text-center">
	                <div class="card-body">
	                    <div class="d-flex flex-column align-items-center text-center">
	                        <img src="" class="rounded-circle my-3 img-fluid" id="img_perfil" alt="Admin" style="max-height: 150px;"/>
	                        <input type="hidden" name="path_foto" id="path_foto" class="form-control">
	                    </div>
	                    <form id="formFoto" class=""> 
	                        <div class="form-group float-right">
	                            <input type="file" id="edit_foto_perfil" name="edit_foto_perfil" class="form-control" style="font-size:12px">
	                        </div>
	                    </form>
	                    <button class="btn btn-primary btn-sm" id="btn_save_foto_perfil">Modificar Imagen</button>
	                </div>
	            </div>
	        </div>
	        <div class="col-md-8">
	        	<ul class="nav nav-tabs mb-2" id="myTabPerfil" role="tablist">
			        <li class="nav-item">
			            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
			                <i class="fas fa-id-badge mr-1"></i>
			                Informacion Personal
			            </a>
			        </li>
			        <li class="nav-item">
			            <a class="nav-link" id="contraseña-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
			                <i class="fas fa-key"></i>
			                Cambiar Contraseña
			            </a>
			        </li>
			    </ul>
			    <div id="div_informacion_personal">
			    	<form id="formProfile" class=""> 
			            <div class="row">
			                <div class="col-md-4">
			                    <div class="form-group m-form__group"> 
			                        <label>Rut</label>
			                        <input type="hidden" name="id_user" id="id_user" class="form-control" required="" readonly="">
			                        <input type="text" name="add_rut" id="add_rut" class="form-control" required="">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group m-form__group"> 
			                        <label>Nombres</label>
			                        <input type="text" name="add_name" id="add_name" class="form-control" required="">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group m-form__group"> 
			                        <label>Apellidos</label>
			                        <input type="text" name="add_last_name" id="add_last_name" class="form-control">
			                    </div>
			                </div>
			            </div>
			            <div class="row">
			            	<div class="col-md-4">
			                    <div class="form-group m-form__group"> 
			                        <label>Nombre de usuario</label>
			                        <input type="text" name="add_username" id="add_username" class="form-control" required="">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group m-form__group"> 
			                        <label>Numero de Contacto</label>
			                        <input type="text" name="add_phone" id="add_phone" class="form-control" required="">
			                    </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="form-group m-form__group"> 
			                        <label>Email</label>
			                        <input type="text" name="add_email" id="add_email" class="form-control" required="">
			                    </div>
			                </div>	                
			            </div>
			            <div class="row">
			            	<div class="col-md-12">
			                    <div class="form-group m-form__group"> 
			                        <label>Rol</label>
			                        <textarea name="add_role" id="add_role" class="form-control" readonly="" style="height: 40px;"></textarea>
			                    </div>
			                </div>
			            </div>
			        </form>
		            <button type="" class="btn btn-success btn-sm float-right" id="btn_save_profile">Actualizar Datos</button>
			    </div>
			    <div id="div_contraseña" style="display: none;">
	            	<form id="formPassword">
		                <div class="form-group">
		                    <label for="inputEmail4">Contraseña Actual</label>
		                    <input type="text" class="form-control" name="old_password" id="old_password" placeholder="********" value="">
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail4">Nueva contraseña</label>
		                    <input type="text" class="form-control" name="new_password" id="new_password" placeholder="********" value="">
		                </div>
		                <div class="form-group">
		                    <label for="inputEmail4">Repita la nueva contraseña</label>
		                    <input type="text" class="form-control" name="confirm_password" id="confirm_password" placeholder="********" value="">
		                </div>
		            </form>
		            <button type="" class="btn btn-success btn-sm float-right" id="btn_save_password">Actualizar Contraseña</button>
			    </div>
	        </div>
	    </div>
	</div>
</div>

<div class="modal fade" id="modalArchivosDelContrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Archivos del Contrato</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card shadow mb-4 card-archivos">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="m-0 font-weight-bold text-primary" style="font-size: 14px">
                                    Archivos del contrato 
                                </h6>
                            </div>
                        </div>              
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="archivosDataTable" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th id="testcolum">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase.js"></script>
<script src="../controllers/profile.js"></script>
<?php require_once '../common/footer.php' ?>  