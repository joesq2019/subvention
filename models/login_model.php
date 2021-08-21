<?php
session_start();

date_default_timezone_set('America/Santiago');

require_once '../common/db.php';
require_once '../common/bdMysql.php';
require_once '../common/function.php';

require_once '../assets/phpmailer/PHPMailer.php';
require_once '../assets/phpmailer/Exception.php';
require_once '../assets/phpmailer/SMTP.php';

$obj_function = new coFunction();
$obj_bdmysql = new coBdmysql();

use PHPMailer\PHPMailer\PHPMailer;
$mail = new PHPMailer;

foreach ($_POST as $i_dato => $dato_) {
    $$i_dato = addslashes($obj_function->evalua_array($_POST, $i_dato));
}

switch ($method) {
    case 'login':
        //print_r($_SESSION);exit();
        if ($key == $_SESSION['key']) {
            $password = sha1($password);

            $sql = "SELECT u.id as id_user, u.id_role, u.status, u.url, u.theme, u.name, r.permissions, r.id as role_id FROM user u inner join role r on r.id = u.id_role WHERE u.username = '$username' AND password = '$password'";
            $q = $obj_bdmysql->query($sql, $dbconn);
            //print_r($sql);exit;
            if (is_array($q)) {
                if ($q[0]['role_id'] == '' AND $q[0]['permisos'] == '') {
                    $out['code'] = 900;
                    $out['message'] = "Su usuario no tiene asignado ningun rol, por favor contacte al administrador.";
                } else{
                    if ($q[0]['status'] == 1) {
                        $out['code'] = 200;
                        $out['message'] = "Bienvenido ".$q[0]['name'];

                        $_SESSION['id_role'] = $q[0]['id_role'];
                        $_SESSION['id_user'] = $q[0]['id_user'];
                        $_SESSION['name'] = $q[0]['name'];
                        $_SESSION['permissions'] = $q[0]['permissions'];
                        $_SESSION['theme'] = $q[0]['theme'];
                        $_SESSION['url'] = $q[0]['url'];

                        $_SESSION['logueado'] = true;

                    } elseif ($q[0]['status'] == 2) {
                        $out['code'] = 204;
                        $out['message'] = "Este usuario no está habilitado";
                    }
                } 
            } else {
                $out['code'] = 204;
                $out['message'] = "Usuario ó la contraseña no son válidas";
            }            
        }
        //print_r($out);exit;
        echo json_encode($out);
    break;

    case 'recover':

        if ($key == $_SESSION['key']) {

            $sql = "SELECT u.id as user_id, u.email as email FROM usuario u WHERE u.email = '$email'";
            $q = $obj_bdmysql->query($sql, $dbconn);
            
            if (is_array($q)) {
                $token = $obj_function->randString(40);
                $fecha = date('Y-m-d h:i:s');
                $user_id = $q[0]['user_id'];

                $campo = "token='$token', date_token='$fecha'";
                $where = "id = '$user_id'";
                $update_token = $obj_bdmysql->update("usuario", $campo, $where, $dbconn);

                if ($update_token > 0) {
                    $out['code'] = 200;
                    $out['message'] = "Ok.";

                    $email_message = '
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Forgot Password</title>
                            <style type="text/css">
                                .content{
                                    width:100%; background:#eee; position:relative; font-family:sans-serif; padding-bottom:40px;
                                }
                                .img-logo{
                                    padding:20px; width:20%
                                }
                                .body-email{
                                    position:relative; margin:auto; width:700px; background:white; padding:20px
                                }
                                .img-email{
                                    padding:20px; width:15%; border-radius: 50%;
                                }
                                @media (max-width: 768px) {
                                   .body-email {
                                     width: 80%;
                                   }
                                   .img-logo{
                                        width: 50%;
                                   }
                                   .img-email{
                                        width: 30%;
                                   }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="content">

                            <div class="body-email">

                                <center>

                                <img class="img-email" src="AQUIVAELLOGO">

                                <h3 style="font-weight:100; color:#666"><strong>Link para restablecer la contraseña de su usuario</strong></h3>

                                <hr style="border:1px solid #ccc; width:90%">

                                <h4 style="font-weight:100; color:#666; padding:0 20px">Para restablecer su contraseña, ingrese al siguiente enlace: <a href="http://localhost/subvenciones/views/login.php?recover='.$token.'" target="_blank">http://localhost/subvenciones/views/login.php?recover='.$token.'</a> haciendo click sobre el.</h4>

                                <h4 style="font-weight:100; color:#666; padding:0 20px">Si al hacer click no ocurre nada, puede copiar y pegar el enlance en una pestaña de su navegador, Gracias.</h4>

                                <br>

                                <hr style="border:1px solid #ccc; width:90%">

                                <h5 style="font-weight:100; color:#666">Si no reconoce este correo, puede ignorarlo.</h5>

                                </center>

                            </div>

                        </div>
                        </body>
                        </html>'
                    ;

                    //ENVIAR EMAIL
                    $mail->ClearAddresses();
                    $mail->ClearCCs();
                    $mail->ClearBCCs();

                    /*$mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    ); */   

                    $mail->isSMTP();
                    //$mail->SMTPDebug  = 4;
                    $mail->CharSet = "utf-8";
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Port = '465';
                    $mail->SMTPSecure = 'ssl';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'correo@gmail.com';
                    $mail->Password = 'xxxxxxxxxxxx';
                    $mail->setFrom('correo@gmail.com', 'Subvenciones');
                    $mail->addAddress($email);
                    $mail->Subject    = "Código de verificación para restablecer la contraseña";
                    $mail->Body = $email_message;
                    $mail->isHTML(true);

                    if($mail->send()){
                        $out['code']    = 200;
                        $out["message"] = 'Se ha enviado un mensaje a su correo para recuperar la contraseña, revise su bandeja de entrada (ó su bandeja de spam)...!';
                    } else {
                        $out['code']    = 204;
                        $out["message"] = 'Ha ocurrido un error, por favor contacte al administrador..!';
                    }
                }
            } else {
                $out['code'] = 204;
                $out['message'] = "El correo no pertenece a ningun usuario registrado.";
            }            
        }
        echo json_encode($out);
    break;

    case 'findToken':
        if ($key == $_SESSION['key']) {
        
            $sql = "SELECT u.email, u.date_token, u.id as user_id, u.username FROM user u WHERE u.token = '$token'";
            $q = $obj_bdmysql->query($sql, $dbconn);

            if (is_array($q)) {
                $fecha_actual = date('Y-m-d h:i:s');
                $fecha_token = $q[0]['date_token'];

                $date1 = new DateTime($fecha_token);
                $date2 = new DateTime($fecha_actual);
                $diff = $date1->diff($date2);

                if ($diff->days == 0) {
                    $user_id = $q[0]['user_id'];

                    $campo = "token='', date_token=null";
                    $where = "id = '$user_id'";
                    $update_user = $obj_bdmysql->update("usuario", $campo, $where, $dbconn);
                    //$update_user = 1;
                    
                    if ($update_user > 0) {

                        $out['email'] = $q[0]['email'];
                        $out['username'] = $q[0]['username'];
                        $out['id_user'] = $q[0]['user_id'];

                        $out['code'] = 200;
                        $out['message'] = "ok.";

                    }else{
                        $out['code'] = 204;
                        $out['message'] = "Error.";
                    }
                }else{
                    $out['code'] = 204;
                    $out['message'] = "El tiempo establecido para recuperar su contraseña se ha vencido, por favor vuelva a solicitar la recuperacion de contraseña.";
                }
            }else{
                $out['code'] = 204;
                $out['message'] = "El tiempo establecido para recuperar su contraseña se ha vencido, por favor vuelva a solicitar la recuperacion de contraseña.";
            }
        }//print_r($out);exit;
        echo json_encode($out);
    break;

    case 'savePassword':

        $out['code'] = 204;
        $out['message'] = 'Error...!';

        $password_nueva = sha1($new_password);
        $campo = "password='$password_nueva'";           
        $where = "id = '$user_id'";
        $update_usuario = $obj_bdmysql->update("usuario", $campo, $where, $dbconn);

        if ($update_usuario > 0) {
            $out['code'] = 200;
            $out['message'] = 'La contraseña fué actualizada exitosamente..!';
        }

        echo json_encode($out);
    break;

    case 'logout':
        session_destroy();
        echo json_encode(array('code'=>200,'message'=>'logout succesfully'));
    break;

}