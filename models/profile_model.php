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
    case 'saveProfile':
        $out['code'] = 204;
        $out['message'] = 'Error Updated...!';
        $fecha = date('Y-m-d');

        $campo = "rut='$add_rut', name='$add_name', last_name='$add_last_name', email='$add_email', phone='$add_phone', username='$add_username', update_at='$fecha'";
        $where = "id = '$id_user'";
        $update_persona = $obj_bdmysql->update("user", $campo, $where, $dbconn);
        //$sql = "UPDATE `user` SET $campo WHERE $where";
        //print($sql);exit();
        if ($update_persona > 0) {
            $out['code'] = 200;
            $out['message'] = 'Su perfil fué actualizado exitosamente..!';
        }   

        echo json_encode($out);
    break;

    case 'savePassword':

        $password_vieja = sha1($old_password);
        $password_nueva = sha1($new_password);

        $fecha = date('Y-m-d');
        $sql = "SELECT id FROM user WHERE id = $id_user AND password = '$password_vieja'";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code'] = 204;
            $out['message'] = 'Error Updated...!';

            $campo = "password='$password_nueva'";           
            $where = "id = '$id_user'";
            $update_usuario = $obj_bdmysql->update("user", $campo, $where, $dbconn);

            if ($update_usuario > 0) {
                $out['code'] = 200;
                $out['message'] = 'La contraseña fué actualizada exitosamente..!';
            }
            
        }else{
            $out['code'] = 204;
            $out['message'] = 'La contraseña actual no es correcta...!';
        }

        echo json_encode($out);
    break;

    case 'saveFoto':

        $out['code'] = 204;
        $out['message'] = 'Error Updated Foto...!';

        $campo = "path='$path', url='$url'";           
        $where = "id = '$id_user'";
        $update_persona = $obj_bdmysql->update("user", $campo, $where, $dbconn);
        //$sql = "UPDATE persona SET $campo WHERE $where";
        //print_r($sql);exit;
        if ($update_persona > 0) {
            $_SESSION['url'] = $url;
            $out['code'] = 200;
            $out['message'] = 'La foto fué actualizada exitosamente..!';
        }        

        echo json_encode($out);
    break;

    case 'findUser':

        $sql = "SELECT ro.name as role_name, u.* FROM user u LEFT JOIN role ro ON ro.id = u.id_role WHERE u.id = $id_user";
        $query = $obj_bdmysql->query($sql, $dbconn);
        //print_r($sql);exit;
        $obj = $query[0];

        $out['id']      = $obj['id'];
        $out['name']      = $obj['name'];
        $out['last_name']      = $obj['last_name'];
        $out['rut']      = $obj['rut'];
        $out['username']  = $obj['username'];
        $out['email']     = $obj['email'];
        $out['phone']     = $obj['phone'];
        $out['url']     = $obj['url'];
        $out['path']     = $obj['path'];
        $out['role_name']     = $obj['role_name'];
        //print_r($sql);exit;
        echo json_encode($out);
    break;

    case 'checkUserName':
        $sql   = "SELECT * FROM user WHERE username = '$username' AND id <> $id_user";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code']    = 200;
            $out['message'] = 'Este nombre de usuario no está disponible..!';
        }else{
            $out['code']    = 204;
            $out['message'] = 'ok..!';
        }
        echo json_encode($out);
    break;

    case 'checkEmail':
        $sql   = "SELECT * FROM user WHERE email = '$email' AND id <> $id_user";
        $query = $obj_bdmysql->query($sql, $dbconn);

        if (is_array($query)) {
            $out['code']    = 200;
            $out['message'] = 'Este email no está disponible..!';
        }else{
            $out['code']    = 204;
            $out['message'] = 'ok..!';
        }
        echo json_encode($out);
    break;

    case 'checkRut':
        $sql   = "SELECT * FROM user WHERE rut = '$rut' AND id <> $id_user";
        $query = $obj_bdmysql->query($sql, $dbconn);
        //print_r($sql);exit;
        if (is_array($query)) {
            $out['code']    = 200;
            $out['message'] = 'Este rut no está disponible..!';
        }else{
            $out['code']    = 204;
            $out['message'] = 'ok..!';
        }
        echo json_encode($out);
    break;
}


function roleName($role){
    $name = '';

    if ($role == 1) $name = "Administrador";
    if ($role == 2) $name = "Directorio";
    if ($role == 3) $name = "Gerente General";
    if ($role == 4) $name = "Staff Técnico";
    if ($role == 5) $name = "Área Médica";
    if ($role == 6) $name = "Administración";
    if ($role == 7) $name = "Comunicación";

    return $name;
}