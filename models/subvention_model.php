<?php
include '../common/login_timeout.php';
require_once '../common/db.php';
require_once '../common/bdMysql.php';
require_once '../common/function.php';

$obj_function = new coFunction();
$obj_bdmysql = new coBdmysql();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../assets/phpmailer/Exception.php';
require '../assets/phpmailer/PHPMailer.php';
require '../assets/phpmailer/SMTP.php';

foreach ($_POST as $i_dato => $dato_) {
    @$$i_dato = addslashes($obj_function->evalua_array($_POST, $i_dato));
}

// $sql   = "SELECT id FROM accountability WHERE id_subvention = $id_subvention";
// $query = $obj_bdmysql->query($sql, $dbconn);
// if(is_array($query)){
// $out['code'] = 204;
// $out['message'] = "Ya existe una rendición de cuenta para la subvención con el numero de folio: $id_subvention";
// echo json_encode($out);die();
// }
$id_user = $_SESSION['id_user'];

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

        $sql = "SELECT su.message, su.id as subvention_id, su.status as subvention_status, su.created_at, o.name, ac.date_admission, ac.status as status_accountability FROM subvention su INNER JOIN organitation o on o.id = su.id_organitation LEFT JOIN accountability ac on ac.id_subvention = su.id";
        if($id_organization > 0){
            $sql .= " WHERE o.id = $id_organization";
        }
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        //print_r($query);exit();
        if (!empty($requestData['search']['value'])) {
            if($id_organization > 0){
                $sql .= " AND (o.name LIKE '%" . $requestData['search']['value'] . "%' ";                        
                $sql .= " OR su.created_at LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR ac.date_admission LIKE '%" . $requestData['search']['value'] . "%')";
            }else{
                $sql .= " WHERE o.name LIKE '%" . $requestData['search']['value'] . "%' ";                        
                $sql .= " OR su.created_at LIKE '%" . $requestData['search']['value'] . "%'";
                $sql .= " OR ac.date_admission LIKE '%" . $requestData['search']['value'] . "%'";
            }            
        } 
        //print_r($sql);exit;
        
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalFiltered = is_array($query) ? count($query) : 0;
        if($requestData['length'] == -1){
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'];
        }else{
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        }
        
        $query = $obj_bdmysql->query($sql, $dbconn);
        //print_r($sql);exit();
        $data = array();
        if (is_array($query)) {
            foreach ($query as $row) {
                $s = array();
                $botones = '';

                /*if($obj_function->validarPermiso($_SESSION['permissions'],'user_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="userController.edit(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>';
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'user_delete')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="userController.deleted(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';
                }*/
                
                $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" onclick="subventionController.view(' . $row["subvention_id"] . ')"><i class="fas fa-eye" aria-hidden="true"></i></a></button>';

                if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Editar Subvención" onclick="subventionController.edit(' . $row["subvention_id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>'; 
                    $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Editar Documentos" onclick="subventionController.editDocuments(' . $row["subvention_id"] . ')"><i class="fas fa-folder"></i></a></button>';
                }                
                
                // if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_approve')){
                //     $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Acciones" onclick="subventionController.actions(' . $row["subvention_id"] . ')"><i class="fas fa-list"></i></a></button>';
                // }

                if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_approve')){
                    if($row['subvention_status'] != 3){
                        $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Cambiar Estatus" onclick="subventionController.actions(' . $row["subvention_id"] . ')"><i class="far fa-check-circle"></i></a></button>';
                    }else{
                        $botones .= '<button class="btn btn-primary btn-sm mr-1 mt-1" title="Subvencion ya aprobada" disabled><i class="far fa-check-circle"></i></a></button>';
                    }
                }

                if($row['subvention_status'] == 3){
                    $botones .= '<button class="btn btn-primary btn-sm mt-1 mr-1" title="Generar Convenio" onclick="subventionController.convenio(' . $row["subvention_id"] . ')"><i class="fas fa-file" title="Generar Convenio" aria-hidden="true"></i></a></button>';
                }

                $botones .= '<button class="btn btn-primary btn-sm mt-1 mr-1" title="Reutilizar subvencion" onclick="subventionController.reutilizar(' . $row["subvention_id"] . ')"><i class="fas fa-sync-alt"></i></a></button>';                

                if($row['status_accountability'] == 0){
                    $status_accountability = "<span class='badge badge-info' title='".$row['message']."'>Pendiente</span>";
                }else if($row['status_accountability'] == 1){
                    $status_accountability = "<span class='badge badge-success' title='".$row['message']."'>Aprobada</span>";
                }else if($row['status_accountability'] == 2){
                    $status_accountability = "<span class='badge badge-primary' title='".$row['message']."'>Observada</span>";
                }else if($row['status_accountability'] == 3){
                    $status_accountability = "<span class='badge badge-warning' title='".$row['message']."'>Devolución</span>";
                }

                if($row['subvention_status'] == 3){
                    $status_subvention = "<span class='badge badge-success' title='".$row['message']."'>Aprobada</span>";
                }else if($row['subvention_status'] == 0){
                    $status_subvention = "<span class='badge badge-danger' title='".$row['message']."'>Error</span>";
                }else if($row['subvention_status'] == 1){
                    $status_subvention = "<span class='badge badge-info' title='".$row['message']."'>En evaluación</span>";
                }else if($row['subvention_status'] == 2){
                    $status_subvention = "<span class='badge badge-primary' title='".$row['message']."'>Pre-Aprobada</span>";
                }else if($row['subvention_status'] == 4){
                    $status_subvention = "<span class='badge badge-warning' title='".$row['message']."'>Rechazada</span>";
                }else{
                    $status_subvention = "<span class='badge badge-dark' title='".$row['message']."'>?</span>";
                }

                $nestedData = array();
                $nestedData[] = $row['subvention_id'];
                $nestedData[] = '<center>' . html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
                $nestedData[] = '<center>' . $row['created_at'] . '</center>';
                $nestedData[] = '<center>' . $row['date_admission'] . '</center>';
                $nestedData[] = '<center>' . $status_accountability . '</center>';
                $nestedData[] = '<center>' . $status_subvention . '</center>'; 
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

                $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Eliminar Documento" onclick="subventionController.deleteDocument(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';               

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

            if($id_organitation > 0){
                $organitation_id = $id_organitation;
            }else{
                $campo = "name, rut, address, email, phone, status, created_at";
                $valor = "'$organitation_name', '$organitation_rut', '$organitation_address', '$organitation_email', '$organitation_phone', '1', '$date'";
                $organitation_id = $obj_bdmysql->insert("organitation", $campo, $valor, $dbconn);
            }

            //$sql = "INSERT INTO user ($campo) VALUES ($valor)";
           // print_r($sql); exit();
             
            $campo = "id_user, id_organitation, year, name_proyect, objetive_proyect, quantity_purchases, amount_purchases, organization_contribution, amount_direct, amount_indirect, total_beneficiaries, quantity_activities, status, created_at";     
            $valor = "$id_user, '$organitation_id', '$year', '$name_proyect', '$objetive_proyect', '$quantity_purchases', '$total_sum_price', '$amount_organitation', '$amount_direct', '$amount_indirect', '$total_sum_bene', '$quantity_activities', 1, '$date'";
            $subvention_id = $obj_bdmysql->insert("subvention", $campo, $valor, $dbconn);

            $cant = count((array)$_POST['beneficiarios']);
            $type = ''; $name = ''; $address = ''; $phone = '';

            if ($cant > 0) {

                for ($i=0; $i < $cant; $i++) { 
                    $type = $_POST['beneficiarios'][$i]['type']; 
                    $name = $_POST['beneficiarios'][$i]['name']; 
                    $rut = $_POST['beneficiarios'][$i]['rut']; 
                    $address = $_POST['beneficiarios'][$i]['address']; 
                    $phone = $_POST['beneficiarios'][$i]['phone'];

                    $campo = "type, id_subvention, name, rut, address, phone, created_at";     
                    $valor = "'$type', '$subvention_id', '$name', '$rut', '$address' , '$phone', '$date'";
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

            if($id_organitation > 0){
                $organitation_id = $id_organitation;
            }else{
                $campo = "name, rut, address, email, phone, status, created_at";
                $valor = "'$organitation_name', '$organitation_rut', '$organitation_address', '$organitation_email', '$organitation_phone', '1', '$date'";
                $organitation_id = $obj_bdmysql->insert("organitation", $campo, $valor, $dbconn);
            }

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

    case 'cloneSubvention':

        $out['code'] = 204;
        $out['message'] = 'Error..!';

        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO subvention (`id_user`, `id_organitation`, `year`, `name_proyect`, `objetive_proyect`, `quantity_purchases`, `amount_purchases`, `organization_contribution`, `amount_direct`, `amount_indirect`, `total_beneficiaries`, `quantity_activities`, `message`) SELECT `id_user`, `id_organitation`, `year`, `name_proyect`, `objetive_proyect`, `quantity_purchases`, `amount_purchases`, `organization_contribution`, `amount_direct`, `amount_indirect`, `total_beneficiaries`, `quantity_activities`, `message` FROM subvention s WHERE s.id = $id_subvention_old";
        $dbconn->query($sql);
        $id_subvention_new = $dbconn->insert_id;

        if (is_integer($id_subvention_new)) {
            $sql = "SELECT * FROM members WHERE id_subvention = $id_subvention_old";
            $query = $obj_bdmysql->query($sql, $dbconn);
            
            if (is_array($query)) {
                foreach($query as $value){
                    //print_r($value['type']);exit;
                    $campo = "type, id_subvention, name, rut, address, phone";
                    $valor = "'".$value['type']."', '$id_subvention_new', '".$value['name']."', '".$value['rut']."', '".$value['address']."', '".$value['phone']."'";
                    $query = $obj_bdmysql->insert("members", $campo, $valor, $dbconn);
                }
            }

            ///////////////////////////////////////////

            $sql = "SELECT * FROM financing_details WHERE id_subvention = $id_subvention_old";
            $query = $obj_bdmysql->query($sql, $dbconn);

            if (is_array($query)) {
                foreach($query as $value){
                    $campo = "`id_subvention`, `details`, `unit_price`, `quantity`, `total_price`";
                    $valor = "$id_subvention_new, '".$value['details']."', '".$value['unit_price']."', '".$value['quantity']."', '".$value['total_price']."'";
                    $query = $obj_bdmysql->insert("financing_details", $campo, $valor, $dbconn);
                }
            }

            //////////////////////////////////////

            $sql = "SELECT * FROM schedule WHERE id_subvention = $id_subvention_old";
            $query = $obj_bdmysql->query($sql, $dbconn);

            if (is_array($query)) {
                foreach($query as $value){
                    $campo = "`id_subvention`, `activities`, `month`";
                    $valor = "$id_subvention_new, '".$value['activities']."', '".$value['month']."'";
                    $query = $obj_bdmysql->insert("schedule", $campo, $valor, $dbconn);
                }
            }            

            //////////////////////////////////////

            $sql = "SELECT * FROM financing_files WHERE id_subvention = $id_subvention_old";
            $query = $obj_bdmysql->query($sql, $dbconn);

            if (is_array($query)) {
                foreach($query as $value){
                    $campo = "`id_subvention`, `name`, `path`, `url`";
                    $valor = "$id_subvention_new, '".$value['name']."', '".$value['path']."', '".$value['url']."'";
                    $query = $obj_bdmysql->insert("financing_files", $campo, $valor, $dbconn);
                }
            }

            //////////////////////////////////////

            $sql = "SELECT * FROM subvention_files WHERE id_subvention = $id_subvention_old";
            $query = $obj_bdmysql->query($sql, $dbconn);

            if (is_array($query)) {
                foreach($query as $value){
                    $campo = "`id_subvention`, `type`, `name`, `path`, `url`";
                    $valor = "$id_subvention_new, '".$value['type']."', '".$value['name']."', '".$value['path']."', '".$value['url']."'";
                    $query = $obj_bdmysql->insert("subvention_files", $campo, $valor, $dbconn);
                }
            }

            $out['code'] = 200;
            $out['id_subvention_new'] = $id_subvention_new;
            $out['message'] = 'Ok..!';
        }

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
        //$sql = "INSERT INTO financing_files ($campo) VALUES ($valor)";
        //print_r($sql);exit;
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
        echo json_encode(array('subvention_data' => $data_subvention, 'financing_details_data' => $financing_details_data, 'financing_files_data' => $financing_files_data, 'members_data' => $members_data, 'schedule_data' => $schedule_data));
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

    case 'updateSubventionStatus':
        //0 Error  //1 En evaluación   //2 Pre-Aprobada    //3 Aprobada    //4 Rechazada

        if($status == 3){
            $sql = "SELECT amount_purchases FROM subvention WHERE id = $subvention_id";
            $query = $obj_bdmysql->query($sql, $dbconn);
            $monto = $query[0]['amount_purchases'];

            $sql = "SELECT accumulated_amount FROM data WHERE id = 1";
            $query = $obj_bdmysql->query($sql, $dbconn);

            if($monto <= $query[0]['accumulated_amount']){

                $campo = "status='$status', message='$reason'";
                $where = "id = '$subvention_id'";
                $update_subvention = $obj_bdmysql->update("subvention", $campo, $where, $dbconn);

                $resultado = $query[0]['accumulated_amount'] - $monto;
                $campo = "accumulated_amount='$resultado'";
                $where = "id = 1";
                $update_data = $obj_bdmysql->update("data", $campo, $where, $dbconn);

                $campo = "id_subvention, no_mayor_decree, agreement_date, no_payment_decree, payment_date, no_payment_installments, session_date, no_session";
                $valor = "$subvention_id, '$add_no_mayor_decree', '$add_agreement_date', '$add_no_payment_decree', '$add_payment_date', '$add_no_payment_installments', '$add_session_date', '$add_no_session'";
                $approval_subsidy_id = $obj_bdmysql->insert("approval_subsidy", $campo, $valor, $dbconn);     
                
                $out['code']    = 200;
                $out['message'] = 'El estado de la subvención se ha cambiado..!';
                
            }else{
                $out['code']    = 204;
                $out['message'] = 'No hay suficiente disponibilidad presupuestaria..!';
            }
        }else{
            $campo = "status='$status', message='$reason'";
            $where = "id = '$subvention_id'";
            $update_subvention = $obj_bdmysql->update("subvention", $campo, $where, $dbconn);

            if($update_subvention > 0){
                $out['code']    = 200;
                $out['message'] = 'El estado de la subvención se ha cambiado..!';

                $sql = "SELECT u.email, s.status, s.message FROM subvention s 
                inner join user u on u.id = s.id_user
                WHERE s.id = $subvention_id";
                $query = $obj_bdmysql->query($sql, $dbconn);
                $email = $query[0]['email'];

                if($query[0]['status'] == 3){
                    $status_subvention = "Aprobada";
                }else if($query[0]['status'] == 0){
                    $status_subvention = "Error";
                }else if($query[0]['status'] == 1){
                    $status_subvention = "En evaluación";
                }else if($query[0]['status'] == 2){
                    $status_subvention = "Pre-Aprobada";
                }else if($query[0]['status'] == 4){
                    $status_subvention = "Rechazada";
                }

                sendEmail($email, $status_subvention, $query[0]['message']);
            }else{
                $out['code']    = 204;
                $out['message'] = 'Error update..!';
            }
        }

        echo json_encode($out);
    break;

    case 'saveNewApprovalSubsidy':
        $fecha = date('Y-m-d');

        $sql = "SELECT amount_purchases FROM subvention WHERE id = $id_subvention";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $monto = $query[0]['amount_purchases'];

        $sql = "SELECT accumulated_amount FROM data WHERE id = 1";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if($monto <= $query[0]['accumulated_amount']){

            $campo = "status=3";
            $where = "id = '$id_subvention'";
            $update_subvention = $obj_bdmysql->update("subvention", $campo, $where, $dbconn);

            $resultado = $query[0]['accumulated_amount'] - $monto;
            $campo = "accumulated_amount='$resultado'";
            $where = "id = 1";
            $update_data = $obj_bdmysql->update("data", $campo, $where, $dbconn);

            $campo = "id_subvention, no_mayor_decree, agreement_date, no_payment_decree, payment_date, no_payment_installments";
            $valor = "$id_subvention, '$add_no_mayor_decree', '$add_agreement_date', '$add_no_payment_decree', '$add_payment_date', '$add_no_payment_installments'";
            $approval_subsidy_id = $obj_bdmysql->insert("approval_subsidy", $campo, $valor, $dbconn);     
            
            $out['code'] = 200;
            $out['message'] = 'El estado de la subvención se ha cambiado..!';
            
        }else{
            $out['code']    = 204;
            $out['message'] = 'No hay suficiente disponibilidad presupuestaria..!';
        }

        echo json_encode($out);

        // if ($id_approval_subsidy == 0) {
        //     //print_r("b");exit;
        //     $out['code'] = 204;
        //     $out['message'] = 'Error Insert...!';

        //     $campo = "id_subvention, no_mayor_decree, agreement_date, no_payment_decree, payment_date, no_payment_installments";
        //     $valor = "$id_subvention, '$add_no_mayor_decree', '$add_agreement_date', '$add_no_payment_decree', '$add_payment_date', '$add_no_payment_installments'";
        //     $approval_subsidy_id = $obj_bdmysql->insert("approval_subsidy", $campo, $valor, $dbconn);          

        //     if ($approval_subsidy_id > 0) {
        //         $out['approval_subsidy_id'] = $approval_subsidy_id;
        //         $out['code'] = 200;
        //         $out['message'] = 'El antecedente fué creado exitosamente..!';
        //     }           
        // }

        // if ($id_approval_subsidy != 0) {
        //     // print_r($_POST);exit;
        //     $out['code'] = 204;
        //     $out['message'] = 'Error Updated...!';
             
        //     $campo = "no_mayor_decree='$add_no_mayor_decree',agreement_date='$add_agreement_date', no_payment_decree='$add_no_payment_decree', no_payment_installments='$add_no_payment_installments'";
        //     $where = "id = '$id_approval_subsidy'";
        //     // $sql = "UPDATE `budge_information` SET $campo WHERE $where";print($sql);exit();
        //     $update_approval_subsidy = $obj_bdmysql->update("approval_subsidy", $campo, $where, $dbconn);

        //     if ($update_approval_subsidy > 0) {
        //         $out['approval_subsidy_id'] = $id_approval_subsidy;
        //         $out['code'] = 200;
        //         $out['message'] = 'El antecedente fué actualizado exitosamente..!';
        //     }
        // }
    break;

    case 'checkRut':
        $sql = "SELECT id, name, rut, address, email, phone FROM organitation WHERE rut = '$rut'";
        $organitation = $obj_bdmysql->query($sql, $dbconn);
        //print_r($subvention_data);exit();
        if(is_array($organitation)){
            $out['code'] = 200;
            $out['id_organitation'] = $organitation[0]['id'];
            $out['name'] = $organitation[0]['name'];
            $out['rut'] = $organitation[0]['rut'];
            $out['address'] = $organitation[0]['address'];
            $out['email'] = $organitation[0]['email'];
            $out['phone'] = $organitation[0]['phone'];
        }else{
            $out['code'] = 204;
        }
        echo json_encode($out);
    break;
}

function sendEmail($to, $status, $motivo){
    global $mail;

    $subject = "El estatus de la subvencion que creaste ha sido cambiado";
    $additional_text = "";
    $message = '
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Forgot Password</title>
                <style type="text/css">
                    .content{
                        width:100%; background:#eee; position:relative; font-family:sans-serif; padding-bottom:40px;
                    }
                    .body-email{
                        position:relative; margin:auto; width:700px; background:white; padding:20px
                    }
                    .img-email{
                        width:15%
                    }
                    @media (max-width: 768px) {
                       .body-email {
                         width: 80%;
                       }
                       .img-email{
                            width: 30%;
                       }
                    }
                </style>
            </head>
            <body>
                <div class="content">
                    <div class="body-email">
                        <center>
                        <img class="img-email" src="../assets/img/notify.jpg" style="width: 150px; height: 150px">

                        <h4 style="font-weight:100; color:#666; padding:0 20px">Hola, se ha cambiado el estado de la subvención que creaste.</h4>

                        <h3 style="font-weight:100; color:#666">El estado ahora es: "<strong>'.$status.'</strong>", y el motivo es el siguiente: <strong>'.$motivo.'</strong></h3>

                        <h3 style="font-weight:100; color:#666"><strong>No responda este mensaje, gracias.</strong></h3>
                      
                        </center>
                    </div>
                </div>
            </body>
    </html>';
    $message = utf8_decode($message);
    $mail = new PHPMailer(TRUE);

    $mail->isSMTP(); // Indicando el uso de SMTP
    $mail->SMTPOptions = array(
        'tls' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );   
    //$mail->SMTPDebug = 4;
    $mail->Debugoutput = 'html'; // Agregando compatibilidad con HTML       
    $mail->Host = 'smtp.gmail.com'; // Estableciendo el nombre del servidor de email         
    $mail->Port = 587; // Estableciendo el puerto
    $mail->SMTPSecure = false; // Estableciendo el sistema de encriptación      
    $mail->SMTPAuth = true; // Para utilizar la autenticación SMTP
    $mail->Username = "subvenciones10@gmail.com"; // Nombre de usuario para la autenticación SMTP - usar email completo para gmail
    //$mail->Password = "2022*subvenciones";// Password para la autenticación SMTP
    $mail->Password = "vqzkbzrddhxreavs";// Password para la autenticación SMTP
    $mail->setFrom('subvenciones10@gmail.com', 'Subvenciones');// Estableciendo como quién se va a enviar el mail
    $mail->addAddress($to, $to);// Estableciendo a quién se va a enviar el mail
    //$mail->addAddress($email_agent, $email_agent);// Estableciendo a quién se va a enviar el mail
    $mail->Subject = $subject;// El asunto del mail
    $mail->MsgHTML($message);// Estableciendo el mensaje a enviar

    // Adjuntando una imagen
    //$mail->addAttachment('images/phpmailer_mini.png');

    // Enviando el mensaje y controlando los errores
    if ($mail->send()) {
        $mss = '1';
        $salida = 'EMAIL SENT.';

        //contacts_service_wizard_log($id_contacts_services, "EMAIL", "OK: SEND EMAIL WIZARD", 1);
    } else {
        $mss = '0';
        $salida = 'EMAIL NOT SENT.';

        //contacts_service_wizard_log($id_contacts_services, "EMAIL", "ERROR: SEND EMAIL WIZARD", 2);
    }
    //echo $mss;
}