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
            0 => 's.id',
            1 => 'or.rut',
            2 => 'or.name',
            3 => 'or.email',
        );

        $id_user = $_SESSION['id_user'];

        $sql = "SELECT u.*, r.name as type_role FROM user u LEFT JOIN role r on r.id = u.id_role WHERE u.id <> $id_user";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        //print_r($sql);exit();
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE p.rut LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR u.name LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR u.last_name LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR u.email LIKE '%" . $requestData['search']['value'] . "%'";
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

                if($obj_function->validarPermiso($_SESSION['permissions'],'user_edit')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="userController.edit(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>';
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'user_delete')){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="userController.deleted(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';
                }

                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = $row['rut'];
                $nestedData[] = '<center>' . html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8") .' '. html_entity_decode($row['last_name'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>';    
                $nestedData[] = '<center>' . utf8_encode($row['email']) . '</center>';
                $nestedData[] = '<center>' . html_entity_decode($row['type_role'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>'; 
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
    	if ($id == 0) {
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
            }           
    	 } else if ($id != 0) { 

    	 }

    	echo json_encode($out);
    break;

}