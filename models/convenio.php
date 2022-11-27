<?php
   include '../common/login_timeout.php';
   require_once '../common/db.php';
   require_once '../common/bdMysql.php';
   require_once '../common/function.php';

   $obj_function = new coFunction();
   $obj_bdmysql = new coBdmysql();

   $id = $_GET["id"];

   $sql = "SELECT * FROM subvention WHERE id = ".$id;
   $query = $obj_bdmysql->query($sql, $dbconn);
   $subvention = $query;

   $sql = "SELECT * FROM members WHERE type = 'Presidente' AND id_subvention = ".$subvention[0]['id'];
   $query = $obj_bdmysql->query($sql, $dbconn);
   $members = $query;

   $sql = "SELECT * FROM approval_subsidy WHERE id_subvention = ".$subvention[0]['id'];
   $query = $obj_bdmysql->query($sql, $dbconn);
   $approval_subsidy = $query;

   $sql = "SELECT * FROM organitation WHERE id = ".$subvention[0]['id_organitation'];
   $query = $obj_bdmysql->query($sql, $dbconn);
   $organitation = $query;

   $name_organitation = $organitation[0]['name'] != "" ? $organitation[0]['name'] : "________________";
   $rut_organitation = $organitation[0]['rut'] != "" ? $organitation[0]['rut'] : "________________";
   $name_member = $members[0]['name'] != "" ? $members[0]['name'] : "";
   $rut_member = $members[0]['rut'] != "" ? $members[0]['rut'] : "";
   $address_organitation = $organitation[0]['address'] != "" ? $organitation[0]['address'] : "________________";
   // $organitation_name = $organitation[0]['name'];

   $amount_purchases = $subvention[0]['amount_purchases'];
   $formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
   $formatterES = $formatterES->format($amount_purchases);

   // $no_payment_installments = $approval_subsidy[0]['no_payment_installments'];

   $no_payment_decree = $approval_subsidy[0]['no_payment_decree'];
   $agreement_date =date("d/m/Y", strtotime($approval_subsidy[0]['agreement_date']));

   $mes_agreement_date = date("m", strtotime($approval_subsidy[0]['agreement_date']));
   $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
   $mes_agreement_date = $meses[$mes_agreement_date-1];

   //Run_representante

   // $payment_date = date("d/m/Y", strtotime($approval_subsidy[0]['payment_date']));

   // $name_proyect = $subvention[0]['name_proyect'];
   // $no_session = $approval_subsidy[0]['no_session'];
   // $session_date = date("d/m/Y", strtotime($approval_subsidy[0]['session_date']));

   //header("Content-type: application/vnd.ms-word");
   //header("Content-Disposition: attachment; Filename=certificado.doc");

         $filename='convenio.pdf';            
        $url_download = BASE_URL . RELATIVE_PATH . $filename;            

        header("Content-type:application/pdf");   
        header("Content-type: application/octet-stream");                       
        header("Content-Disposition:inline;filename='".basename($filename)."'");            
        header('Content-Length: ' . filesize($filename));   
        header("Cache-control: private"); //use this to open files directly                     
        readfile($filename);     
    //exit();

?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="Windows-1252" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   </head>
   <style>
      .column {
         float: left;
         width: 50%;
      }
   </style>
   <body style="line-height: 30px">
   
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- <img src="" alt="wff" style="height: 30px; width: 30px"> -->
      <p style="line-height: 1px; font-size: 12px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MUNICIPALIDAD DE ARAUCO</strong></p>
      <p style="line-height: 1px; font-size: 12px"><strong>DIRECCION DE DESARROLLO COMUNITARIO</strong></p>
      <p style="line-height: 1px; font-size: 12px"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ORGANIZACIONES COMUNITARIAS</strong></p><br>

      <h3 style="text-align: justify; text-indent: 40px"><strong>CONVENIO DE OTORGAMIENTO DE SUBVENCI&Oacute;N MUNICIPAL A <?php echo strtoupper($name_organitation); ?> A&Ntilde;O 2022.</strong></h3>

      <p style="text-align: justify; text-indent: 40px"><?php echo "En Arauco, a 21 de julio del a&ntilde;o 2022, entre la Municipalidad de Arauco, persona jur&iacute;dica de derecho p&uacute;blico, RUT N&deg; 69.160.100-5, representada por la Alcaldesa de la comuna, do&ntilde;a Elizabeth Noem&iacute; Maric&aacute;n Rivas, c&eacute;dula de identidad N&deg; 16.013.163-2, ambos domiciliados en calle Esmeralda N&ordm; 411 de la comuna de Arauco y $name_organitation RUT $rut_organitation representado legalmente por don $name_member c&eacute;dula de identidad N&deg; $rut_member, ambos domiciliados para estos efectos en $address_organitation de la comuna de Arauco, se acuerda suscribir el presente convenio para el otorgamiento de una Subvenci&oacute;n Municipal:"; ?>
      </p>

      <p style="text-align: justify;"><?php echo "<strong>PRIMERO</strong>: La Municipalidad de Arauco, para el cumplimiento de sus funciones, tiene entre sus atribuciones esenciales la de otorgar subvenciones y aportes para fines espec&iacute;ficos a personas jur&iacute;dicas de car&aacute;cter p&uacute;blico o privado, sin fines de lucro, que colaboren directamente en el cumplimiento de sus objetivos."; ?> </p>

      <p style="text-align: justify;"><?php echo "<strong>SEGUNDO</strong>: La Municipalidad ha aprobado a trav&eacute;s del D.A. N&ordm; $no_payment_decree de fecha $agreement_date del a&ntilde;o 2022, el otorgamiento de una Subvenci&oacute;n Municipal de $ $amount_purchases ($formatterES), a la entidad denominada &ldquo;$name_organitation&rdquo;. Esta suma se entregar&aacute; en 1 solo pago, el cual se efectuar&aacute; durante el mes de $mes_agreement_date del a&ntilde;o 2022, mediante transferencia electr&oacute;nica efectuada a la cuenta entregada por la organizaci&oacute;n para tales efectos."; ?> </p>

      <p style="text-align: justify;"><?php echo "<strong>TERCERO</strong>: La entidad beneficiaria deber&aacute; utilizar los recursos aportados por la Municipalidad de Arauco, en el programa, proyecto o servicio que se detalla a continuaci&oacute;n:"; ?> </p>

      <p style="text-align: justify;"><?php echo "<strong>CUARTO</strong>: La rendici&oacute;n de los gastos efectuados con cargo a la subvenci&oacute;n entregada, se deber&aacute; realizar de acuerdo con las formalidades establecidas en la Ordenanza de Subvenciones Municipales que se encuentre vigente en ese momento, documento que se entiende conocido por la entidad beneficiaria. Para tal efecto, se debe considerar lo establecido en el T&iacute;tulo VI y VII de dicho cuerpo normativo, denominado &ldquo;De las Rendiciones de Cuenta&rdquo;. "; ?> </p>


      <h3 style="text-align: center;"><strong>T&Iacute;TULO VI. </strong></h3>
      <h3 style="text-align: center;"><strong>DE LAS RENDICIONES DE CUENTA. </strong></h3>
       
      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 21&deg;</strong>: Todas las organizaciones que reciban una subvenci&oacute;n de la Municipalidad estar&aacute;n obligadas a rendir cuenta detallada de los gastos, los cuales deben ser coincidentes con el convenio y programa de trabajo. Las rendiciones deber&aacute;n realizarse conforme a la presente ordenanza, lo cual posteriormente ser&aacute; revisado por la Direcci&oacute;n de Control Interno, la que llevar&aacute; un archivo actualizado de todas ellas. Del mismo modo, la Direcci&oacute;n de Control tendr&aacute; la facultad de fiscalizar a las organizaciones que han recibido subvenciones municipales, respecto del uso y destino de los recursos, pudiendo solicitar la informaci&oacute;n que requiera para este efecto. "; ?> </p> 

      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 22&deg;</strong>: Las rendiciones de cuentas se presentar&aacute;n en la Oficina de Partes de la Municipalidad, en el &ldquo;Formulario de Rendici&oacute;n de Cuentas&rdquo;, el cual puede ser solicitado en la Direcci&oacute;n de Desarrollo Comunitario, Oficina de Recepci&oacute;n de la Municipalidad o en la Direcci&oacute;n de Control Interno. Formulario al cual se acompa&ntilde;ar&aacute; la documentaci&oacute;n original que respalda los gastos. S&oacute;lo se except&uacute;an de esta obligaci&oacute;n los organismos del Estado que reciban subvenci&oacute;n de la Municipalidad que est&eacute;n sujetos a la fiscalizaci&oacute;n de la Contralor&iacute;a General de la Rep&uacute;blica, los cuales deben presentar como rendici&oacute;n de cuentas, el correspondiente Original del Comprobante de Ingreso. S&oacute;lo se except&uacute;an de esta obligaci&oacute;n los Organismos del Estado que reciban subvenci&oacute;n de la Municipalidad que est&eacute;n sujetos a la fiscalizaci&oacute;n de la Contralor&iacute;a General de Rep&uacute;blica, los cuales deben presentar como rendici&oacute;n de cuentas, el correspondiente original del comprobante de ingreso. "; ?> </p> 

      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 23&deg;</strong>: Los gastos que se hagan con cargo a la subvenci&oacute;n deber&aacute;n efectuarse a partir de la fecha del Decreto Alcaldicio que aprueba el otorgamiento de la subvenci&oacute;n correspondiente al a&ntilde;o calendario aprobado, no se aceptaran gastos efectuados con anterioridad al otorgamiento, ni despu&eacute;s del 15 de diciembre del a&ntilde;o en que se otorg&oacute;. "; ?> </p> 

      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 24&deg;</strong>: Las rendiciones de cuenta deben presentarse, a m&aacute;s tardar, el 15 de diciembre de cada a&ntilde;o, salvo aquellas subvenciones otorgadas en forma excepcional en el mes de diciembre, cuyas rendiciones podr&aacute;n entregarse hasta el &uacute;ltimo d&iacute;a h&aacute;bil de ese mes. "; ?> </p> 

      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 25&deg;</strong>: Todo gasto deber&aacute; ser respaldado por su correspondiente documento mercantil en original y sin enmendaduras (factura, boleta de venta, boleta de servicio u honorario, etc.), registrados ante el Servicio de Impuestos Internos:"; ?> </p> 

      <p style="margin-left: 30px;text-align: justify;"> <?php echo "<strong>- A)</strong> Las facturas deber&aacute;n ser emitidas al nombre y RUT de la organizaci&oacute;n beneficiaria de la subvenci&oacute;n, en que se especifique claramente la compra o servicio, indicando su valor unitario neto y total. Las facturas deben presentarse canceladas por el proveedor, con su firma y fecha de cancelaci&oacute;n. <br> <strong>- B)</strong> Las facturas deber&aacute;n contener claramente el detalle de la mercader&iacute;a; en caso de identificar s&oacute;lo un n&uacute;mero de gu&iacute;a de despacho, se deber&aacute; adjuntar tal documento. <br> <strong>- C)</strong> Solo se aceptar&aacute;n los gastos relacionados con el destino para el cual se otorg&oacute; la subvenci&oacute;n.<br> <strong>- D)</strong> No se aceptar&aacute; para las rendiciones de cuenta compras efectuadas con tarjetas de cr&eacute;dito. e) Para compras mayores a 1 UTM se deber&aacute; presentar obligatoriamente factura, y en caso de que no sea procedente la emisi&oacute;n de tal instrumento tributario (compras menores a 1 UTM), ser&aacute;n admisibles las boletas, a las cuales deber&aacute; adjuntarse un detalle de los bienes y/o servicios pagados; dicho detalle deber&aacute; ser emitido por la casa comercial. <br> <strong>- E)</strong> Se deber&aacute; entregar conjuntamente con la rendici&oacute;n de cuentas un m&iacute;nimo de 3 fotograf&iacute;as representativas de la adquisici&oacute;n o contrataci&oacute;n, producto de la subvenci&oacute;n entregada. <br> <strong>- F)</strong> En el caso de tener que acreditar la entrega directa a beneficiarios con los recursos de la subvenci&oacute;n y de acuerdo al proyecto presentado, se deber&aacute; adjuntar un listado que contenga como m&iacute;nimo el nombre completo, el RUT, direcci&oacute;n, tel&eacute;fono y firma de cada beneficiario. <br> <strong>- G)</strong> Los fondos no utilizados por las organizaciones hasta el 15 de diciembre deber&aacute;n reintegrarse al municipio. "?> </p>
      
      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 26&deg;</strong>: Los pagos que se originen por concepto de Contratos Honorarios deben rendirse por su monto bruto, acompa&ntilde;ando una copia del contrato respectivo, boletas de honorarios con la firma y timbre del representante legal de la organizaci&oacute;n, certificado que acredite la recepci&oacute;n conforme del servicio. Trat&aacute;ndose de Contratos de Trabajo, se deber&aacute; presentar copia del contrato, la liquidaci&oacute;n de sueldo y copia del comprobante del pago previsional, finiquito de trabajo si correspondiera. En el caso de Contratos de Reparaciones se debe adjuntar a la factura, copia del contrato, tres presupuestos, y certificado de recepci&oacute;n conforme. "; ?> </p> 


      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 27&deg;</strong>: S&oacute;lo en casos calificados en que no se pueda obtener factura o boleta, se podr&aacute; utilizar el RECIBO, en el cual se especificar&aacute; el gasto, valor, motivo, fecha e identificaci&oacute;n del proveedor o prestador de servicio, cuyo monto no podr&aacute; ser superior a 1 UTM.:"; ?> </p>

      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 28&deg;</strong>: Excepcionalmente, en casos calificados, cuando se autorice efectuar gastos de locomoci&oacute;n, para rendir los pasajes se deber&aacute; presentar la citaci&oacute;n o invitaci&oacute;n, una planilla en la cual se indique el tipo de movilizaci&oacute;n, el valor, la fecha, el nombre, RUT y firma de la persona que incurri&oacute; en el gasto, todo visado por el representante de la organizaci&oacute;n."; ?> </p> 

      <h3 style="text-align: center;"><strong>T&Iacute;TULO VII. </strong></h3>
      <h3 style="text-align: center;"><strong>SANCIONES. </strong></h3>

      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 29&deg;</strong>: El incumplimiento en la presentaci&oacute;n de las rendiciones de cuentas o el no reintegro de los montos entregados por concepto de una subvenci&oacute;n facultar&aacute; a la Municipalidad para efectuar la cobranza judicial de los montos correspondientes."; ?> </p> 
      <p style="margin-left: 20px;text-align: justify;"><?php echo "<strong>Art&iacute;culo 30&deg;</strong>: La presentaci&oacute;n de rendici&oacute;n de cuentas con documentaci&oacute;n falsa o adulterada originar&aacute; las denuncias correspondientes ante la Fiscal&iacute;a Local. "; ?> </p> 

      <p style="text-align: justify;"><?php echo "<strong>QUINTO</strong>: La personer&iacute;a de don $name_member para representar legalmente al $name_organitation, consta en el art&iacute;culo &laquo;Art&iacute;culo_estatutos&raquo; letra &laquo;Letra_estatutos&raquo; de los &ldquo;Estatutos del $name_organitation&rdquo;, as&iacute; como, en Certificado extendido con fecha &laquo;Fecha_Certificado&raquo; del a&ntilde;o en curso, por don F&eacute;lix Rocha Ruiz, Secretario Municipal de la Municipalidad de Arauco, mediante el cual se acredita que el se&ntilde;or $name_member ocupa el cargo de Presidenta de la Organizaci&oacute;n Comunitaria Funcional referida con antelaci&oacute;n "; ?> </p>

      <p style="text-align: justify;"><?php echo "<strong>SEXTO</strong>: La personer&iacute;a de do&ntilde;a Elizabeth Noem&iacute; Maric&aacute;n Rivas para representar a la Municipalidad de Arauco, consta en Sentencia de Proclamaci&oacute;n de Alcalde n&uacute;mero 3 de fecha 30 de junio del a&ntilde;o 2021, pronunciada por el Tribunal Calificador de Elecciones de la VIII Regi&oacute;n del Bio Bio. "; ?> </p>

      <div class="row">
         <p class="column"><?php echo strtoupper($name_member)." PRESIDENTA ".strtoupper($name_organitation);?>
         </p>
         <p class="column"><?php echo "ELIZABETH NOEM&Iacute; MARIC&Aacute;N RIVAS ALCALDESA COMUNA DE ARAUCO";?>
         </p>
      </div>
   </body>
</html>