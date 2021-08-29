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

         $sql   = "SELECT a.*, r.rut, r.name, r.phone, r.email, s.name_proyect FROM accountability a
        INNER JOIN subvention s ON a.id_subvention = s.id 
        INNER JOIN organitation r ON s.id_organitation = r.id ";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        // print_r($sql);exit();
        
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE r.rut LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR r.name LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR a.name_represent LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR a.amount_delivered LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR a.amount_yielded LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR a.date_admission LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR a.status LIKE '%" . $requestData['search']['value'] . "%'";
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
                if($obj_function->validarPermiso($_SESSION['permissions'],'user_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="accountabilityController.edit(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>';
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'user_delete')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="accountabilityController.deleted(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';
                }

                if($row['status'] == 1) $status = '<span class="badge badge-warning">Pendiente</span>';
                if($row['status'] == 2) $status = '<span class="badge badge-success">Aprobada</span>';
                if($row['status'] == 3) $status = '<span class="badge badge-info">Observada</span>';
                if($row['status'] == 4) $status = '<span class="badge badge-danger">Devolución o Reintegro</span>';


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

    case 'saveNewAccountability':
        $fecha = date('Y-m-d');
        //print_r($_POST);exit;

        if ($id == 0) {
            //print_r("b");exit;
            $out['code'] = 204;
            $out['message'] = 'Error Insert...!';

            $campo = "id_subvention, name_represent, number_invoices, amount_delivered, amount_yielded, amount_refunded, balance, date_admission, status";
            $valor = "'$id_subvention', '$add_name_represent', '$add_invoice_number', '$add_mount_delivered', '$add_yielded', '$add_amount_refunded', '$add_balance', '$add_date_surrender_income', 1";
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
            }           
        }

        if ($id != 0) {
            // print_r($_POST);
            $out['code'] = 204;
            $out['message'] = 'Error Updated...!';
                $campo = "id_subvention='$id_subvention', name_represent='$add_name_represent', number_invoices='$add_invoice_number', amount_delivered='$add_mount_delivered', amount_yielded='$add_yielded', amount_refunded='$add_amount_refunded', balance='$add_balance', date_admission='$add_date_surrender_income'";

            $where = "id = '$id'";
            $update_accountability = $obj_bdmysql->update("accountability", $campo, $where, $dbconn);
            //$sql = "UPDATE `user` SET $campo WHERE $where";print($sql);exit();
            $cant =   count((array)$_POST['invoices_array']);
            if ($cant > 0) {
                foreach ($_POST['invoices_array'] as $value ) {
                    $date = $value[0]['value'];                    
                    $amount = $value[1]['value'];
                    $detail = $value[2]['value'];

                    $sql = "SELECT id FROM invoices WHERE `date`='$date' AND `amount`='$amount' OR `detail` LIKE '%$detail%'";
                    $r = $obj_bdmysql->query($sql, $dbconn);
                    $id_invoice = $r[0]['id'];
                   
                    if (!is_array($r)) {
                        $campo = "id_accountability, date, detail, amount";     
                        $valor = "'$id', '$date', '$detail' ,'$amount'";
                        $obj_bdmysql->insert("invoices", $campo, $valor, $dbconn);
                    }else{
                        $campo = "`date`='$date',`detail`='$detail',`amount`='$amount'";
                        $where = "id = '$id_invoice'";
                        $obj_bdmysql->update("invoices", $campo, $where, $dbconn);
                    }
                }
            }
            // exit;
            if ($update_accountability > 0) {
                $out['code'] = 200;
                $out['message'] = 'La rendición de cuenta fué actualizada exitosamente..!';
            }
            
        }

        echo json_encode($out);
    break;

    case 'editAccountability':
        $sql = "SELECT a.*, r.rut, r.name, r.phone, r.email, s.name_proyect 
        FROM accountability a
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

            $sql = "SELECT * FROM `invoices` WHERE id_accountability = $id";
            $result = $obj_bdmysql->query($sql, $dbconn);
            $invoices = '';
          
            foreach($result as $row)
            {     

                $count = 1;
                
                    $button = '';
                    if($count > 1)
                    {
                        $button = '<button type="button" name="remove" id="'.$count.'" class="btn btn-danger btn-xs remove">x</button>';
                    }
                    else
                    {
                        $button = '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
                    }
                    $amount = floatval($row['amount']);
                    $invoices .= '<tr id="row'.$count.'" class="test invoice_'.$count.'">
                            <td class="col-md-1">
                                <div class="rotate-15 text-center mt-4">
                                    <label for="">Factura '.$row['id'].'</label>
                                </div>                    
                            </td>
                            <td class="col-md-3">
                                <div class="form-group m-form__group"> 
                                    <label>Fecha</label>
                                    <input type="text" name="add_date_'.$row['id'].'" id="add_date" class="form-control dateAcc" value="'.$row['date'].'" required>
                                </div>
                            </td>
                            <td class="col-md-3"> 
                                <div class="form-group m-form__group"> 
                                    <label>Monto</label>
                                    <input type="number" name="add_amount_'.$row['id'].'" id="add_amount" class="form-control sum_amount" step="0.1" value="'.$amount.'" required>
                                </div>
                            </td>
                            <td class="col-md-4">
                                <div class="form-group m-form__group"> 
                                    <label>Detalle</label>
                                    <input type="text" name="add_detail_'.$row['id'].'" id="add_detail" class="form-control" value="'.$row['detail'].'" required>
                                </div>
                            </td>
                             
                        <td align="center">'.$button.'</td>
                    </tr>';
                    $count++;
                
            }
            $out['invoices'] = $invoices;            

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

    case 'checkRut':
        $sql   = "SELECT r.rut, r.name, r.phone, r.email, s.name_proyect, s.id AS id_subvention FROM organitation r 
        INNER JOIN subvention s ON s.id_organitation = r.id 
        WHERE rut = '$rut' ";
       
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
            $out['message'] = 'No existe una organizacion con este Rut!';
        }
        echo json_encode($out);
    break;
}