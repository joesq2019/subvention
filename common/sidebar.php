 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                    <!-- <img src="../assets/img/avatar.png" alt="" style="width: 50px"> -->
                </div>
                <div class="sidebar-brand-text mx-1">Subvenciones</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Panel de administracion</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interfaz
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <?php if($obj_function->validarPermiso($_SESSION['permissions'],'subvention_list')): ?>
            <li class="nav-item">
                <a class="nav-link" href="subvention.php">
                    <i class="fas fa-file-contract"></i>
                    <span>Subvenciones</span></a>
            </li>
            <?php endif; ?>
            <!-- Nav Item - Utilities Collapse Menu -->

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="far fa-address-book"></i>
                    <span>Rendición de Cuentas</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Ajustes
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <?php if($obj_function->validarPermiso($_SESSION['permissions'],'user_list')): ?>
            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span></a>
            </li>
            <?php endif; ?>
            
            <?php if($obj_function->validarPermiso($_SESSION['permissions'],'roles_list')): ?>
            <li class="nav-item">
                <a class="nav-link" href="roles.php">
                    <i class="far fa-id-badge"></i>
                    <span class="ml-1">Roles</span></a>
            </li>
            <?php endif; ?>
           

            <!-- Nav Item - Charts -->


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <div class="sidebar-card">
                <img class="mb-2 img-fluid" src="../assets/img/huachipato2.png" alt="">
            </div>

        </ul>