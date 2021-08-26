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
    	// print_r($_POST['financing']);
    	 
    	     	// the insert command and table columns 
    	$detalle = '';    	 
    	$keys = [];
    	$values = [];
    	$cant_f =  $cant = count((array)$_POST['financing'][0]);
    	foreach ($_POST['financing'][0] as $value ) {
			$keys[] = $value['name'];
			
			// foreach($value['value'] as $v){
			
			
		// }
   //  		  // $query = "INSERT INTO table ('details') VALUES ('$detalle')" ;
		}
		
		// $name = implode(', ', (array)$keys);
		// $valor = implode(',', (array)$values);
		// for ($i = 0; $i < $cant_f; $i++) {
		// 	$values[] = $_POST['financing'][0];
			if (strpos($name, 'inputDetails_') !== false) {
			   echo ($_POST['financing'][0][$i]['value']);
			}
		// }
		
		 
   		  // print_r($name);
		  	 
    	exit;
    	$organitation_name = $_POST['organitation']['name']; 
    	$organitation_rut = $_POST['organitation']['rut']; 
    	$organitation_address = $_POST['organitation']['address']; 
    	$organitation_email = $_POST['organitation']['email']; 
    	$organitation_phone = $_POST['organitation']['phone']; 
    	if ($id == 0) {
    		$out['code'] = 204;
            $out['message'] = 'Error Insert...!';

            $campo = "name, rut, address, email, phone, status";
            $valor = "$organitation_name, $organitation_rut, $organitation_address, $organitation_email, $organitation_phone, '1'";
            $organitation_id = $obj_bdmysql->insert("organitation", $campo, $valor, $dbconn);
            // $sql = "INSERT INTO user ($campo) VALUES ($valor)";
            // print_r($sql); exit();
             
            $campo = "id_organitation, year, name_proyect, objetive_proyect, quantity_purchases, amount_purchases, organization_contribution, amount_direct, amount_indirect, total_beneficiaries, quantity_activities, status";     
            $valor = "'$organitation_id', '$year', '$name_proyect', '$objetive_proyect', '$quantity_purchases', '$total_sum_price', '$amount_organitation', '$amount_direct', '$amount_indirect', '$total_sum_bene', '$quantity_activities', 1";
            $subvention_id = $obj_bdmysql->insert("subvention", $campo, $valor, $dbconn);

            $cant = count((array)$_POST['beneficiarios']);
            $type = ''; $name = ''; $address = ''; $phone = '';

	        if ($cant > 0) {

	            for ($i=0; $i < $cant; $i++) { 
	            	$type = $_POST['beneficiarios'][$i]['type']; 
	            	$name = $_POST['beneficiarios'][$i]['name']; 
	            	$address = $_POST['beneficiarios'][$i]['address']; 
	            	$phone = $_POST['beneficiarios'][$i]['phone'];

	                $campo = "type, id_subvention, name, address, phone,";     
            		$valor = "'$type', '$id_subvention', '$name' ,'$address' , '$phone'";
	                $obj_bdmysql->insert("members", $campo, $valor, $dbconn);
	                // $sql = "INSERT INTO user ($campo) VALUES ($valor)";print_r($sql);
	            }
	        }
	        $json = json_decode($_POST['financing'], true);
	        $cant_f =  $cant = count((array)$json);
	        // $v
	        	if ($cant > 0) {
		    		foreach ($json as $key => $value) {
		    			print_r($value);
		    		}
		    	}
	        // INSERT INTO `financing_details`(`id`, `id_subvention`, `details`, `unit_price`, `quantity`, `total_price`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]')
			exit;
            if ($subvention_id > 0) {
                $out['code'] = 200;
                $out['message'] = 'La subvención fué creada exitosamente..!';
            }           
    	 } else if ($id != 0) { 

    	 }

		//     [financing] => [ { "name": "iinputDetails_1", "value": "s" }, { "name": "inputUnityPrice_1", "value": "2" }, { "name": "inputQuantity_1", "value": "2" }, { "name": "inputDetails_2", "value": "e" }, { "name": "inputUnityPrice_2", "value": "3" }, { "name": "inputQuantity_2", "value": "3" } ]
	
		//     [activities] => [ { "name": "inputActivity_1", "value": "t" }, { "name": "inputMonthAct_1", "value": "03-2021" } ]
		// )
    	exit();
    break;

}