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
$id_user = $_SESSION['id_user'];

switch ($method) {
    case 'budgetInformationList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'bf.budget_certificate_number',
            1 => 'bf.emision_date',
            2 => 'bf.amount_available',
        );        

        $sql = "SELECT bf.*  FROM budge_information bf WHERE bf.id_user = $id_user";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        //print_r($sql);exit();
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE bf.budget_certificate_number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR bf.emision_date LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR bf.amount_available LIKE '%" . $requestData['search']['value'] . "%'";
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
                $document = '';
                $status = '';
                /*if($obj_function->validarPermiso($_SESSION['permissions'],'budget_information_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="budgetInformationController.edit(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>';
                }*/

                if($obj_function->validarPermiso($_SESSION['permissions'],'budget_information_delete') AND $row["status"] == 1){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Anular" onclick="budgetInformationController.cancel(' . $row["id"] . ','. $row["amount_available"] .')"><i class="fas fa-ban"></i></a></button>';
                    $status .= '<i class="fas fa-check" style="color:#62dd6a" title="Activa"></i>';
                }else{
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" title="Ya ha sido anulada" disabled><i class="fas fa-ban"></i></a></button>';
                    $status .= '<i class="fas fa-ban" style="color:red" title="Anulada"></i>';
                }

                if ($row['url_document'] !== '') {
                	$document = '<center> <b>Documento:</b>' . $row['name_document']  . '</center>';
                }

                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = $row['budget_certificate_number'];
                $nestedData[] = '<center>' . html_entity_decode($row['emision_date'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';
                $nestedData[] = '<center>' . html_entity_decode($row['amount_available'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
                $nestedData[] = '<center>' . utf8_encode($document) . '</center>';
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

    case 'saveNewBudgetInformation':
        $fecha = date('Y-m-d');
        //print_r($_SESSION);exit;
        if ($id_budget_information == 0) {
            //print_r("b");exit;
            $out['code'] = 204;
            $out['message'] = 'Error Insert...!';

            $campo = "id_user, budget_certificate_number, emision_date, amount_available";
            $valor = "'$id_user', '$add_budget_certificate_number', '$add_emision_date', '$add_amount_available'";
            $budget_information_id = $obj_bdmysql->insert("budge_information", $campo, $valor, $dbconn);          

            if ($budget_information_id > 0) {
                $sql = "SELECT accumulated_amount from data";
                $result = $obj_bdmysql->query($sql, $dbconn);

                if (is_array($result)) {
                    $suma = $result[0]['accumulated_amount'] + $add_amount_available;

                    $campo = "accumulated_amount='$suma'";
                    $where = "";
                    $update_user = $obj_bdmysql->update("data", $campo, $where, $dbconn);
                }

                $out['id_user'] = $id_user;
                $out['id_budget_information'] = $budget_information_id;
                $out['code'] = 200;
                $out['message'] = 'El certificado fué creado exitosamente..!';
            }           
        }

        // if ($id_budget_information != 0) {
        //     // print_r($_POST);exit;
        //     $out['code'] = 204;
        //     $out['message'] = 'Error Updated...!';
             
        //     $campo = "budget_certificate_number='$add_budget_certificate_number',emision_date='$add_emision_date', amount_available='$add_amount_available'";
        //     $where = "id = '$id_budget_information'";
        //     // $sql = "UPDATE `budge_information` SET $campo WHERE $where";
        //     // print($sql);exit();
        //     $update_budget_information = $obj_bdmysql->update("budge_information", $campo, $where, $dbconn);

        //     if ($update_budget_information > 0) {
        //     	$out['id_user'] = $id_user;
        //         $out['id_budget_information'] = $id_budget_information;
        //         $out['code'] = 200;
        //         $out['message'] = 'El certificado fué actualizado exitosamente..!';
        //     }
            
        // }

        echo json_encode($out);
    break;

    case 'editbudgetInformation':
        $sql = "SELECT bf.*  FROM budge_information bf 
        WHERE bf.id = $id";
        $query = $obj_bdmysql->query($sql, $dbconn);
        // print_r($sql);exit();
        if (is_array($query)) {
            $obj = $query[0];

            $out['id_budget_information']  = $obj['id'];
            $out['budget_certificate_number']  = $obj['budget_certificate_number'];
            $out['emision_date']     = $obj['emision_date'];
            $out['amount_available']  = $obj['amount_available'];
            $out['name_document']  = $obj['name_document'];
            $out['path_document']  = $obj['path_document'];
            $out['url_document']  = $obj['url_document'];

            $out['code'] = 200;
            $out['message'] = "Ok";
        }else{
            $out['code'] = 204;
            $out['message'] = "Error";
        }

        echo json_encode($out);
    break;

    case 'cancelBudgetInformation':

        $campo = "accumulated_amount='$resultado'";
        $where = "id = 1";
        $update_user = $obj_bdmysql->update("data", $campo, $where, $dbconn);

        $campo = "status='0'";
        $where = "id = '$id'";
        $update_user = $obj_bdmysql->update("budge_information", $campo, $where, $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Certificado Presupuestario Cancelado con exito..!';

        echo json_encode($out);
    break;

    case 'changeStatus':

        $sql = "SELECT status from budge_information where id = $id";
        $row = $obj_bdmysql->query($sql, $dbconn);
        //print_r($row);exit();
        if ($row[0]['status'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $fields = "status= '$status'";
        $where = "id = '$id'";
        $obj_bdmysql->update('budge_information', $fields, $where, $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Updated budge_information status..!';

        echo json_encode($out);
    break;

    case 'uploadFileCertificate':
        $out['code'] = 204;
        $out['message'] = 'Error Updated Document...!';

        $campo = "name_document='$name', path_document='$path', url_document='$url'";           
        $where = "id = '$id_budget_information'";
        $update_role_financing = $obj_bdmysql->update("budge_information", $campo, $where, $dbconn);
       
        //print_r($sql);exit;
        if ($update_role_financing > 0) {
            $out['code'] = 200;
            $out['message'] = 'El documento fué subido exitosamente..!';
        }        

        echo json_encode($out);
    break;

    case 'findAccumulatedAmount':
        $sql = "SELECT accumulated_amount FROM data WHERE id = 1";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['accumulated_amount'] = $query[0]['accumulated_amount'];
            $out['code'] = 200;
            $out['message'] = 'ok..!';
        }else{
            $out['code'] = 204;
            $out['message'] = 'error find accumulated amount..!';
        }
        echo json_encode($out);
    break;

    case 'new':
        $suma = 1 + 2;

                    $campo = "accumulated_amount='$suma'";
                    $where = "WHERE id = 1";
                    $update_user = $obj_bdmysql->update("data", $campo, $where, $dbconn);
    break;
}