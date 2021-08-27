<?php require_once '../common/header.php'; ?>
<?php if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_add') OR $obj_function->validarPermiso($_SESSION['permissions'],'subvention_edit')): ?>
 
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-contract"></i> Crear Subvención </h6>
			</div>
			<div class="col-md-6">
				<?php if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_list')): ?>
					<a href="subvention.php" class="btn btn-primary btn-sm float-right"> <i class="fas fa-arrow-left"></i> Volver </a>
				<?php endif; ?>
			</div>
		</div>	    		
	</div>
	<div class="card-body border-bottom-primary">
	    <form id="formCreateSubvention">


            <div class="card">
                <div class="card-header border-bottom">
                    <!-- Wizard navigation-->
                    <div class="nav nav-pills nav-justified flex-column flex-xl-row nav-wizard" id="cardTab" role="tablist">
                        <!-- Wizard navigation item 1-->
                        <a class="nav-item nav-p nav-link active" id="wizard1-tab" href="#wizard1-5556" data-toggle="tab" href="#wizard1" role="tab" aria-controls="nav-home" aria-selected="true">
                            <div class="wizard-step-icon">1</div>
                            <div class="wizard-step-text">
                                <div class="wizard-step-text-name">DATOS GENERALES Y DATOS DEL PROYECTO</div>
                            </div>
                        </a>
                        <!-- Wizard navigation item 2-->
                        <a class="nav-item nav-link" id="wizard2-tab" href="#wizard2" data-toggle="tab" href="#wizard2" role="tab" aria-controls="wizard2" aria-selected="false">
                            <div class="wizard-step-icon">2</div>
                            <div class="wizard-step-text">
                                <div class="wizard-step-text-name">REPRESENTANTES DE LA ORGANIZACIÓN</div>
                            </div>
                        </a>
                        <!-- Wizard navigation item 3-->
                        <a class="nav-item nav-link" id="wizard3-tab" href="#wizard3" data-toggle="tab" href="#wizard3" role="tab" aria-controls="wizard2" aria-selected="false">
                            <div class="wizard-step-icon">3</div>
                            <div class="wizard-step-text">
                                <div class="wizard-step-text-name">FINANCIAMIENTO</div>
                            </div>
                        </a>
                        <!-- Wizard navigation item 4-->
                        <a class="nav-item nav-link" id="wizard4-tab" href="#wizard4" data-toggle="tab" href="#wizard4" role="tab" aria-controls="wizard2" aria-selected="false">
                            <div class="wizard-step-icon">4</div>
                            <div class="wizard-step-text">
                                <div class="wizard-step-text-name">CRONOGRAMA DE ACTIVIDADES</div>
                            </div>
                        </a>
                         <!-- Wizard navigation item 5-->
                        <a class="nav-item nav-link" id="wizard5-tab" href="#wizard5" data-toggle="tab" href="#wizard5" role="tab" aria-controls="wizard2" aria-selected="false">
                            <div class="wizard-step-icon">5</div>
                            <div class="wizard-step-text">
                                <div class="wizard-step-text-name">SUBIR DOCUMENTOS</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="cardTabContent">
                        <!-- Wizard tab pane item 1-->
                        <div class="tab-pane py-5 py-xl-10 fade active show" id="wizard1-5556" role="tabpanel" aria-labelledby="wizard1-tab">
                            <div class="row justify-content-center">
                                <div class="col-xxl-6 col-xl-8">
                                    <h3 class="text-primary">Paso 1</h3>
                                    <h5 class="card-title mb-4">Ingresar los datos generales</h5>
                                                            
                                    <div class="row gx-3">
                                    	<div class="offset-8 col-md-4 mb-0">
                                            <label class="small mb-1" for="inputBirthday">Año de la subvención</label>
                                            <input class="form-control" id="inputYearSubvention" type="date" name="inputYearSubvention" placeholder="06/10/1988"  required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="small mb-1" for="inputOrgName">Nombre de la Organización</label>
                                            <input class="form-control" id="inputOrgName" name="inputOrgName" type="text" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="small mb-1" for="inputOrgRut">Rut de la Organización</label>
                                            <input class="form-control" id="inputOrgRut" name="inputOrgRut" type="text" required>
                                        </div>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-12">
                                            <label class="small mb-1" for="inputAddress">Dirección</label>
                                            <input class="form-control" id="inputAddress" name="inputAddress" type="text" required>
                                        </div>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-6">
                                            <label class="small mb-1" for="inputEmailAddress">Correo Electrónico</label>
                                            <input class="form-control" id="inputEmailAddress" name="inputEmailAddress" type="email"  required>
                                        </div>                            
                                        <div class="col-md-6 mb-md-0">
                                            <label class="small mb-1" for="inputPhone">Teléfono</label>
                                            <input class="form-control" id="inputPhone" name="inputPhone" type="text" >
                                        </div>
                                       
                                    </div>
                                    <hr class="my-4">
                                    <h5 class="card-title mb-4">Ingresar los datos del proyecto</h5>

                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-12">
                                            <label class="small mb-1" for="inputProName">Nombre del proyecto o programa a ejecutar</label>
                                            <input class="form-control" id="inputProName" name="inputProName" type="text" required="">
                                        </div>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-12">
                                            <label class="small mb-1" for="inputAddress">Objetivo del proyecto</label>
                                            <textarea class="form-control" id="inputProObj" name="inputProObj" rows="2" style="resize: none;"></textarea>
                                        </div>
                                    </div>                      

                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary btn-next-1" type="button">Siguiente</button>
                                    </div>
                                     
                                </div>
                            </div>
                        </div>
                        <!-- Wizard tab pane item 2-->
                        <div class="tab-pane py-5 py-xl-10 fade" id="wizard2" role="tabpanel" aria-labelledby="wizard2-tab">
                            <div class="row justify-content-center">
                                <div class="col-xxl-6 col-xl-10">
                                    <h3 class="text-primary">Step 2</h3>
                                    <h5 class="card-title mb-4">Ingresar representates de la organización</h5>
                                    <table class="table">
            							<thead>
            							    <tr>
            							      	<th scope="col">#</th>
            							      	<th scope="col">Nombre Completo</th>
            							      	<th scope="col">Dirección</th>
            							      	<th scope="col">Teléfono</th>
            							    </tr>
            							</thead>
            						  	<tbody>
            							    <tr>
            							      	<th scope="row">
                                                    PRESIDENTE
                                                    <input type="hidden" id="type1" value="Presidente">
                                                </th>
            							      	<td><input class="form-control inner_form" id="inputRepreName1" name="inputRepreName1" type="text"></td>
            							      	<td><textarea class="form-control inner_form" id="inputRepreAddress1" name="inputRepreAddress1" rows="2" style="resize: none;"></textarea></td>
            							      	<td><input class="form-control inner_form" id="inputReprePhone1" name="inputReprePhone1" type="text"></td>
            							    </tr>
            							    <tr>
            							      	<th scope="row">
                                                    VICEPRESIDENTE
                                                    <input type="hidden" id="type2" value="Vicepresidente">
                                                </th>
            							      	<td><input class="form-control inner_form" id="inputRepreName2" name="inputRepreName2" type="text"></td>
            							      	<td><textarea class="form-control inner_form" id="inputRepreAddress2" name="inputRepreAddress2" rows="2" style="resize: none;"></textarea></td>
            							      	<td><input class="form-control inner_form" id="inputReprePhone2" name="inputReprePhone2" type="text"></td>
            							    </tr>
            							    <tr>
            							      	<th scope="row">
                                                    SECRETARIO
                                                    <input type="hidden" id="type3" value="Secretario">
                                                </th>
            							      	<td><input class="form-control inner_form" id="inputRepreName3" name="inputRepreName3" type="text"></td>
            							      	<td><textarea class="form-control inner_form" id="inputRepreAddress3" name="inputRepreAddress3" rows="2" style="resize: none;"></textarea></td>
            							      	<td><input class="form-control inner_form" id="inputReprePhone3" name="inputReprePhone3" type="text"></td>
            							    </tr>
            							    <tr>
            							      	<th scope="row">
                                                    TESORERO
                                                    <input type="hidden" id="type4" value="Tesorero">
                                                </th>
            							      	<td><input class="form-control inner_form" id="inputRepreName4" name="inputRepreName4" type="text"></td>
            							      	<td><textarea class="form-control inner_form" id="inputRepreAddress4" name="inputRepreAddress4" rows="2" style="resize: none;"></textarea></td>
            							      	<td><input class="form-control inner_form" id="inputReprePhone4" name="inputReprePhone4" type="text"></td>
            							    </tr>
            						  	</tbody>
            						</table>
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-light disabled btnPrevious b-1" type="button">Anterior</button>
                                        <button class="btn btn-primary btn-next-2" type="button">Siguiente</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- Wizard tab pane item 3-->
                        <div class="tab-pane py-5 py-xl-10 fade" id="wizard3" role="tabpanel" aria-labelledby="wizard3-tab">
                            <div class="row justify-content-center">
                                <div class="col-xxl-6 col-xl-10">
                                    <h3 class="text-primary">Pase 3</h3>
                                    <h5 class="card-title mb-4">Ingresar datos del financiamiento</h5>
                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-3 form-group">
                                            <label class="btn btn-primary btn-file mt-4">
                                                Cargar Archivos <span class="requerido" >*</span>
                                                <input type="file" id="imagenes" required name="images[]" style="display: none;" multiple accept='file_extension|image/*' >
                                            </label>
                                            <p>Seleccione hasta 10 imagenes</p>  
                                        </div>
                                        <div class="mb-3 col-md-9 form-group row gx-3" id="lista">
                                            <p>Debe Cargar 1 foto minimo</p>
                                        </div>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-3 form-group">
                                            <label>Cantidad de compras (número) </label>
                                            <input type="number" name="inputCantCompras" id="inputCantCompras" class="form-control" required min="0" />
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" >
                                                <tbody id="dynamic_field">
                                                    <tr class="dynamic_field">
                                                        <td><input type="text" name="inputDetails_1" id="inputDetails_1" data-op="${i}" placeholder="Detalle inversión 1" class="form-control inner_form detalle_list" /></td>
                                                        <td><input type="number" name="inputUnityPrice_1" id="inputUnityPrice_1" data-op="1" placeholder="Precio unitario" class="form-control inner_form unity_price_list" step="0.1" /></td>
                                                        <td><input type="number" name="inputQuantity_1" id="inputQuantity_1" data-op="1" placeholder="Cantidad" class="form-control quantity_list" /></td>
                                                        <td><input type="text" name="inputTotalPrice_1" id="inputTotalPrice_1" data-op="1" placeholder="Precio Total" class="form-control inner_form total_price_list" readonly="" /></td>

                                                        <td><button type="button" name="add" id="add" class="btn btn-success">Agregar</button></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>                                            
                                                        <td><input type="number" name="inputOrgAmount" id="inputOrgAmount" placeholder="Aporte de la organizació" class="form-control inner_form" step="0.1" /></td>
                                                        <td><input type="text" name="inputTotalSumPrice" id="inputTotalSumPrice" placeholder="Total de la suma" class="form-control inner_form" disabled="" /></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                             
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <h5 class="card-title mb-4">Ingresar los datos de los beneficiarios de la inversión </h5>

                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-4">
                                            <label class="small mb-1" for="inputProName">Directos</label>
                                            <input type="number" name="inputDirectAmount" id="inputDirectAmount" class="form-control inner_form" step="0.1" />
                                        </div>
                                   
                                        <div class="mb-3 col-md-4">
                                            <label class="small mb-1" for="inputProName">Indirectos</label>
                                            <input type="number" name="inputIndirectAmount" id="inputIndirectAmount" class="form-control inner_form" step="0.1" />
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="small mb-1" for="inputProName">Total </label>
                                            <input type="text" name="inputTotalSumBene" id="inputTotalSumBene" placeholder="Total de la suma" class="form-control" disabled="" />
                                        </div>
                                        
                                    </div> 
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-light disabled btnPrevious b-2" type="button">Anterior</button>
                                        <button class="btn btn-primary btn-next-3" type="button">Siguiente</button>
                                    </div>          
                                </div>
                            </div>
                        </div>
                        <!-- Wizard tab pane item 4-->
                        <div class="tab-pane py-5 py-xl-10 fade" id="wizard4" role="tabpanel" aria-labelledby="wizard4-tab">
                            <div class="row justify-content-center">
                                <div class="col-xxl-6 col-xl-10">
                                    <h3 class="text-primary">Paso 4</h3>
                                    <h5 class="card-title mb-4">Ingresar actividades</h5>
                                    <div class="mb-3 col-md-3 form-group">
                                        <label>Cantidad de actividades (número) </label>
                                        <input type="number" name="inputCantActivities" id="inputCantActivities" class="form-control" required min="0" />
                                    </div>
                                     <table class="table" id="dynamic_activities">                                                
                                        <tr class="dynamic_activities">
                                            <td class="col-md-8"><input type="text" name="inputActivity_1" id="inputActivity_1" data-op="1" placeholder="Descripción de actividad" class="form-control inner_form activity_list" /></td>
                                            <td><input type="text" name="inputMonthAct_1" id="inputMonthAct_1" data-op="1" placeholder="Mes" class="form-control inner_form month_list"/></td>
                                            <td><button type="button" name="add" id="add_act" class="btn btn-success">Agregar</button></td>
                                        </tr>
                                    </table>
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-light disabled btnPrevious b-3" type="button">Anterior</button>
                                        <button class="btn btn-primary btn-next-4" type="button">Siguiente</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Wizard tab pane item 4-->
                        <div class="tab-pane py-5 py-xl-10 fade" id="wizard5" role="tabpanel" aria-labelledby="wizard5-tab">
                            <div class="row justify-content-center">
                                <div class="col-xxl-6 col-xl-8">
                                    <h3 class="text-primary">Step 5</h3>
                                    <h5 class="card-title mb-4">Subir Documentos</h5>
                                    <div class="row gx-3">
                                        <div class="mb-3 col-md-3 form-group">
                                            <label class="btn btn-primary btn-file mt-4">
                                                Cargar Archivos <span class="requerido" >*</span>
                                                <input type="file" id="archivos" required name="archivos[]" style="display: none;" multiple accept='file_extension|image/*' >
                                            </label>
                                            <p>Seleccione los archivos correspondientes</p>  
                                        </div>
                                        <div class="mb-3 col-md-9 form-group row gx-3" id="lista_2">
                                            <p>Debe Cargar 1 archivo minimo</p>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between">
                                        <input type="hidden" id="id_subvention">
                                        <button class="btn btn-light btnPrevious b-3" type="button">Anterior</button>
                                        <button class="btn btn-primary" id="btnSavedSubvention" type="button">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


	    </form>
	</div>
</div> 

<!-- <script src="https://www.gstatic.com/firebasejs/6.1.0/firebase.js"></script> -->

<script src="../controllers/subvention_add.js"></script>

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