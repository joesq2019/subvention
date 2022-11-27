<div class="modal fade" id="modalCreateRol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Rol</h5>
                <input type="hidden" name="id_role" id="id_role" value="0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id='data_permisos'>
                    <div class="row ">
                        <div class="col-md-4 form-group">
                            <label for="">Nombre del Rol</label>
                        <input type="text" class="form-control" name="add_name" id="add_name">
                        </div>
                        
                    </div>
                    <div class="row">
                        <?php foreach($obj_function->users_permissions() as $key => $value): ?>
                            <div class="col-md-6 card shadow pl-1 mb-1">
                                <div class="header mt-3 ml-3">
                                    <h6 class="title"><?php echo $value['icon'].' '.$value['title']; ?></h6>
                                </div><hr>
                                <div class="form-check">
                                    <?php foreach($value['keys'] as $k => $v): ?>                                       
                                        <input type="checkbox" value="true" class="kick" checked="checked" name="<?php echo $k; ?>" id="<?php echo $k; ?>">
                                        <label for="<?php echo $k; ?>" style="font-size: 90%"><?php echo $v; ?></label>
                                        <br>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary tr" key="" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_saveRole">Guardar</button>
            </div>
        </div>
    </div>
</div>