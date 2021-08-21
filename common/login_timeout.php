<?php
session_start();
if (!isset($_SESSION["logueado"])) {
    $resp = array(
        'code' => '440', 
        'message' => 'Login Time-out',
        "draw" => 0,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => ""
    );
    echo json_encode($resp);
    die();
}