<?php
include "header.php";

// Database connection
require_once "../connection.php";

// Initialize variables to store errors and form data
$LeaveTypeErr = $reasonErr = $startdateErr = $lastdateErr = "";
$option = $reason = $startdate = $lastdate = "";
$successMessage = "";
$errorMessage = "";

// Fetching employee's name from the database based on email
$employeeEmail = $_SESSION['email'];
$employeeName = "";
$fetchEmployeeQuery = "SELECT name FROM employee WHERE email = '$employeeEmail'";
$fetchEmployeeResult = mysqli_query($conn, $fetchEmployeeQuery);
if ($fetchEmployeeResult && mysqli_num_rows($fetchEmployeeResult) > 0) {
    $employeeData = mysqli_fetch_assoc($fetchEmployeeResult);
    $employeeName = $employeeData['name'];
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate LeaveType
    if (empty($_POST["option"])) {
        $LeaveTypeErr = "<p style='color:red'>* LeaveType is Required</p>";
    } else {
        $option = $_POST["option"];
    }

    // Validate Reason
    if (empty($_POST["reason"])) {
        $reasonErr = "<p style='color:red'>* Reason is Required</p>";
    } else {
        $reason = $_POST["reason"];
    }

    // Validate Start Date
    if (empty($_POST["startDate"])) {
        $startdateErr = "<p style='color:red'>* Start Date is Required</p>";
    } else {
        $startdate = $_POST["startDate"];
        // Check if the date is valid and not in the past
        if (strtotime($startdate) < strtotime(date('Y-m-d'))) {
            $startdateErr = "<p style='color:red'>* Start Date cannot be in the past</p>";
        }
    }

    // Validate Last Date
    if (empty($_POST["lastDate"])) {
        $lastdateErr = "<p style='color:red'>* Last Date is Required</p>";
    } else {
        $lastdate = $_POST["lastDate"];
        // Check if the date is valid and not in the past
        if (strtotime($lastdate) < strtotime(date('Y-m-d'))) {
            $lastdateErr = "<p style='color:red'>* Last Date cannot be in the past</p>";
        }
    }

    // Check if Start Date is before Last Date
    if (!empty($startdate) && !empty($lastdate)) {
        if (strtotime($startdate) > strtotime($lastdate)) {
            $startdateErr = "<p style='color:red'>* Start Date should be before Last Date</p>";
        }
    }

    // Check if the employee has already taken leave for the same period
    $checkLeaveQuery = "SELECT * FROM emp_leave WHERE email = '$employeeEmail' AND ((start_date >= '$startdate' AND start_date <= '$lastdate') OR (last_date >= '$startdate' AND last_date <= '$lastdate'))";
    $checkLeaveResult = mysqli_query($conn, $checkLeaveQuery);
    if (mysqli_num_rows($checkLeaveResult) > 0) {
        $errorMessage = "<div class='alert alert-warning alert-dismissible fade show'>
            <strong>Sorry! You have already taken leave for this period.</strong>
            <button type='button' class='close bg-transparent' data-dismiss='alert' >
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    } else {
        // If all fields are filled and there are no errors, proceed to insert data into the database
        if (empty($LeaveTypeErr) && empty($reasonErr) && empty($startdateErr) && empty($lastdateErr)) {
            // Check if the employee has already taken 5 accepted leaves in the current month
            $currentMonth = date('m');
            $currentYear = date('Y');
            $leaveCountQuery = "SELECT COUNT(*) AS leaveCount FROM emp_leave WHERE status = 'Accepted' AND MONTH(start_date) = $currentMonth AND YEAR(start_date) = $currentYear AND email = '$employeeEmail'";
            $leaveCountResult = mysqli_query($conn, $leaveCountQuery);
            $leaveCountRow = mysqli_fetch_assoc($leaveCountResult);
            $leaveCount = $leaveCountRow['leaveCount'];

            if ($leaveCount >= 4) {
                // Automatically apply for leave for the upcoming month
                $currentMonth = date('m', strtotime('+1 month'));
                $leaveCountQuery = "SELECT COUNT(*) AS leaveCount FROM emp_leave WHERE status = 'Accepted' AND MONTH(start_date) = $currentMonth AND YEAR(start_date) = $currentYear AND email = '$employeeEmail'";
                $leaveCountResult = mysqli_query($conn, $leaveCountQuery);
                $leaveCountRow = mysqli_fetch_assoc($leaveCountResult);
                $leaveCount = $leaveCountRow['leaveCount'];

                if ($leaveCount >= 4) {
                    $errorMessage = "<div class='alert alert-warning alert-dismissible fade show'>
                        <strong>Sorry! You have already taken 4 accepted leaves this month.</strong>
                        <button type='button' class='close bg-transparent' data-dismiss='alert' >
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>";
                } else {
                    $errorMessage = "<div class='alert alert-warning alert-dismissible fade show'>
                        <strong>Sorry! You have already taken 4 accepted leaves this month. Leave application automatically applied for the upcoming month.</strong>
                        <button type='button' class='close bg-transparent' data-dismiss='alert' >
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>";
                }
            } else {
                // Prepare and bind SQL statement to prevent SQL injection
                $sql = "INSERT INTO emp_leave (LeaveType, reason, start_date, last_date, email, name, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $option, $reason, $startdate, $lastdate, $_SESSION['email'], $employeeName);

                // Execute the statement
                if ($stmt->execute()) {
                    // Reset form fields
                    $option = $reason = $startdate = $lastdate = "";
                    $successMessage = "<div class='alert alert-success alert-dismissible fade show'>
                        <strong>Succefully! Applied..</strong>
                        <button type='button' class='close bg-transparent' data-dismiss='alert' >
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div> ";
                } else {
                    $errorMessage = "<div class='alert alert-warning alert-dismissible fade show'>
                        <strong>Error! Something is Wrong...</strong>
                        <button type='button' class='close bg-transparent' data-dismiss='alert' >
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div> ";
                }

                // Close statement and connection
                $stmt->close();
            }
        }
    }
}

?>

<!-- Your HTML form -->
<div class="login-form-bg h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-6 pt-3">
                <div class="form-input-content">
                    <?php
                    // Display success or error message if they are set
                    if (!empty($successMessage)) {
                        echo $successMessage;
                    } elseif (!empty($errorMessage)) {
                        echo $errorMessage;
                    }
                    ?>
                    <div class="card login-form mb-0">
                        <div class="card-body pt-3 shadow">
                            <h4 class="text-center">Apply For Leave</h4>
                            <p class="text-center">Limit: 4 Leave (per month)</p>

                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <div class="form-group mt-3 mb-3">
                                    <label class="col-form-label">Your Leave Type </label>
                                    <?php
                                    $sql = "SELECT LeaveType FROM tblleavetype";
                                    if ($output = mysqli_query($conn, $sql)) {
                                        if (mysqli_num_rows($output) > 0) {
                                            echo "<select class='bg-light rounded-2' name='option'>";
                                            // Adding an empty option as the default
                                            echo "<option value='' selected disabled>Select Leave Type</option>";
                                            while ($row = mysqli_fetch_array($output)) {
                                                $dbselected = $row['LeaveType'];
                                                echo "<option value='$dbselected'>$dbselected</option>";
                                            }
                                            echo "</select>";
                                        }
                                    }
                                    ?>
                                    <?php echo $LeaveTypeErr; ?>
                                </div>

                                <div class="form-group mb-2">
                                    <label>Comment:</label>
                                    <input type="text" class="form-control" value="<?php echo $reason; ?>" name="reason">
                                    <?php echo $reasonErr; ?>
                                </div>

                                <div class="form-group mb-2">
                                    <label>Starting Date:</label>
                                    <input type="date" class="form-control" value="<?php echo $startdate; ?>" name="startDate">
                                    <?php echo $startdateErr; ?>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Last Date:</label>
                                    <input type="date" class="form-control" value="<?php echo $lastdate; ?>" name="lastDate">
                                    <?php echo $lastdateErr; ?>
                                </div>

                                <div class="form-group mt-2">
                                    <input type="submit" value="Apply Now" class="btn btn-success btn-lg w-100 " name="signin">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>