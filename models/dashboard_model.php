<?php
session_start();
require_once '../common/db.php';
require_once '../common/bdMysql.php';
require_once '../common/function.php';

$obj_function = new coFunction();
$obj_bdmysql = new coBdmysql();

foreach ($_POST as $i_dato => $dato_) {
    $$i_dato = addslashes($obj_function->evalua_array($_POST, $i_dato));
}

switch ($method) {
    case 'findDashboardData':

        $sql = "SELECT (select count(*) as count FROM user where id_role = r.id) as cantidad, r.name, r.id FROM role r; ";
        $query = $obj_bdmysql->query($sql, $dbconn);
        //print_r($q);exit;
        if (is_array($query)) {
            $out['code'] = 200;
            $out['message'] = "Ok...";

            $out['data'] = $query;            
        } else {
            $out['code'] = 204;
            $out['message'] = "";
        }            
        //print_r($out);exit;
        echo json_encode($out);
    break;

    case 'change_theme':

        $out['code'] = 204;
        $out['message'] = 'Error...!';

        if ($status == 1) {
            $_SESSION['theme'] = 'dark';
            $theme = 'dark';
        } else {
            $_SESSION['theme'] = 'light';
            $theme = 'light';
        }

        $id_user = $_SESSION['id_user'];

        $campo = "theme='$theme'";           
        $where = "id = '$id_user'";
        $update_usuario = $obj_bdmysql->update("user", $campo, $where, $dbconn);

        if ($update_usuario > 0) {
            $out['code'] = 200;
            $out['message'] = 'Tema actualizado..!';
        }

        echo json_encode($out);
    break;
}