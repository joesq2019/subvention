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
    case 'userList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'u.id',
            1 => 'u.rut',
            2 => 'u.name',
            3 => 'u.email',
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

    case 'saveNewUser':
        $fecha = date('Y-m-d');
        //print_r($_POST);exit;
        //$add_name = utf8_encode($add_name);
        //$add_apellido = utf8_encode($add_apellido);

        if ($id_user == 0) {
            //print_r("b");exit;
            $out['code'] = 204;
            $out['message'] = 'Error Insert...!';
            $password = sha1($add_password); 

            $campo = "rut, name, last_name, email, id_role, phone, username, password";
            $valor = "'$add_rut', '$add_name', '$add_last_name', '$add_email', '$add_role', '$add_phone', '$add_username', '$password'";
            $user_id = $obj_bdmysql->insert("user", $campo, $valor, $dbconn);
            //$sql = "INSERT INTO user ($campo) VALUES ($valor)";
            //print_r($sql);exit;            

            if ($user_id > 0) {
                $out['code'] = 200;
                $out['message'] = 'El usuario fué creado exitosamente..!';
            }           
        }

        if ($id_user != 0) {
            //print_r("a");exit;
            $out['code'] = 204;
            $out['message'] = 'Error Updated...!';

            if ($add_password != '') {
                $password = sha1($add_password); 
                $campo = "rut='$add_rut', name='$add_name', last_name='$add_last_name', email='$add_email', id_role='$add_role', phone='$add_phone', username='$add_username', update_at='$fecha', password='$password'";
            } else{
                $campo = "rut='$add_rut', name='$add_name', last_name='$add_last_name', email='$add_email', id_role='$add_role', phone='$add_phone', username='$add_username', update_at='$fecha'";
            }

            $where = "id = '$id_user'";
            $update_persona = $obj_bdmysql->update("user", $campo, $where, $dbconn);
            //$sql = "UPDATE `user` SET $campo WHERE $where";
            //print($sql);exit();
            if ($update_persona > 0) {
                $out['code'] = 200;
                $out['message'] = 'El usuario fué actualizado exitosamente..!';
            }
            
        }

        echo json_encode($out);
    break;

    case 'editUser':
        $sql = "SELECT r.id as type_role, u.* FROM user u LEFT JOIN role r on r.id = u.id_role WHERE u.id = $id";
        $query = $obj_bdmysql->query($sql, $dbconn);
        //print_r($sql);exit();
        if (is_array($query)) {
            $obj = $query[0];

            $out['id_user'] = $obj['id'];
            $out['name']      = $obj['name'];
            $out['last_name']      = $obj['last_name'];
            $out['rut']      = $obj['rut'];
            $out['username']  = $obj['username'];
            $out['email']     = $obj['email'];
            $out['type_role']  = $obj['type_role'];

            $out['phone']  = $obj['phone'];

            $out['code'] = 200;
            $out['message'] = "Ok";
        }else{
            $out['code'] = 204;
            $out['message'] = "Error";
        }

        echo json_encode($out);
    break;

    case 'delUser':

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

    case 'checkUserName':
        $sql   = "SELECT * FROM user WHERE username = '$username' AND id <> $id";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code']    = 204;
            $out['message'] = 'Este nombre de usuario no está disponible..!';
        }else{
            $out['code']    = 200;
            $out['message'] = 'ok..!';
        }
        echo json_encode($out);
    break;

    case 'checkEmail':
        $sql   = "SELECT * FROM user WHERE email = '$email' AND id <> $id";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code']    = 204;
            $out['message'] = 'Este email no está disponible..!';
        }else{
            $out['code']    = 200;
            $out['message'] = 'ok..!';
        }
        echo json_encode($out);
    break;

    case 'checkRut':
        $sql   = "SELECT * FROM user WHERE rut = '$rut' AND id <> $id";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code']    = 204;
            $out['message'] = 'Este rut no está disponible..!';
        }else{
            $out['code']    = 200;
            $out['message'] = 'ok..!';
        }
        echo json_encode($out);
    break;

    case 'findRoles':
        $sql = "SELECT id, name FROM role";
        $query = $obj_bdmysql->query($sql,$dbconn);

        $html="<option value=''>Seleccione una opción</option>";
        foreach ($query as $value){
            $html.="<option value='".$value['id']."'>".html_entity_decode($value['name'], ENT_QUOTES | ENT_HTML401, "UTF-8")."</option>";
        }
        //echo $html; 
        echo json_encode($html);
    break;
}