<?php 
    session_start();
    if( empty($_SESSION["email"]) ){
        header("Location: login.php");
    }
?>

<?php $page= substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <title>Admin</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- Template Stylesheet -->
    <link href="./css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-light position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar bg-light pe-4 pb-3 ">
            <nav class="navbar navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <!-- <h3 class="text-success"><i class="fa fa-hashtag me-2"></i>EAS</h3> -->
                    <div class="text-container">
                        <span>E</span>
                        <span>M</span>
                        <span>S</span>
                        <span>.</span>

                    </div>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/logo.png" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <span>Admin</span>
                    </div>
                </div>

                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link <?= $page== 'index.php'? 'active' : ''?> "><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>

                    <div class="navbar-nav w-100 ">

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle <?= $page== 'add-employee.php' || $page== 'manage-employee.php' ? 'active' : ''?> " data-bs-toggle="dropdown">
                            <i class="fa fa-users"></i> Employee
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 <?= $page== 'add-employee.php' || $page== 'manage-employee.php' ? 'show' : ''?>">
                                <a href="add-employee.php" class="dropdown-item nav-item nav-link <?= $page== 'add-employee.php'? 'active' : ''?> "><i class="fa fa-plus"></i> Add Employee</a> 
                                <a href="manage-employee.php" class="dropdown-item nav-item nav-link <?= $page== 'manage-employee.php'? 'active' : ''?> "> <i class="fa fa-address-card"></i> Manage Employee</a> 
                            </div>
                        </div>

                    </div>

                    <div class="navbar-nav w-100 ">

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle <?= $page== 'add-admin.php' || $page== 'manage-admin.php' ? 'active' : ''?> " data-bs-toggle="dropdown">
                            <i class="fa fa-user-plus"></i> Admin
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 <?= $page== 'add-admin.php' || $page== 'manage-admin.php' ? 'show' : ''?>">
                                 <a href="add-admin.php" class="dropdown-item nav-item nav-link <?= $page== 'add-admin.php'? 'active' : ''?>"><i class="fa fa-plus"></i> Add Admin</a> 
                                 <a href="manage-admin.php" class="dropdown-item nav-item nav-link <?= $page== 'manage-admin.php'? 'active' : ''?>"><i class="fa fa-address-book"></i> Manage Admin</a> 
                            </div>
                        </div>

                    </div>

                    <a href="department.php" class="nav-item nav-link <?= $page== 'department.php'? 'active' : ''?>"><i class="fas fa-chalkboard"></i> Department</a>

                    <div class="navbar-nav w-100 ">

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle <?= $page== 'add-leavetype.php' || $page== 'manage-leave.php' || $page== 'showlist.php' || $page== 'employee-leavestatus.php' ? 'active' : ''?> " data-bs-toggle="dropdown">
                            <i class="fa fa-th me-2"></i> Leave
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 <?= $page== 'add-leavetype.php' || $page== 'manage-leave.php' || $page== 'showlist.php' || $page== 'employee-leavestatus.php' ? 'show' : ''?> ">
                                 <a href="add-leavetype.php" class="dropdown-item nav-item nav-link <?= $page== 'add-leavetype.php'? 'active' : ''?> "><i class="fa fa-plus"></i> Add LeaveType</a> 
                                 <a href="manage-leave.php" class="dropdown-item nav-item nav-link <?= $page== 'manage-leave.php' ? 'active' : ''?> "> <i class="fa fa-eye-slash"></i> Manage Leave</a>
                                 <a href="showlist.php" class="dropdown-item nav-item nav-link <?= $page== 'showlist.php' ? 'active' : ''?> "> <i class="fa fa-list"></i> ShowList </a>
                                 <a href="employee-leavestatus.php" class="dropdown-item nav-item nav-link <?= $page== 'employee-leavestatus.php' ? 'active' : ''?> "> <i class="fa fa-check"></i> TotalLeave </a>
                            </div>
                        </div>

                    </div>
                    
                    <div class="navbar-nav w-100 ">

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle <?= $page== 'add-project.php' || $page== 'list-project.php' ? 'active' : ''?> " data-bs-toggle="dropdown">
                            <i class="fa fa-thumbtack"></i> Task
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 <?= $page== 'add-project.php' || $page== 'list-project.php' ? 'show' : ''?>">
                                 <a href="add-project.php" class="dropdown-item nav-item nav-link <?= $page== 'add-project.php'? 'active' : ''?>"><i class="fa fa-plus"></i> Add Task</a> 
                                 <a href="list-project.php" class="dropdown-item nav-item nav-link <?= $page== 'list-project.php'? 'active' : ''?>"><i class="fa fa-file"></i> ListTask</a> 
                            </div>
                        </div>

                    </div>
                    
                    <div class="navbar-nav w-100 ">

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle <?= $page== 'attendance-status.php' || $page== 'schedule.php' ? 'active' : ''?> " data-bs-toggle="dropdown">
                            <i class="fa fa-bell"></i> Attendance
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 <?= $page== 'attendance-status.php' || $page== 'schedule.php' ? 'show' : ''?>">
                                 <a href="attendance-status.php" class="dropdown-item nav-item nav-link <?= $page== 'attendance-status.php'? 'active' : ''?>"> <i class="fa fa-clock"></i> A-Status</a> 
                                 <a href="schedule.php" class="dropdown-item nav-item nav-link <?= $page== 'schedule.php'? 'active' : ''?>"> <i class="fa fa-business-time"></i> Schedule</a> 
                            </div>
                        </div>

                    </div>

                    <a href="noticeboard.php" class="nav-item nav-link <?= $page== 'noticeboard.php'? 'active' : ''?>"> <i class="fa fa-bullhorn"></i> Notice Board</a>
                    <a href="report.php" class="nav-item nav-link <?= $page == 'report.php' ? 'active' : '' ?>"> <i class="fa fa-cubes"></i> Report </a>
                    <a href="profile.php" class="nav-item nav-link <?= $page== 'profile.php'? 'active' : ''?>"><i class="fa fa-id-badge"></i> Profile</a>
                    <a href="left.php" class="nav-item nav-link <?= $page== 'left.php'? 'active' : ''?>"> <i class="fa fa-lock"></i> Left</a>

                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand  sticky-top px-4 py-0 nav-background">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <!-- <img class="rounded-circle me-lg-2" src="img/abid.jpg" alt="profile" style="width: 40px; height: 40px;"> -->
                          <?php 
                          include "../connection.php";  

                          $sql_command = "SELECT * FROM admin WHERE email = '$_SESSION[email]' ";
                          $result = mysqli_query($conn , $sql_command);
                      
                          if( mysqli_num_rows($result) > 0){
                             while( $rows = mysqli_fetch_assoc($result) )
                                  $dp = $rows["dp"];
                             }
                          
                          ?>
                            <img src="upload/<?php if(!empty($dp)){ echo $dp; }else{ echo "../img/logo.png"; } ?>" class="rounded-circle me-lg-2" style="width: 40px; height: 40px; "  alt="profile">
                            <span class="d-none d-lg-inline-flex">Admin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="profile.php" class="dropdown-item">My Profile</a>
                            <a href="./logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->



