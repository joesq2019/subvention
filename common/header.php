<?php
session_start();
if(!isset($_SESSION['logueado']) || $_SESSION['logueado'] != true){
    header('Location:login.php');
}

require_once '../common/function.php';
$obj_function = new coFunction();

?>

<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Subvenciones</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon.png">

    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> -->
    <link href="../assets/fontawesome/css/all.css" rel="stylesheet" />   
    <?php if($_SESSION['theme'] == 'dark'): ?>  
        <link href="../assets/css/sb-admin-2-dark.min.css" class="estilo" rel="stylesheet">
    <?php else: ?>
        <link href="../assets/css/sb-admin-2.min.css" class="estilo" rel="stylesheet">
    <?php endif; ?>
    

    <link href="../assets/css/common.css" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap-datepicker.min.css">

    <!--     Fonts and icons     -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'> -->

    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- <script src="../assets/js/bootstrap.bundle.min.js"></script> -->
    <script src="../assets/js/sb-admin-2.min.js"></script>
    
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap4.min.css"/>
     
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

    <script src="../assets/js/jquery.validate.js"></script>
    
    <script src="../assets/js/bootstrap-datepicker.min.js"></script>
    <!-- <script src="../assets/js/additionalvalidations.js"></script> --> 
    <script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once 'sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once 'nav.php'; ?>                
                <!-- End of Topbar -->

                <!-- INICIO DEL CONTAINER FLUID -->
                <div class="container-fluid">