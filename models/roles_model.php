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
    case 'rolesList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'name'
        );

        $id_user = $_SESSION['id_user'];

        $sql = "SELECT * FROM role";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;

        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE id LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR name LIKE '%" . $requestData['search']['value'] . "%'";
        } 
        
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalFiltered = is_array($query) ? count($query) : 0;
        
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'];
        $query = $obj_bdmysql->query($sql, $dbconn);
        //print_r($sql);exit;
        $data = array();
        if (is_array($query)) {
            foreach ($query as $row) {
                $s = array();
                $botones = '';    

                if($obj_function->validarPermiso($_SESSION['permissions'],'roles_edit')){
                   $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="rolesController.edit(' . $row["id"] . ')"><i class="fas fa-edit" aria-hidden="true"></i></a></button>'; 
                }
                if($obj_function->validarPermiso($_SESSION['permissions'],'roles_delete') AND $row["id"] > 5){
                    $botones .= '<button class="btn btn-primary btn-sm mr-1" onclick="rolesController.deleted(' . $row["id"] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></button>';
                }
                
                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8");
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

    case 'saveRole':
        $fecha = date('Y-m-d');
        //print_r($_POST);exit;
        //$add_name = utf8_encode($add_name);
        //$add_apellido = utf8_encode($add_apellido);

        if ($id_role == 0) {
            //print_r("b");exit;
            $out['code'] = 204;
            $out['message'] = 'Error Insert...!';
                
            $json_permissions = $_POST['permissions'];

            $campo = "name, permissions";
            $valor = "'$add_name', '$json_permissions'";
            $rol_id = $obj_bdmysql->insert("role", $campo, $valor, $dbconn);
            //$sql = "INSERT INTO role ($campo) VALUES ($valor)";
            //print_r($sql);exit;
            if ($rol_id > 0) {
                
                $out['code'] = 200;
                $out['message'] = 'El rol fué creado exitosamente..!';
                
            }           
        }

        if ($id_role != 0) {
            //print_r("a");exit;
            $out['code'] = 204;
            $out['message'] = 'Error Updated...!';

            $json_permissions = $_POST['permissions'];
            //print_r($campo);exit;
            $campo = "name='$add_name', permissions='$json_permissions'";
            $where = "id = '$id_role'";
            $update_role = $obj_bdmysql->update("role", $campo, $where, $dbconn);

            if ($update_role > 0) {
             
                $out['code'] = 200;
                $out['message'] = 'El rol fué actualizado exitosamente..!';
                
            }
        }
        //print($out);exit();
        echo json_encode($out);
    break;

    case 'editRol':
        $sql = "SELECT name, permissions FROM role WHERE id = $id_rol";
        $q = $obj_bdmysql->query($sql, $dbconn);
        if (is_array($q)) {
            $result = $q[0];

            $out['code'] = 200;
            $out['message'] = "Ok..";
            
            $out['permissions'] = $result['permissions'];
            $out['name'] = html_entity_decode($result['name'], ENT_QUOTES | ENT_HTML401, "UTF-8");

        }else{
            $out['code'] = 204;
            $out['message'] = "Error";
        }

        echo json_encode($out);
    break;

    case 'checkname':
        $sql = "SELECT id FROM role WHERE name = '$add_name' AND id <> $id";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code']    = 204;
            $out['message'] = 'Este Nombre no está disponible..!';
        }else{
            $out['code']    = 200;
            $out['message'] = 'ok..!';
        }
        echo json_encode($out);
    break;

    case 'delRole':

        $obj_bdmysql->delete("role", "id= $id", $dbconn);

        $out['code']    = 200;
        $out['message'] = 'Rol Eliminado con exito..!';

        echo json_encode($out);
    break;
}