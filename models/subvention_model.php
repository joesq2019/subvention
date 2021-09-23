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
    case 'subventionsList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'su.id',
            1 => 'o.name',
            2 => 'su.created_at',
            3 => 'ac.date_admission'
        );

        $id_user = $_SESSION['id_user'];

        $sql = "SELECT su.id as subvention_id, su.created_at, o.name, ac.date_admission, su.status FROM subvention su INNER JOIN organitation o on o.id = su.id_organitation LEFT JOIN accountability ac on ac.id_subvention = su.id";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        //print_r($sql);exit();
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE o.name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR su.created_at LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR ac.date_admission LIKE '%" . $requestData['search']['value'] . "%'";
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
                $status = '';
                /*if($obj_function->validarPermiso($_SESSION['permissions'],'user_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="userController.edit(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>';
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'user_delete')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="userController.deleted(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';
                }*/


                $botones .= '<button class="btn btn-primary btn-sm mr-1 button_view"><i class="fas fa-eye" aria-hidden="true"></i></a></button>';

                $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Editar Subvención" onclick="subventionController.edit(' . $row["subvention_id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>'; 

                $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Editar Documentos" onclick="subventionController.editDocuments(' . $row["subvention_id"] . ')"><i class="fas fa-folder"></i></a></button>'; 

                //change status
                $botones .= '<button class="btn btn-primary btn-sm mr-1 button_status" title="Cambiar Estatus"><i class="fas fa-sync"></i></a></button>';  

                if ($row['status'] == 0) { $status = '<button class="btn btn-sm text-white bg-gray-600">Error</button>';}
                elseif ($row['status'] == 1) { $status = '<button class="btn btn-sm text-white bg-gradient-primary">En Evaluación</button>';} 
                elseif ($row['status'] == 2) { $status = '<button class="btn btn-sm text-white bg-gradient-info">Pre-Aprobada</button>';}             
                elseif ($row['status'] == 3) { $status = '<button class="btn btn-sm text-white bg-gradient-success">Aprobada</button>';}
                elseif ($row['status'] == 4) { $status = '<button class="btn btn-sm text-white bg-gradient-danger">Rechazada</button>';}

                $nestedData = array();
                $nestedData[] = $row['subvention_id'];
                $nestedData[] = '<center>' . html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
                $nestedData[] = '<center>' . $row['created_at'] . '</center>';
                $nestedData[] = '<center>' . $row['date_admission'] . '</center>';
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

    case 'documentsList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'type',
            2 => 'name'
        );

        $id_user = $_SESSION['id_user'];

        $sql = "SELECT id, type, name, url FROM subvention_files where id_subvention = $id_subvention";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        //print_r($sql);exit();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR type LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR id LIKE '%" . $requestData['search']['value'] . "%'";
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

                $botones .= '<a href="' . $row["url"] . '" target="_blank" class="text-white" title="Visualizar Documento"><button class="btn btn-primary btn-sm mr-1" title="Editar Subvención"><i class="fas fa-external-link-alt"></i></a></button></a>';

                //$botones .= '<button class="btn btn-primary btn-sm mr-1" title="Editar Subvención" onclick="subventionController.editDocuments(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>'; 

                // $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Eliminar Documento" onclick="subventionController.deleteDocument(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';               

                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = '<center>' . html_entity_decode($row['type'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
                $nestedData[] = '<center>' . html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';
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

    case 'saveNewSubvention':
        //print_r($_POST['activities']);exit();
        $date = date('Y-m-d H:i:s');

        $organitation_name = $_POST['organitation']['name']; 
        $organitation_rut = $_POST['organitation']['rut']; 
        $organitation_address = $_POST['organitation']['address']; 
        $organitation_email = $_POST['organitation']['email']; 
        $organitation_phone = $_POST['organitation']['phone']; 
        if ($id_subvention == 0) {
            $out['code'] = 204;
            $out['message'] = 'Error Insert...!';

            $campo = "name, rut, address, email, phone, status, created_at";
            $valor = "'$organitation_name', '$organitation_rut', '$organitation_address', '$organitation_email', '$organitation_phone', '1', '$date'";
            $organitation_id = $obj_bdmysql->insert("organitation", $campo, $valor, $dbconn);
            //$sql = "INSERT INTO user ($campo) VALUES ($valor)";
           // print_r($sql); exit();
             
            $campo = "id_organitation, year, name_proyect, objetive_proyect, quantity_purchases, amount_purchases, organization_contribution, amount_direct, amount_indirect, total_beneficiaries, quantity_activities, status, created_at";     
            $valor = "'$organitation_id', '$year', '$name_proyect', '$objetive_proyect', '$quantity_purchases', '$total_sum_price', '$amount_organitation', '$amount_direct', '$amount_indirect', '$total_sum_bene', '$quantity_activities', 1, '$date'";
            $subvention_id = $obj_bdmysql->insert("subvention", $campo, $valor, $dbconn);

            $cant = count((array)$_POST['beneficiarios']);
            $type = ''; $name = ''; $address = ''; $phone = '';

            if ($cant > 0) {

                for ($i=0; $i < $cant; $i++) { 
                    $type = $_POST['beneficiarios'][$i]['type']; 
                    $name = $_POST['beneficiarios'][$i]['name']; 
                    $address = $_POST['beneficiarios'][$i]['address']; 
                    $phone = $_POST['beneficiarios'][$i]['phone'];

                    $campo = "type, id_subvention, name, address, phone, created_at";     
                    $valor = "'$type', '$subvention_id', '$name' ,'$address' , '$phone', '$date'";
                    $obj_bdmysql->insert("members", $campo, $valor, $dbconn);
                    //$sql = "INSERT INTO members ($campo) VALUES ($valor)";print_r($sql);exit();
                }
            }

            $cant_f =   count((array)$_POST['financing']);
          
            if ($cant_f > 0) {
                foreach ($_POST['financing'] as $value ) {
                    $detalle = $value[0]['value'];
                    $price = $value[1]['value'];
                    $cant = $value[2]['value'];
                    $total = $value[3]['value'];

                    $campo = "id_subvention, details, unit_price, quantity, total_price";     
                    $valor = "'$subvention_id', '$detalle', '$price' ,'$cant' , '$total'";
                    $obj_bdmysql->insert("financing_details", $campo, $valor, $dbconn);
                    
                }
            }

            $cant_a =   count((array)$_POST['activities']);
          
            if ($cant_a > 0) {
                foreach ($_POST['activities'] as $value ) {
                    $activities = $value[0]['value'];
                    $month = $value[1]['value'];

                    $campo = "id_subvention, activities, month";     
                    $valor = "'$subvention_id', '$activities', '$month'";
                    $obj_bdmysql->insert("schedule", $campo, $valor, $dbconn);
                    
                }
            }
        
            if ($subvention_id > 0) {
                $out['code'] = 200;
                $out['message'] = 'La subvención fué creada exitosamente..!';
                $out['subvention_id'] = $subvention_id;
            }           
        } else if ($id_subvention != 0) { 

            $edit = 0;

            $organitation_id = $id_organitation;

            $campo = "year='$year', name_proyect='$name_proyect', objetive_proyect='$objetive_proyect', quantity_purchases='$quantity_purchases', amount_purchases='$total_sum_price', organization_contribution='$amount_organitation', amount_direct='$amount_direct', amount_indirect='$amount_indirect', total_beneficiaries='$total_sum_bene', quantity_activities='$quantity_activities', status='1', updated_at='$date'";
            $where = "id = '$id_subvention'";
            $update_subvention = $obj_bdmysql->update("subvention", $campo, $where, $dbconn);

            if ($update_subvention > 0) {
                $out['code'] = 200;
                $out['message'] = 'La subvención fué actualizada exitosamente..!';
            }

            $obj_bdmysql->delete("members", "id_subvention= $id_subvention", $dbconn);

            $cant = count((array)$_POST['beneficiarios']);
            $type = ''; $name = ''; $address = ''; $phone = '';

            if ($cant > 0) {

                for ($i=0; $i < $cant; $i++) { 
                    $type = $_POST['beneficiarios'][$i]['type']; 
                    $name = $_POST['beneficiarios'][$i]['name']; 
                    $address = $_POST['beneficiarios'][$i]['address']; 
                    $phone = $_POST['beneficiarios'][$i]['phone'];

                    $campo = "type, id_subvention, name, address, phone, created_at";     
                    $valor = "'$type', '$id_subvention', '$name' ,'$address' , '$phone', '$date'";
                    $obj_bdmysql->insert("members", $campo, $valor, $dbconn);
                    //$sql = "INSERT INTO members ($campo) VALUES ($valor)";print_r($sql);exit();
                }
            }            
            //print_r($cant);exit();
            $obj_bdmysql->delete("financing_details", "id_subvention= $id_subvention", $dbconn);

            $cant_f =   count((array)$_POST['financing']);
            if ($cant_f > 0) {
                foreach ($_POST['financing'] as $value ) {
                    $detalle = $value[0]['value'];
                    $price = $value[1]['value'];
                    $cant = $value[2]['value'];
                    $total = $value[3]['value'];

                    $campo = "id_subvention, details, unit_price, quantity, total_price";     
                    $valor = "'$id_subvention', '$detalle', '$price' ,'$cant' , '$total'";
                    $obj_bdmysql->insert("financing_details", $campo, $valor, $dbconn);
                    
                }
            }
            
            $obj_bdmysql->delete("schedule", "id_subvention= $id_subvention", $dbconn);

            $cant_a =   count((array)$_POST['activities']);
          
            if ($cant_a > 0) {
                foreach ($_POST['activities'] as $value ) {
                    $activities = $value[0]['value'];
                    $month = $value[1]['value'];

                    $campo = "id_subvention, activities, month";     
                    $valor = "'$id_subvention', '$activities', '$month'";
                    $obj_bdmysql->insert("schedule", $campo, $valor, $dbconn);
                    
                }
            }

            
        
            if ($update_subvention > 0) {
                $out['code'] = 200;
                $out['message'] = 'La subvención fué actualizada exitosamente..!';
                $out['subvention_id'] = $id_subvention;
            } else{
                $out['code'] = 204;
                $out['message'] = 'Error..!';
            }           
        }
        //print_r($out);exit();
        echo json_encode($out);
    break;

    case 'saveFinancingFiles':
        
        $out['code'] = 204;
        $out['message'] = 'Error..!';

        $campo = "id_subvention, name, path, url";
        $valor = "$subvention_id, '$name', '$path', '$url'";
        //$sql = "INSERT INTO financing_files ($campo) VALUES ($valor)";
        //print_r($sql);exit;
        $id = $obj_bdmysql->insert("financing_files", $campo, $valor, $dbconn);

        if ($id > 0) {
            $out['code'] = 200;
            $out['message'] = 'El Archivo fué creado exitosamente..!';
        }
        
        echo json_encode($out);
    break;

    case 'saveDocumentsFiles':

        $out['code'] = 204;
        $out['message'] = 'Error..!';

        $campo = "id_subvention, type, name, path, url";
        $valor = "$subvention_id, '$type', '$name', '$path', '$url'";
        // $sql = "INSERT INTO subvention_files ($campo) VALUES ($valor)";
        // print_r($sql);exit;
        $id = $obj_bdmysql->insert("subvention_files", $campo, $valor, $dbconn);

        if ($id > 0) {
            $out['code'] = 200;
            $out['message'] = 'El Documento fué creado exitosamente..!';
        }

        echo json_encode($out);
    break;

    case 'findSubvention':
        $sql = "SELECT o.name, o.rut, o.address, o.email, o.phone, s.* FROM subvention s INNER JOIN organitation o ON o.id = s.id_organitation WHERE s.id = $id";
        $subvention_data = $obj_bdmysql->query($sql, $dbconn);
        //print_r($subvention_data);exit();
        if(is_array($subvention_data)){
            $data_subvention['id_organitation'] = $subvention_data[0]['id_organitation'];
            $data_subvention['name'] = $subvention_data[0]['name'];
            $data_subvention['rut'] = $subvention_data[0]['rut'];
            $data_subvention['address'] = $subvention_data[0]['address'];
            $data_subvention['email'] = $subvention_data[0]['email'];
            $data_subvention['phone'] = $subvention_data[0]['phone'];
            $data_subvention['year'] = $subvention_data[0]['year'];
            $data_subvention['name_proyect'] = $subvention_data[0]['name_proyect'];
            $data_subvention['objetive_proyect'] = $subvention_data[0]['objetive_proyect'];
            $data_subvention['quantity_purchases'] = $subvention_data[0]['quantity_purchases'];
            $data_subvention['amount_purchases'] = $subvention_data[0]['amount_purchases'];
            $data_subvention['organization_contribution'] = $subvention_data[0]['organization_contribution'];
            $data_subvention['amount_direct'] = $subvention_data[0]['amount_direct'];
            $data_subvention['amount_indirect'] = $subvention_data[0]['amount_indirect'];
            $data_subvention['total_beneficiaries'] = $subvention_data[0]['total_beneficiaries'];
            $data_subvention['quantity_activities'] = $subvention_data[0]['quantity_activities'];
            //$data_subvention['status'] = $subvention_data['status'];
        }

        /*$sql = "SELECT sf.* FROM subvention_files sf INNER JOIN subvention s ON s.id = sf.id_subvention WHERE s.id = $id";
        $financing_details_data = $obj_bdmysql->query($sql, $dbconn);

        if(!is_array($financing_details_data)){
            $financing_details_data = '';
        }  */      

        $sql = "SELECT f.* FROM financing_details f INNER JOIN subvention s ON s.id = f.id_subvention WHERE s.id = $id";
        $financing_details_data = $obj_bdmysql->query($sql, $dbconn);

        if(!is_array($financing_details_data)){
            $financing_details_data = '';
        }

        $sql = "SELECT f.* FROM financing_files f INNER JOIN subvention s ON s.id = f.id_subvention WHERE s.id = $id";
        $financing_files_data = $obj_bdmysql->query($sql, $dbconn);

        if(!is_array($financing_files_data)){
            $financing_files_data = '';
        }

        $sql = "SELECT m.* FROM members m INNER JOIN subvention s ON s.id = m.id_subvention WHERE s.id = $id";
        $members_data = $obj_bdmysql->query($sql, $dbconn);

        if(!is_array($members_data)){
            $members_data = '';
        }

        $sql = "SELECT sch.* FROM schedule sch INNER JOIN subvention s ON s.id = sch.id_subvention WHERE s.id = $id";
        $schedule_data = $obj_bdmysql->query($sql, $dbconn);

        if(!is_array($schedule_data)){
            $schedule_data = '';
        }
        //echo "e";exit();
        echo json_encode(array('code' => 200, 'subvention_data' => $data_subvention, 'financing_details_data' => $financing_details_data, 'financing_files_data' => $financing_files_data, 'members_data' => $members_data, 'schedule_data' => $schedule_data));
    break;

    case 'deleteFinancingFile':

        $sql = "SELECT `path` FROM financing_files WHERE id = $id_file";
        $financing_file = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($financing_file)) {
            $out['path'] = $financing_file[0]['path'];
        }
        
        $obj_bdmysql->delete("financing_files", "id= $id_file", $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Archivo Eliminado con exito..!';
        //print_r($out);exit();
        //print_r($out);exit();
        echo json_encode($out);
    break;

    case 'deleteSubventionFile':

        $sql = "SELECT `path` FROM subvention_files WHERE id = $id";
        $subvention_file = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($subvention_file)) {
            $out['path'] = $subvention_file[0]['path'];
        }
        
        $obj_bdmysql->delete("subvention_files", "id= $id", $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Documento Eliminado con exito..!';
        //print_r($out);exit();
        //print_r($out);exit();
        echo json_encode($out);
    break;

    case 'checkDocumentsFiles':

        $sql = "SELECT id FROM subvention_files WHERE id_subvention = $subvention_id AND type = '$type'";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if(is_array($query)){
            $out['code'] = 204;
            $out['message'] = 'Ya haz guardado este tipo de documentos en esta subvención..!';
        } else {
            $out['code'] = 200;
            $out['message'] = 'ok..!';
        }

        echo json_encode($out);
    break;

    case 'changeStatus':

        $out['code'] = 204;
        $out['message'] = 'Error..!';

        $fields = "status= '$status'";
        $where = "id = '$id'"; 
        $update_status = $obj_bdmysql->update('subvention', $fields, $where, $dbconn);

        if ($update_status > 0) {
            $out['code']    = 200;
            $out['message'] = 'Estatus actualizado..!';
        }

        echo json_encode($out);
    break;
}