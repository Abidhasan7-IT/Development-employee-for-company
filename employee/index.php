<?php
include "header.php";

?>

<!-- Navbar End -->

<?php
require_once "../connection.php";


$i = 1;

// applied leaves--------------------------------------------------------------------------------------------
$total_accepted = $total_pending = $total_canceled = $total_applied = 0;

$leave = "SELECT * FROM emp_leave WHERE email = '$_SESSION[email]' ";
$result = mysqli_query($conn, $leave);

if (mysqli_num_rows($result) > 0) {
    $total_applied = mysqli_num_rows($result);

    while ($leave_info = mysqli_fetch_assoc($result)) {
        $status = $leave_info["status"];

        if ($status == "pending") {
            $total_pending += 1;
        } elseif ($status == "Accepted") {
            $total_accepted += 1;
        } elseif ($status == "Canceled") {
            $total_canceled += 1;
        }
    }
} else {
    $total_accepted = $total_pending = $total_canceled = $total_applied = 0;
}

// leave status--------------------------------------------------------------------------------------------------------------
$currentDay = date('Y-m-d', strtotime("today"));

$upcoming_leave_status = "";
$last_leave_status = "No leave applied";

// for last leave status
$check_leave = "SELECT * FROM emp_leave WHERE email = '$_SESSION[email]' ";
$s = mysqli_query($conn, $check_leave);
if (mysqli_num_rows($s) > 0) {
    while ($info = mysqli_fetch_assoc($s)) {
        $last_leave_status =  $info["status"];
    }
}

// for next leave date
$check_ = "SELECT * FROM emp_leave WHERE email = '$_SESSION[email]' AND status = 'Accepted' AND start_date > '$currentDay' ORDER BY start_date ASC ";
$e = mysqli_query($conn, $check_);

if (!$e) {
    echo "Error: " . mysqli_error($conn);
}

if (mysqli_num_rows($e) > 0) {
    while ($info = mysqli_fetch_assoc($e)) {
        $date = $info["start_date"];
        $upcoming_leave_status = date('jS F', strtotime($date));
        break;
    }
} else {
    $upcoming_leave_status = " ";
}

// total employee--------------------------------------------------------------------------------------------
$select_emp = "SELECT * FROM employee";
$total_emp = mysqli_query($conn, $select_emp);

// total Department--------------------------------------------------------------------------------------------
$dept = "SELECT * FROM department";
$total_dept = mysqli_query($conn, $dept);

// highest paid employee--------------------------------------------------------------------------
$sql_highest_salary =  "SELECT * FROM employee ORDER BY salary DESC";
$emp_ = mysqli_query($conn, $sql_highest_salary);


// Count today's pending tasks
$sql_today_task = "SELECT COUNT(*) AS count FROM task WHERE status = 'pending' AND start_date = CURDATE()";;
$result_today_task = mysqli_query($conn, $sql_today_task);
$row_today_task = mysqli_fetch_assoc($result_today_task);
$today_task = $row_today_task['count'];

?>


<!-- dashboard Start -->
<div class="container-fluid px-4 pt-3 mb-2">
    <div class="row g-5">

    <div class="col-sm-6 col-xl-4">
            <div class="bg-primary rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-2 text-light">
                    <p class="mb-1 text-center heading"><i class="fas fa-users fa-0.5x" style="color: #ffffff;"></i> <b>Applied leaves</b></p>
                    <p>Total Pending  : <?php echo $total_pending; ?> </p>
                    <p>Total Applied  : <?php echo $total_applied; ?> </p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-4">
            <div class="bg-success rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-2 text-light">
                    <p class="mb-1 text-center heading"><i class="fas fa-user-check fa-0.5x" style="color: #ffffff;"></i> <b>Leave Status</b></p>
                    <p>Upcoming Leave on :  <?php echo  $upcoming_leave_status; ?>  </p>
                    <p>Last Leave's Status :  <?php echo ucwords($last_leave_status);  ?> </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="bg-danger rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-2 text-light">
                    <p class="mb-1 text-center heading"><i class="fas fa-users fa-0.5x" style="color: #ffffff;"></i> <b>leaves</b></p>
                    <p>Total Accepted  : <?php echo $total_accepted;  ?> </p>
                    <p>Total Canceled  : <?php echo $total_canceled; ?> </p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="bg-warning rounded d-flex align-items-center justify-content-between p-4">
            <div class="ms-1 text-light">
                    <p class="text-center heading"><i class="fas fa-user-check fa-0.5x" style="color: #ffffff;"></i> Task</p>
                    <p>Today pending : <?php echo $today_task; ?></p>
                    <div class="rounded"> 
                        <a href="task.php" class="text-center "><b>View All</b></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-4">
            <div class="bg-dark rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-1 text-light">
                    <p class="text-center heading"> <i class="fas fa-user fa-0.5x" style="color: #ffffff;"></i> <b>Employees</b></p>
                    <p>Total Employees : <?php echo mysqli_num_rows($total_emp); ?> </p>
                    <div class=" rounded"> <a href="view-employee.php"> <b>View All Employees</b></a> </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-4">
            <div class="bg-success rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-1 text-light">
                    <p class="text-center heading"><i class="fa fa-sitemap fa-0.5x" style="color: #ffffff;"></i> <b>Department</b></p>
                    <p>Department : <?php echo mysqli_num_rows($total_dept); ?> </p>
                    <div class=" rounded"> <a href="view-department.php"> <b>View Dept.</b></a> </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<!-- dashboard End -->

<!-- Table start -->
<div class="container-fluid pt-3 px-3 mb-3">
    <div class="bg-light text-center rounded p-3">
        <div class="d-flex align-items-center justify-content-center mb-1">
            <h5>Employee Leadership Board</h5>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="bg-secondary text-light text-center">
                        <!-- <th scope="col">S.No.</th> -->
                        <th scope="col">Employee Id</th>
                        <th scope="col">Employee's Name</th>
                        <th scope="col">Employee's Email</th>
                        <th scope="col">Position</th>
                        <th scope="col">Salary in Rs.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit = 4; // Limit the number of entries displayed
                    $count = 0; // Counter to keep track of entries displayed
                    while ($emp_info = mysqli_fetch_assoc($emp_)) {
                        $emp_id = $emp_info["e_id"];
                        $emp_name = $emp_info["name"];
                        $emp_email = $emp_info["email"];
                        $emp_Position = $emp_info["Position"];
                        $emp_salary = $emp_info["salary"];
                        ?>
                        <tr>
                            <th><?php echo $emp_id; ?></th>
                            <td><?php echo $emp_name; ?></td>
                            <td><?php echo $emp_email; ?></td>
                            <td><?php echo $emp_Position; ?></td>
                            <td><?php echo $emp_salary . " TK."; ?></td>
                        </tr>
                        <?php
                        $count++;
                        if ($count == $limit) { // Break the loop if the limit is reached
                            break;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if (mysqli_num_rows($emp_) > $limit) { ?>
            <button class="btn btn-dark text-center mt-3"><a href="view-employee.php">See More</a></button>
        <?php
        }
        ?>

    </div>

</div>



<!-- Footer Start -->

<?php
include "footer.php";

?>
