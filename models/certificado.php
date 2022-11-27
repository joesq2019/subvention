<?php
   include '../common/login_timeout.php';
   require_once '../common/db.php';
   require_once '../common/bdMysql.php';
   require_once '../common/function.php';

   $obj_function = new coFunction();
   $obj_bdmysql = new coBdmysql();

   $id = $_GET["id"];
   $sql = "SELECT * FROM invoices WHERE id_accountability = $id";
   $query = $obj_bdmysql->query($sql, $dbconn);
   $invoices = $query;

   $sql = "SELECT * FROM accountability WHERE id = $id";
   $query = $obj_bdmysql->query($sql, $dbconn);
   $accountability = $query;

   $sql = "SELECT * FROM subvention WHERE id = ".$accountability[0]['id_subvention'];
   $query = $obj_bdmysql->query($sql, $dbconn);
   $subvention = $query;

   $sql = "SELECT * FROM approval_subsidy WHERE id_subvention = ".$subvention[0]['id'];
   $query = $obj_bdmysql->query($sql, $dbconn);
   $approval_subsidy = $query;

   $sql = "SELECT * FROM organitation WHERE id = ".$subvention[0]['id_organitation'];
   $query = $obj_bdmysql->query($sql, $dbconn);
   $organitation = $query;

   $no_mayor_decree = $approval_subsidy[0]['no_mayor_decree'];
   $agreement_date = date("d/m/Y", strtotime($approval_subsidy[0]['agreement_date']));
   $organitation_name = $organitation[0]['name'];
   $amount_purchases = $subvention[0]['amount_purchases'];
   $no_payment_installments = $approval_subsidy[0]['no_payment_installments'];
   $no_payment_decree = $approval_subsidy[0]['no_payment_decree'];
   $payment_date = date("d/m/Y", strtotime($approval_subsidy[0]['payment_date']));

   $name_proyect = $subvention[0]['name_proyect'];
   $no_session = $approval_subsidy[0]['no_session'];
   $session_date = date("d/m/Y", strtotime($approval_subsidy[0]['session_date']));
   header("Content-type: application/vnd.ms-word");
   header("Content-Disposition: attachment; Filename=certificado.doc");
?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="Windows-1252" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   </head>
   <body style="line-height: 30px">
 
      <h4 style="text-align: center;">CERTIFICADO N&deg;</h4>

      <p style="text-align: justify; text-indent: 40px"><strong>MARCIA PAREDES CARRIEZ</strong>, Directora de Control Interno de la Municipalidad de Arauco, viene a certificar lo siguiente: </p>

      <p style="text-align: justify; text-indent: 40px"><?php echo "Que mediante Decreto Alcaldicio N&deg; $no_mayor_decree de fecha $agreement_date, le fue aprobado a la organizaci&oacute;n $organitation_name, una subvenci&oacute;n de $amount_purchases mil pesos, cuyo monto se entreg&oacute; en $no_payment_installments cuotas, mediante Decreto de Pago N&deg; $no_payment_decree de fecha $payment_date"; ?>.</p>

      <p style="text-align: justify; text-indent: 40px"><?php echo "Que, el gasto se realiz&oacute; de acuerdo al Programa de Trabajo $name_proyect, aprobado por el Concejo Municipal en sesi&oacute;n ordinaria N&deg; $no_session de fecha $session_date. En rendici&oacute;n de cuentas se presenta"; ?> </p>

      <table class="default">
         <tr>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N&deg; 
               Factura &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
               Fecha &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
               Compra de: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </th>
         </tr>
         <?php
            $sum = 0;
            foreach($invoices as $value){
               echo "<tr>
                      <td style='text-align: center'>".$value['id']."</td>
                      <td style='text-align: center'>".date("d/m/Y", strtotime($value['date']))."</td>
                      <td style='text-align: center'>".$value['detail']."</td>
                    </tr>";
               $sum = $sum + $value['amount'];
            }
         ?>
      </table>      

        
      <p> La cual suma un total de <?php echo $sum; ?> mil pesos, dando as&iacute; por rendida la subvenci&oacute;n otorgada. </p>
      

      <p style="text-indent: 40px; text-align: justify;">
         Se extiende el presente certificado para ser presentado ante la Oficina de Contabilidad. 
      </p>
      <p>
         Arauco, <?php echo date("d/m/Y"); ?>
      </p>

      <p>
         MPC/pms <br>
         Distribuci&oacute;n <br>
         1. Direcci&oacute;n de Finanzas <br>
         2. Archivo Direcci&oacute;n Control
      </p> 
   </body>
</html>