<?php
include '../common/login_timeout.php';
require_once '../common/db.php';
require_once '../common/bdMysql.php';
require_once '../common/function.php';

$obj_function = new coFunction();
$obj_bdmysql = new coBdmysql();

foreach ($_POST as $i_dato => $dato_) {
    @$$i_dato = addslashes($obj_function->evalua_array($_POST, $i_dato));
}

switch ($method) {
    case 'accountabilityList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'r.rut',
            1 => 'r.name',
            2 => 'a.name_represent',
            3 => 'a.amount_delivered',
            4 => 'a.amount_yielded',
            5 => 'a.date_admission',
            6 => 'a.status'
        );

        $id_user = $_SESSION['id_user'];

        $sql = "SELECT s.status as status_subvention, a.*, r.rut, r.name, r.phone, r.email, s.name_proyect FROM accountability a INNER JOIN subvention s ON a.id_subvention = s.id INNER JOIN organitation r ON s.id_organitation = r.id";
        if($id_organization > 0){
            $sql .= " WHERE r.id = $id_organization";
        }
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        // print_r($sql);exit();
        
        if (!empty($requestData['search']['value'])) {
            if($id_organization > 0){
                $sql .= " AND (r.rut LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR r.name LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.name_represent LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.amount_delivered LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.amount_yielded LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.date_admission LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.status LIKE '%" . $requestData['search']['value'] . "%')";
            }else{
                $sql .= " WHERE r.rut LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR r.name LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.name_represent LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.amount_delivered LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.amount_yielded LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.date_admission LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR a.status LIKE '%" . $requestData['search']['value'] . "%'";
            }            
        } 
        
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalFiltered = is_array($query) ? count($query) : 0;
        
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        $query = $obj_bdmysql->query($sql, $dbconn);

        $data = array();
        if (is_array($query)) {
            foreach ($query as $row) {
                $s = array();
                $botones = '';
                $status = '';

                if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="accountabilityController.edit(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>';
                    
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_view_list')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Ver Documentos" onclick="accountabilityController.viewDocuments(' . $row["id"] . ')"><i class="fas fa-folder" title="Ver Documentos" aria-hidden="true"></i></a></button>';
                    
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_approve')){
                    if($row['status_subvention'] == 3){
                        if($row['status'] != 1){
                            $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Cambiar estatus" onclick="accountabilityController.actions(' . $row["id"] . ')"><i class="far fa-check-circle"></i></a></button>';
                        }else{
                            $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Rendicion ya aprobada" disabled><i class="far fa-check-circle"></i></a></button>';
                            $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Generar Certificado" onclick="accountabilityController.certificate(' . $row["id"] . ')"><i class="fas fa-file" title="Generar Certificado" aria-hidden="true"></i></a></button>';
                        }                        
                    }else{
                       $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Debe aprobar la subvención" disabled><i class="far fa-check-circle"></i></a></button>';
                    }
                }

                if($row['status'] == 0) $status = '<span class="badge badge-warning" title="'.$row['message'].'">Pendiente</span>';
                if($row['status'] == 1) $status = '<span class="badge badge-success" title="'.$row['message'].'">Aprobada</span>';
                if($row['status'] == 2) $status = '<span class="badge badge-info" title="'.$row['message'].'">Observada</span>';
                if($row['status'] == 3) $status = '<span class="badge badge-danger" title="'.$row['message'].'">Reintegro</span>';

                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = $row['rut'];
                $nestedData[] = '<center>' . html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>'; 
                $nestedData[] = '<center>' . html_entity_decode($row['name_represent'], ENT_QUOTES | ENT_HTML401, "UTF-8") .'</center>';   
                $nestedData[] = '<center>' . utf8_encode($row['amount_delivered']) . '</center>';
                $nestedData[] = '<center>' . utf8_encode($row['amount_yielded']) . '</center>';
                $nestedData[] = '<center>' . utf8_encode($row['date_admission']) . '</center>';
                $nestedData[] = '<center>' . utf8_encode($status) . '</center>';
                $nestedData[] = '<center>' . $botones . '</center>'; 

                $data[] = $nestedData;
            }
        }
        $name_organization = "";
        if($id_organization > 0){
            $sql = "SELECT name FROM organitation WHERE id = $id_organization";
            $query = $obj_bdmysql->query($sql, $dbconn);
            $name_organization = $query[0]['name'];
        }
        ## Response
        $json_data = array(           
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "name_organization" => $name_organization
        );//print_r($json_data);exit();

        echo json_encode($json_data);
        /////////////////////////////////////////////////////////////////////////////////////////
    break;

    case 'saveNewAccountability':
        $fecha = date('Y-m-d');
        //print_r($_POST);exit;

        if ($id == 0) {
            $sql   = "SELECT id FROM accountability WHERE id_subvention = $id_subvention";
            $query = $obj_bdmysql->query($sql, $dbconn);
            if(is_array($query)){
                $out['code'] = 204;
                $out['message'] = "Ya existe una rendición de cuenta para la subvención con el numero de folio: $id_subvention";
                echo json_encode($out);die();
            }

            $out['code'] = 204;
            $out['message'] = 'Error Insert...!';

            $campo = "id_subvention, name_represent, number_invoices, amount_delivered, amount_yielded, amount_refunded, balance, date_admission, status";
            $valor = "'$id_subvention', '$add_name_represent', '$add_invoice_number', '$add_mount_delivered', '$add_yielded', '$add_amount_refunded', '$add_balance', '$add_date_surrender_income', 0";
            $accountability_id = $obj_bdmysql->insert("accountability", $campo, $valor, $dbconn);
            //$sql = "INSERT INTO user ($campo) VALUES ($valor)";
            //print_r($sql);exit;    
            
            $cant =   count((array)$_POST['invoices_array']);
            if ($cant > 0) {
                foreach ($_POST['invoices_array'] as $value ) {
                    $date = $value[0]['value'];                    
                    $amount = $value[1]['value'];
                    $detail = $value[2]['value'];

                    $campo = "id_accountability, date, detail, amount";     
                    $valor = "'$accountability_id', '$date', '$detail' ,'$amount'";
                    $obj_bdmysql->insert("invoices", $campo, $valor, $dbconn);
                    // $sql = "INSERT INTO user ($campo) VALUES ($valor)";
                    // print_r($sql);exit;                       
                }
            }        

            if ($accountability_id > 0) {
                $out['code'] = 200;
                $out['message'] = 'La rendición de cuenta fué creada exitosamente..!';
                $out['accountability_id'] = $accountability_id;
            }           
        }

        if ($id != 0) {
            //print_r($_POST);
            $out['code'] = 204;
            $out['message'] = 'Error Updated...!';
                $campo = "id_subvention='$id_subvention', name_represent='$add_name_represent', number_invoices='$add_invoice_number', amount_delivered='$add_mount_delivered', amount_yielded='$add_yielded', amount_refunded='$add_amount_refunded', balance='$add_balance', date_admission='$add_date_surrender_income'";

            $where = "id = '$id'";
            $update_accountability = $obj_bdmysql->update("accountability", $campo, $where, $dbconn);
            //$sql = "UPDATE `user` SET $campo WHERE $where";print($sql);exit();

            $obj_bdmysql->delete("invoices", "id_accountability= $id", $dbconn);
            $cant =   count((array)$_POST['invoices_array']);
            if ($cant > 0) {
                foreach ($_POST['invoices_array'] as $value ) {
                    $date = $value[0]['value'];                    
                    $amount = $value[1]['value'];
                    $detail = $value[2]['value'];

                    $campo = "id_accountability, date, detail, amount";     
                    $valor = "$id, '$date', '$detail' ,'$amount'";
                    $obj_bdmysql->insert("invoices", $campo, $valor, $dbconn);
                    // $sql = "INSERT INTO user ($campo) VALUES ($valor)";
                    // print_r($sql);exit;                       
                }
            }        
            // exit;
            if ($update_accountability > 0) {
                $out['code'] = 200;
                $out['message'] = 'La rendición de cuenta fué actualizada exitosamente..!';
                $out['accountability_id'] = $id;
            }
            
        }

        echo json_encode($out);
    break;

    case 'editAccountability':
        $sql = "SELECT a.*, r.rut, r.name, r.phone, r.email, s.name_proyect
        FROM accountability a
        INNER JOIN accountability_files af ON af.id_accountability  = a.id
        INNER JOIN subvention s ON a.id_subvention = s.id 
        INNER JOIN organitation r ON s.id_organitation = r.id WHERE a.id = $id";
        $query = $obj_bdmysql->query($sql, $dbconn);
        if (is_array($query)) {
            $obj = $query[0];

            $out['id_accountability'] = $obj['id'];
            $out['id_subvention']      = $obj['id_subvention'];
            $out['rut']      = $obj['rut'];
            $out['name']      = $obj['name'];
            $out['phone']  = $obj['phone'];
            $out['email']     = $obj['email'];
            $out['name_proyect']  = $obj['name_proyect'];
            $out['name_represent']      = $obj['name_represent'];
            $out['number_invoices']      = $obj['number_invoices'];
            $out['amount_delivered']  = $obj['amount_delivered'];
            $out['amount_yielded']     = $obj['amount_yielded'];
            $out['amount_refunded']  = $obj['amount_refunded'];
            $out['balance'] = $obj['balance'];
            $out['date_admission']      = $obj['date_admission'];
            $out['status']      = $obj['status'];

            $sql = "SELECT id, name, `path`, url FROM `accountability_files` WHERE id_accountability = $id AND type = 1";
            $query = $obj_bdmysql->query($sql, $dbconn);
            $beneficiarios = $query[0];

            if(is_array($beneficiarios)) $out['beneficiarios_file'] = $beneficiarios;

            $sql = "SELECT id, name, `path`, url FROM `accountability_files` WHERE id_accountability = $id AND type = 2";
            $query = $obj_bdmysql->query($sql, $dbconn);
            $comprobante = $query;
            if(is_array($comprobante)) $out['comprobante_file'] = $comprobante;

            $sql = "SELECT id, name, `path`, url FROM `accountability_files` WHERE id_accountability = $id AND type = 3";
            $query = $obj_bdmysql->query($sql, $dbconn);
            $fotografias = $query;
            if(is_array($fotografias)) $out['fotografias_file'] = $fotografias;


            $sql = "SELECT * FROM `invoices` WHERE id_accountability = $id";
            $result = $obj_bdmysql->query($sql, $dbconn);
            if(is_array($result)) $out['invoices'] = $result;  

          
            $out['code'] = 200;
            $out['message'] = "Ok";
        }else{
            $out['code'] = 204;
            $out['message'] = "Error";
        }

        echo json_encode($out);
    break;

    case 'delAccountability':

        $obj_bdmysql->delete("user", "id= $id", $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Usuario Eliminado con exito..!';

        echo json_encode($out);
    break;

    case 'changeStatus':

        $sql = "SELECT status from user where id = $id";
        $row = $obj_bdmysql->query($sql, $dbconn);
        //print_r($row);exit();
        if ($row[0]['status'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $fields = "status= '$status'";
        $where = "id = '$id'";
        $obj_bdmysql->update('user', $fields, $where, $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Updated user status..!';

        echo json_encode($out);
    break;

    case 'checkNumFolio':
        $sql = "SELECT r.rut, r.name, r.phone, r.email, s.name_proyect, s.id AS id_subvention FROM subvention s 
        INNER JOIN organitation r ON s.id_organitation = r.id 
        WHERE s.id = '$num_folio'";
       
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code']    = 200;
            $out['id_subvention'] = $query[0]['id_subvention'];
            $out['name'] = $query[0]['name'];
            $out['phone'] = $query[0]['phone'];
            $out['email'] = $query[0]['email'];
            $out['name_proyect'] = $query[0]['name_proyect'];
            $out['message'] = 'Ok';
        }else{
            $out['code']    = 204;
            $out['message'] = 'No existe una subvención con ese numero de folio!';
        }
        echo json_encode($out);
    break;

    case 'updateFileBeneficiarie':

        if($id_accountability_file == 0){

            $out['code'] = 204;
            $out['message'] = 'Error Insert..!';

            $campo = "id_accountability, name, path, url, type";
            $valor = "$id_accountability, '$name', '$path', '$url', '1'";
            //$sql = "INSERT INTO financing_files ($campo) VALUES ($valor)";
            //print_r($sql);exit;
            $id = $obj_bdmysql->insert("accountability_files", $campo, $valor, $dbconn);

            if ($id > 0) {
                $out['code'] = 200;
                $out['message'] = 'El Archivo fué creado exitosamente..!';
            }  
        }
       
        if($id_accountability_file != 0){

            $out['code'] = 204;
            $out['message'] = 'Error Update..!';

            $campo = "name='$name', path='$path', url='$url'";           
            $where = "id = '$id_accountability_file'";
            $update_accountability_file = $obj_bdmysql->update("accountability_files", $campo, $where, $dbconn);

            if ($update_accountability_file > 0) {
                $out['code'] = 200;
                $out['message'] = 'El Archivo fué creado exitosamente..!';
            }  
        }
        
        echo json_encode($out);
        break;

    case 'saveImagesFiles':
        
        $out['code'] = 204;
        $out['message'] = 'Error..!';

        $campo = "id_accountability, name, path, url, type";
        $valor = "$id_accountability, '$name', '$path', '$url', '$type'";
        //$sql = "INSERT INTO financing_files ($campo) VALUES ($valor)";
        //print_r($sql);exit;
        $id = $obj_bdmysql->insert("accountability_files", $campo, $valor, $dbconn);

        if ($id > 0) {
            $out['code'] = 200;
            $out['message'] = 'El Archivo fué creado exitosamente..!';
        }
        
        echo json_encode($out);
    break;

    case 'saveDocumentFiles':
        
        $out['code'] = 204;
        $out['message'] = 'Error..!';
        
        $campo = "id_accountability, name, path, url, type";
        $valor = "$id_accountability, '$name', '$path', '$url', '$type'";
        // $sql = "INSERT INTO accountability_files ($campo) VALUES ($valor)";
        // print_r($sql);exit;
        $id = $obj_bdmysql->insert("accountability_files", $campo, $valor, $dbconn);

        if ($id > 0) {
            $out['code'] = 200;
            $out['message'] = 'El Documento fué creado exitosamente..!';
            $old_path = '';
        }

        echo json_encode($out);
    break;

    case 'documentsList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'af.id',
            1 => 'af.name',
            2 => 'af.url',
        );

        $sql   = "SELECT af.id, af.name, af.url, af.type FROM accountability_files af WHERE af.id_accountability = $id";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        
        
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (af.id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR af.name LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR af.url LIKE '%" . $requestData['search']['value'] . "%')";
        } 
        
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalFiltered = is_array($query) ? count($query) : 0;
        
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        $query = $obj_bdmysql->query($sql, $dbconn);
        //print_r($sql);exit();
        $data = array();
        if (is_array($query)) {
            foreach ($query as $row) {
                $s = array();
                $botones = '';

                $botones .= '<a class="btn btn-primary btn-sm mr-1" href="'.$row['url'].'" target="_blank"><i class="fas fa-external-link-alt"></i></a>';

                if($obj_function->validarPermiso($_SESSION['permissions'],'accountability_view_delete')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="accountabilityController.deleteDocument(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';
                }
                

                if($row['type'] == 1) $status = '<span class="badge badge-warning">Lista de beneficiarios</span>';
                if($row['type'] == 2) $status = '<span class="badge badge-success">Comprobante de restitución de fondos</span>';
                if($row['type'] == 3) $status = '<span class="badge badge-info">Fotografías de lo adquirido</span>';
                if($row['type'] == 4) $status = '<span class="badge badge-primary">Archivo de reintegro</span>';

                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = '<center>' . html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>'; 
                $nestedData[] = '<center>' . $status . '</center>';
                $nestedData[] = '<center>' . $botones . '</center>'; 

                $data[] = $nestedData;
            }
        }
        
        ## Response
        $json_data = array(           
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );//print_r($json_data);exit();

        echo json_encode($json_data);
        /////////////////////////////////////////////////////////////////////////////////////////
    break;

    case 'deleteDocumentsFile':

        $sql = "SELECT `path` FROM accountability_files WHERE id = $id";
        $financing_file = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($financing_file)) {
            $out['path'] = $financing_file[0]['path'];
        }
        
        $obj_bdmysql->delete("accountability_files", "id= $id", $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Archivo Eliminado con exito..!';
        //print_r($out);exit();
        //print_r($out);exit();
        echo json_encode($out);
    break;

    case 'checkDocumentsFiles':
        if ($type == 3) {
            $out['code'] = 200;
            $out['message'] = 'ok..!';
        } else{
            $sql = "SELECT id FROM accountability_files WHERE id_accountability = $id_accountability AND type = '$type'";
            $query = $obj_bdmysql->query($sql, $dbconn);

            if(is_array($query)){
                $out['code'] = 204;
                $out['message'] = 'Ya haz guardado este tipo de documentos en esta rendición..!';
            } else {
                $out['code'] = 200;
                $out['message'] = 'ok..!';
            }
        }

        echo json_encode($out);
    break;

    case 'updateAccountabilityStatus':
        $out['code'] = 204;
        $out['message'] = 'Error Update..!';

        $campo = "status='$status', message='$reason'";           
        $where = "id = '$accountability_id'";
        $update = $obj_bdmysql->update("accountability", $campo, $where, $dbconn);

        if ($update > 0) {
            $out['code'] = 200;
            $out['message'] = 'El estatus de la rendición fue cambiado exitosamente..!';
        } 
        echo json_encode($out); 
    break;
}