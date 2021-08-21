<?php
session_start();
if(!isset($_SESSION['logueado']) || $_SESSION['logueado'] != true){
    header('Location:login.php');
}

require_once '../common/function.php';
$obj_function = new coFunction();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Subvenciones</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon.png">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../assets/fontawesome/css/all.css" rel="stylesheet" />   
    <?php if($_SESSION['theme'] == 'dark'): ?>  
        <link href="../assets/css/sb-admin-2-dark.min.css" class="estilo" rel="stylesheet">
    <?php else: ?>
        <link href="../assets/css/sb-admin-2.min.css" class="estilo" rel="stylesheet">
    <?php endif; ?>
    <link href="../assets/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/> 

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!--     Fonts and icons     -->
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- <script src="../assets/js/bootstrap.bundle.min.js"></script> -->
    <script src="../assets/js/sb-admin-2.min.js"></script>
    
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    
    <script type="text/javascript" src="../assets/js/datatables.min.js"></script>
    <script type="text/javascript" src="../assets/js/dataTables.bootstrap4.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- <script src="../assets/js/additionalvalidations.js"></script> -->
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