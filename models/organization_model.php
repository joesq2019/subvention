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
    case 'organizationList':

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'rut',
            2 => 'name',
            3 => 'address',
            4 => 'created_at'
        );

        $id_user = $_SESSION['id_user'];

        $sql = "SELECT * FROM organitation";
        $query = $obj_bdmysql->query($sql, $dbconn);
        $totalData = is_array($query) ? count($query) : 0;
        // print_r($sql);exit();
        
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE rut LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR name LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR address LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR created_at LIKE '%" . $requestData['search']['value'] . "%'";
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
                $botones .= '<a href="subvention.php?organization='.$row['id'].'" target="_blank" class="text-white" title="Ver Subvenciones"><button class="btn btn-info btn-sm mr-1" title="Ver Subvenciones" style="font-size:10px">Subvenciones</a></button></a>';
                $botones .= '<a href="accountability.php?organization='.$row['id'].'" target="_blank" class="text-white" title="Ver Rendiciones"><button class="btn btn-success btn-sm mr-1" title="Ver Rendiciones" style="font-size:10px">Rendiciones</a></button></a>';
                //$botones .= '<a href="subvention.php?organization='.$row['id'].'" target="_blank" class="text-white" title="Ver Subvenciones"><button class="btn btn-primary btn-sm mr-1" title="Ver Subvenciones" style="font-size:10px">Subvenciones</a></button></a>';
                
                $nestedData = array();
                $nestedData[] = $row['id'];
                $nestedData[] = $row['rut'];
                $nestedData[] = '<center>' . html_entity_decode($row['name'], ENT_QUOTES | ENT_HTML401, "UTF-8") . '</center>'; 
                $nestedData[] = '<center>' . html_entity_decode($row['address'], ENT_QUOTES | ENT_HTML401, "UTF-8") .'</center>';   
                $nestedData[] = '<center>' . utf8_encode($row['created_at']) . '</center>';
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
}