<?php
use PHPMailer\PHPMailer\PHPMailer;

class coFunction{
    //VALIDA Y DEPURA VALORES DE ARREGLOS
    function evalua_array($array,$i){
        if (array_key_exists($i,$array)){
            if (isset($array[$i])) {
                $resp = $array[$i];
            }else{
                $resp = "";
            }
        }else{
            $resp = "";
        }
        return $resp;
    }

    function randString($size){
        return substr(
                str_shuffle(
                    str_repeat(
                        $x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', ceil($size/strlen($x)) )
                )
                ,1
                ,$size
            );
    }

    function sendEmail($isSMTP, $host, $port, $SMTPSecure, $SMTPAuth, $from, $from_name, $password, $to, $cc, $bcc, $subject, $message, $files, $tracking, $random_id, $count, $recipient_type, $id_related, $header, $footer, $strg_pdf){
        //var_dump($isSMTP, $host, $port, $SMTPSecure, $SMTPAuth, $from, $from_name, $password, $to, $cc, $bcc, $subject, $message, $files, $tracking, $random_id, $count, $recipient_type, $id_related, $header, $footer, $strg_pdf); //die();

        require '../../vendor/autoload.php';
        $mail = new PHPMailer;

        include './general.php';
        $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNOM) or die("Connection failed: " . mysqli_connect_error()); /* Database connection end */
        $mail->ClearAddresses();  // each AddAddress add to list
        $mail->ClearCCs();
        $mail->ClearBCCs();

        $addAddress = explode(",", $to);
        $addCC = explode(",", $cc);
        $addBCC = explode(",", $bcc);
        if ($isSMTP == 1) {
            $mail->isSMTP();
        }
        $mail->CharSet = "utf-8";
        //$mail->SMTPDebug = 3;

        $mail->Host = $host;
        $mail->Port = $port;
        if ($SMTPSecure == 1) {
            $mail->SMTPSecure = 'tls';
        } elseif ($SMTPSecure == 2) {
            $mail->SMTPSecure = 'ssl';
        } else {
            $mail->SMTPSecure = false;
        }
        if ($SMTPAuth == 1) {
            $mail->SMTPAuth = true;
        } else {
            $mail->SMTPAuth = false;
        }
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->setFrom($from, $from_name);
        foreach ($addAddress as $email) {
            $mail->addAddress($email);
        }
        foreach ($addCC as $email) {
            $mail->addCC($email);
        }
        foreach ($addBCC as $email) {
            $mail->addBCC($email);
        }
        $mail->Subject = $subject;
        //$mail->Body = $email_plain_message;
        //$mail->AltBody = $email_plain_message;
        //$mail->addAttachment($attachment);

        if (is_array($files)) { // Armado de envio de attachs
            for ($ct = 0; $ct <= $count; $ct++) {
                $mail->AddAttachment($files['files-' . $ct]['tmp_name'], $files['files-' . $ct]['name']);
            }
        }

        if ($strg_pdf != '') { // Caso PDF de DomPDF en correo para agentes

            $mail->AddStringAttachment($strg_pdf, ''.$from_name.'.pdf', 'base64', 'application/pdf');
        }


        // Inicio tracking Pixel (construye el body con el message a enviar)
        if ($tracking == 1) {
            $originalImage = "/var/www/html/v103/img/tracking/LogoPowered.jpg";
            $userImage = "/var/www/html/v103/img/tracking/LogoPowered_" . $random_id . ".jpg";
            $urlImageSource = "https://services.gosmartcrm.com/img/tracking/LogoPowered_" . $random_id . ".jpg";
            copy($originalImage, $userImage);

            if (substr_count($message,'<html>') > 0) {
                $message = str_replace("</html>", "<img src=" . $urlImageSource . "  height='36' width='100'></html>", $message);
            } else {
                $message = "<html>".$message."<img src=" . $urlImageSource . "  height='36' width='100'></html>";
            }
        }
        //Fin tracking pixel

        // Inicio verificacion merge fields (construye el body con campos genericos)
        if ($recipient_type == 1) { //caso contact
            $sql = "SELECT * FROM contacts WHERE id = $id_related";
            $query = mysqli_query($conn, $sql) or die($sql);
            $data = mysqli_fetch_assoc($query);

        } elseif ($recipient_type == 2) { //caso lead
            $sql = "SELECT * FROM leads WHERE id = $id_related";
            $query = mysqli_query($conn, $sql) or die($sql);
            $data = mysqli_fetch_assoc($query);

        } elseif ($recipient_type == 3) { //caso user_crm
            $sql = "SELECT * FROM user_crm WHERE id = $id_related";
            $query = mysqli_query($conn, $sql) or die($sql);
            $data = mysqli_fetch_assoc($query);
        }

        $sql = "SELECT name, value, type_merge_field FROM merge_field WHERE status = 1";
        $query = mysqli_query($conn, $sql) or die($sql);
        extract(mysqli_fetch_array($query));

        foreach ($query as $row) {

            $merge_field_name = $row['name'];
            $merge_field_value = $row['value'];

            if (strpos($message, $merge_field_name) == true) {
                $value_data = $data["$merge_field_value"];
                $message = str_replace($merge_field_name, $value_data, $message);
            }
        }
        //Fin verificacion merge fields ---------------------------------------------------------------------------------------------------

        $mail->Body = $message;
        $mail->isHTML(true);

        // Inicio Caso Formularios Infinity, toca estandarizar procedimiento para otros clientes (tablas, disposicion de imagenes para otros header/footer, etc) DJ
        if ($header == 1) {
        $mail->AddEmbeddedImage('../../assets/media/img/images/header.png', 'header');
        }
        if ($footer == 1 ) {
        $mail->AddEmbeddedImage('../../assets/media/img/images/footer.png', 'footer');
        }
        // Fin Caso Formularios Infinity
        // print_r($mail->ErrorInfo);dies();
        //$mail->send();
        $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
            )
        );
        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return 'Email Error'. $mail->ErrorInfo;
        } else {
            //echo "Message sent!";
            return 'Email Sent';
        }
    }

    function users_permissions(){
        $p = [
            'users' => [
                'icon' => '<i class="fas fa-users"></i>',
                'title' => 'Modulo Usuarios',
                'keys' => [
                    'user_list' => 'Puede ver la lista de usuarios',
                    'user_add' => 'Puede agregar usuarios',
                    'user_edit' => 'Puede editar usuarios',
                    'user_delete' => 'Puede borrar usuarios'
                ]
            ],
            'roles' => [
                'icon' => '<i class="far fa-id-badge"></i>',
                'title' => 'Modulo Roles',
                'keys' => [
                    'roles_list' => 'Puede ver la lista de roles',
                    'roles_add' => 'Puede agregar roles',
                    'roles_edit' => 'Puede editar roles',
                    'roles_delete' => 'Puede borrar roles'
                ]
            ],
            'subvenciones' => [
                'icon' => '<i class="fas fa-file-contract"></i>',
                'title' => 'Modulo Subvencion',
                'keys' => [
                    'subvention_list' => 'Puede ver la lista de subvenciones',
                    'subvention_add' => 'Puede agregar subvenciones',
                    'subvention_edit' => 'Puede editar subvenciones',
                    'subvention_delete' => 'Puede borrar subvenciones'
                ]
            ],
            'accountability' => [
                'icon' => '<i class="fas fa-file-invoice-dollar"></i>',
                'title' => 'Modulo Rendicion de Cuentas',
                'keys' => [
                    'accountability_list' => 'Puede ver la lista de rendicion de cuentas',
                    'accountability_add' => 'Puede agregar rendicion de cuenta',
                    'accountability_edit' => 'Puede editar rendicion de cuenta',
                    'accountability_delete' => 'Puede borrar rendicion de cuenta'
                ]
            ],
            'budget_information' => [
                'icon' => '<i class="fas fa-file-invoice-dollar"></i>',
                'title' => 'Modulo Información Presupuestaria',
                'keys' => [
                    'budget_information_list' => 'Puede ver la lista de informaciones presupuestarias',
                    'budget_information_add' => 'Puede agregar informacion presupuestaria',
                    'budget_information_edit' => 'Puede editar informacion presupuestaria',
                    'budget_information_delete' => 'Puede borrar informacion presupuestaria'
                ]
            ],
            'approval_subsidy' => [
                'icon' => '<i class="fas fa-check-double"></i>',
                'title' => 'Modulo Aprovación de Subvención',
                'keys' => [
                    'approval_subsidy_list' => 'Puede ver la lista de aprobaciones de subvenciones',
                    'approval_subsidy_add' => 'Puede agregar una aprovación de subvencion',
                    'approval_subsidy_edit' => 'Puede editar una aprovación de subvencion',
                    'approval_subsidy_delete' => 'Puede borrar una aprovación de subvencion'
                ]
            ]
        ];
        return $p;
    }

    function validarPermiso($json, $key){
        if ($json == null) {
            return null;
        } else {
            $json = $json;
            $json = json_decode($json,true);
            if (array_key_exists($key, $json)) {
                return $json[$key];
            } else {
                return null;
            }
        }
    }
}