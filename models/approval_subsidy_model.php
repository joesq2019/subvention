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
    case 'approvalSubsidyList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'no_mayor_decree',
            2 => 'agreement_date',
            3 => 'no_payment_decree',
            4 => 'payment_date',
            5 => 'no_payment_installments'
        );        

        $sql = "SELECT aps.*, s.id AS numero_de_folio FROM approval_subsidy aps INNER JOIN subvention s ON s.id = aps.id_subvention";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE aps.no_mayor_decree LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR aps.agreement_date LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR s.id LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR aps.no_payment_decree LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR aps.payment_date LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR aps.no_payment_installments LIKE '%" . $requestData['search']['value'] . "%'";
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
                if($obj_function->validarPermiso($_SESSION['permissions'],'approval_subsidy_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1 button_edit"><i class="fas fa-edit" aria-hidden="true"></i></a></button>';
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'approval_subsidy_delete')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1 button_delete"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';
                }

                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = '<center>'. $row['numero_de_folio'] .'</center>';
                $nestedData[] = '<center>' . html_entity_decode($row['no_mayor_decree'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';
                $nestedData[] = '<center>' . html_entity_decode($row['agreement_date'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';
                $nestedData[] = '<center>' . html_entity_decode($row['no_payment_decree'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
                $nestedData[] = '<center>' . html_entity_decode($row['payment_date'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
                //$nestedData[] = '<center>' . html_entity_decode($row['no_payment_installments'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
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

    case 'saveNewApprovalSubsidy':
        $fecha = date('Y-m-d');

        if ($id_approval_subsidy == 0) {
            //print_r("b");exit;
            $out['code'] = 204;
            $out['message'] = 'Error Insert...!';

            $campo = "no_mayor_decree, agreement_date, no_payment_decree, payment_date, no_payment_installments";
            $valor = "'$add_no_mayor_decree', '$add_agreement_date', '$add_no_payment_decree', '$add_payment_date', '$add_no_payment_installments'";
            $approval_subsidy_id = $obj_bdmysql->insert("approval_subsidy", $campo, $valor, $dbconn);          

            if ($approval_subsidy_id > 0) {
                $out['approval_subsidy_id'] = $approval_subsidy_id;
                $out['code'] = 200;
                $out['message'] = 'El antecedente fué creado exitosamente..!';
            }           
        }

        if ($id_approval_subsidy != 0) {
            // print_r($_POST);exit;
            $out['code'] = 204;
            $out['message'] = 'Error Updated...!';
             
            $campo = "no_mayor_decree='$add_no_mayor_decree',agreement_date='$add_agreement_date', no_payment_decree='$add_no_payment_decree', no_payment_installments='$add_no_payment_installments'";
            $where = "id = '$id_approval_subsidy'";
            // $sql = "UPDATE `budge_information` SET $campo WHERE $where";print($sql);exit();
            $update_approval_subsidy = $obj_bdmysql->update("approval_subsidy", $campo, $where, $dbconn);

            if ($update_approval_subsidy > 0) {
                $out['approval_subsidy_id'] = $id_approval_subsidy;
                $out['code'] = 200;
                $out['message'] = 'El antecedente fué actualizado exitosamente..!';
            }
            
        }

        echo json_encode($out);
    break;

    case 'editApprovalSubsidy':
        $sql = "SELECT aps.*  FROM approval_subsidy aps 
        WHERE aps.id = $id";
        $query = $obj_bdmysql->query($sql, $dbconn);
        // print_r($sql);exit();
        if (is_array($query)) {
            $obj = $query[0];

            $out['id_approval_subsidy']  = $obj['id'];
            $out['add_no_mayor_decree']  = $obj['no_mayor_decree'];
            $out['add_agreement_date']     = $obj['agreement_date'];
            $out['add_no_payment_decree']  = $obj['no_payment_decree'];
            $out['add_payment_date']  = $obj['payment_date'];
            $out['add_no_payment_installments']  = $obj['no_payment_installments'];

            $out['code'] = 200;
            $out['message'] = "Ok";
        }else{
            $out['code'] = 204;
            $out['message'] = "Error";
        }

        echo json_encode($out);
    break;

    case 'delApprovalSubsidy':
    // print_r($_POST);
        $obj_bdmysql->delete("approval_subsidy", "id= $id", $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Antecedente Eliminado con exito..!';

        echo json_encode($out);
    break;

    case 'changeStatus':

        $sql = "SELECT status from approval_subsidy where id = $id";
        $row = $obj_bdmysql->query($sql, $dbconn);
        //print_r($row);exit();
        if ($row[0]['status'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $fields = "status= '$status'";
        $where = "id = '$id'";
        $obj_bdmysql->update('approval_subsidy', $fields, $where, $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Estatus actualizado..!';

        echo json_encode($out);
    break;
}