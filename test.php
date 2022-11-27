<?php
   header("Content-type: application/vnd.ms-word");
   header("Content-Disposition: attachment; Filename=certificado.doc");
?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
 
   <head>
      <meta charset="Windows-1252" />
   </head>
 
   <body>
 
      <h4>CERTIFICADO N° %NumeroDecreto%</h4>
      <p>Este es un archivo de ejemplo donde se demuestran las posibilidades para generar archivos DOC dinámicamente.</p>
      <p><strong>MARCIA PAREDES CARRIEZ</strong>, Directora de Control Interno de la Municipalidad de Arauco, viene a certificar lo siguiente: </p>

      <p>Que mediante Decreto Alcaldicio N° %DecretoAlcaldicio% de fecha %fechadecretoalcaldicio%, le fue aprobado a la %NombreOrganización%, una subvención de %MontoSubvención% mil pesos, cuyo monto se entregó en %CantidadCueta%, mediante Decreto de Pago N°%NumeroDecretoPago% de fecha %fechadecretopago%.</p>
      <p>
         Que, el gasto se realizó de acuerdo al Programa de Trabajo %NombreProyectoSolicitudFinancimiento%, aprobado por el Concejo Municipal en sesión ordinaria N°%Numerodesesion% de fecha %fechasesionconcejo%. En rendición de cuentas se presenta %NumeroFactura1% de fecha %fechafactura1%, por la compra de %descripcionfactura1%; %NumeroFactura2% de fecha %fechafactura2%, por la compra de %descripcionfactura2%; %NumeroFactura3% de fecha %fechafactura3%, por la compra de %descripcionfactura3%; %NumeroFactura4% de fecha %fechafactura4%, por la compra de %descripcionfactura4%; %NumeroFactura5% de fecha %fechafactura5%, por la compra de %descripcionfactura5%; %NumeroFactura6% de fecha %fechafactura6%, por la compra de %descripcionfactura6%; %NumeroFactura7% de fecha %fechafactura7%, por la compra de %descripcionfactura7%; %NumeroFactura8% de fecha %fechafactura8%, por la compra de %descripcionfactura8%; %NumeroFactura9% de fecha %fechafactura9%, por la compra de %descripcionfactura9%; %NumeroFactura10% de fecha %fechafactura10 por la compra de %descripcionfactura10%; %NumeroFactura11% de fecha %fechafactura11%, por la compra de %descripcionfactura11%; %NumeroFactura12% de fecha %fechafactura12%, por la compra de %descripcionfactura12%; %NumeroFactura13% de fecha %fechafactura13%, por la compra de %descripcionfactura13%; %NumeroFactura14% de fecha %fechafactura14%, por la compra de %descripcionfactura14%; %NumeroFactura15% de fecha %fechafactura15%, por la compra de %descripcionfactura15%; %NumeroFactura16% de fecha %fechafactura16%, por la compra de %descripcionfactura16%; %NumeroFactura17% de fecha %fechafactura17%, por la compra de %descripcionfactura17%; %NumeroFactura18% de fecha %fechafactura18%, por la compra de %descripcionfactura18%; %NumeroFactura19% de fecha %fechafactura19%, por la compra de %descripcionfactura19%; %NumeroFactura20% de fecha %fechafactura20%, por la compra de %descripcionfactura20%. La cual suma un total de %sumafacturas% mil pesos, dando así por rendida la subvención otorgada.
      </p>

      <p>
         Se extiende el presente certificado para ser presentado ante la Oficina de Contabilidad. 
      </p>
      <p>
         Arauco, %fecha del dia que se genera el certificado%
      </p>

      <p>
         MPC/pms
         Distribución
         1. Dirección de Finanzas
         2. Archivo Dirección Control
      </p> 
   </body>
</html>