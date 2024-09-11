<?php
include "./connection.php";

// Initialize the error message variable
$e_iderror = '';
$in = '';
$inerror = '';
$out = '';
$outerror = '';
$leave_message = '';

// Schedule time
$schedule = "SELECT * FROM schedule";
$res = mysqli_query($conn, $schedule);
$sc = mysqli_fetch_assoc($res);
$scin = $sc['sched_in'];
$scout = $sc['sched_out'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['attendance'])) {
    // Retrieve data from the form
    $e_id = $_POST['e_id'];
    $activity = $_POST['activity'];

    // Check if employee exists
    $sql_check_employee = "SELECT * FROM employee WHERE e_id = '$e_id'";
    $result_check_employee = mysqli_query($conn, $sql_check_employee);
    if (mysqli_num_rows($result_check_employee) > 0) {
        $employee_data = mysqli_fetch_assoc($result_check_employee);
        $name = $employee_data['name'];
        $e_id = $employee_data['e_id'];
        $email = $employee_data['email'];
        $Statusemployee = $employee_data['status'];

        // Check if employee status is active
        if ($Statusemployee == 'active') {
            // Check if the selected employee has leave on the attendance dates
            $attendance_date = date("Y-m-d");
            $sql_leave = "SELECT * FROM emp_leave WHERE email='$email' AND start_date <= '$attendance_date' AND last_date >= '$attendance_date'";
            $result_leave = mysqli_query($conn, $sql_leave);
            if (mysqli_num_rows($result_leave) > 0) {
                // Employee has leave on selected dates
                $leave_message = "<p style='color: red;'>Employee has leave on selected date.</p>";
            } else {
                // Check if the employee has already punched in or out
                $sql_check_status = "SELECT status FROM attendance WHERE e_id = '$e_id' ORDER BY date DESC, time DESC LIMIT 1";
                $result_check_status = mysqli_query($conn, $sql_check_status);
                if ($result_check_status) {
                    if (mysqli_num_rows($result_check_status) > 0) {
                        $row = mysqli_fetch_assoc($result_check_status);
                        if (isset($row['status'])) {
                            $last_status = $row['status'];
                            if ($last_status == 'in' && $activity == 'out') {
                                // Employee is allowed to punch out
                                $attendance_date = date("Y-m-d");
                                $sql_update_attendance = "INSERT INTO attendance (e_id, name,email, date, status) VALUES ('$e_id', '$name','$email', '$attendance_date', '$activity')";
                                $result = mysqli_query($conn, $sql_update_attendance);
                                if ($result) {
                                    $out = "<p style='color: lime;'>Successfully Time out </p>";
                                } else {
                                    $outerror = "<p style='color: red;'>Something went wrong while punching out.</p>";
                                }
                            } elseif ($last_status == 'out' && $activity == 'in') {
                                // Employee has already timed out, can time in again
                                $attendance_date = date("Y-m-d");
                                $sql_update_attendance = "INSERT INTO attendance (e_id, name,email, date, status) VALUES ('$e_id', '$name','$email', '$attendance_date', '$activity')";
                                $result = mysqli_query($conn, $sql_update_attendance);
                                if ($result) {
                                    $in = "<p style='color: lime;'>Successfully Time in </p>";
                                } else {
                                    $inerror = "<p style='color: red;'>Something went wrong while punching in.</p>";
                                }
                            } else {
                                // Invalid activity or already timed in/out
                                if ($last_status == 'in') {
                                    $inerror = "<p style='color: red;'>Employee already punched in.</p>";
                                } elseif ($last_status == 'out') {
                                    $outerror = "<p style='color: red;'>Employee already punched out.</p>";
                                }
                            }
                        } else {
                            $inerror = "<p style='color: red;'>Error: Status not found.</p>";
                            $outerror = "<p style='color: red;'>Error: Status not found.</p>";
                        }
                    } else {
                        // No previous status found, employee needs to time in first if the activity is time out
                        if ($activity == 'out') {
                            $inerror = "<p style='color: red;'>Employee needs to time in first.</p>";
                        } else {
                            // If the activity is time in and there's no previous status, employee can punch in
                            $attendance_date = date("Y-m-d");
                            $sql_update_attendance = "INSERT INTO attendance (e_id, name,email, date, status) VALUES ('$e_id', '$name','$email', '$attendance_date', '$activity')";
                            $result = mysqli_query($conn, $sql_update_attendance);
                            if ($result) {
                                $in = "<p style='color: lime;'>Successfully Time in </p>";
                            } else {
                                $inerror = "<p style='color: red;'>Something went wrong while punching in.</p>";
                            }
                        }
                    }
                } else {
                    // Error in checking status
                    $inerror = "<p style='color: red;'>Error checking status.</p>";
                    $outerror = "<p style='color: red;'>Error checking status.</p>";
                }
            }
        } else {
            $e_iderror = "<p style='color: red;'>Employee with ID $e_id is deactive.</p>";
        }
    } else {
        // Employee does not exist
        $e_iderror = "<p style='color: red;'> Employee with ID $e_id does not exist. </p>";
    }

    // Clear input fields after submission
    $e_id = '';
    $activity = '';
}
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <link rel="shortcut icon" href="image/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="./first.css">
    <title>Homepage</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bgnav">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <a class="logo d-flex align-items-center text-warning text-decoration-none">
                <img src="image/logo.png" alt="Logo" class="logo-img">
                <h3 class="headtxt">Turnago Group.</h3>
            </a>

            <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>

            <div class="collapse navbar-collapse d-flex align-items-center" id="navbarNavAltMarkup">
                <button class="employee-btn"><a href="./employee/login.php">Employee</a></button>
                <button class="admin-btn"><a href="./admin/login.php">Admin</a></button>
            </div>

        </div>

    </nav>

    <div class="homepage">
        <div class="text-content">
            <h2 class="home-head"> Employee Management</h2>
            <p>Embark on a transformative journey with Turnago Group, a multifaceted enterprise shaping excellence across
                diverse sectors. From pioneering the realms of Travel & Tourism through Turnago Enterprise, and
                cutting-edge Software Development with Turnago IT, to the serene retreats of Turnago Lounge & Resort, and
                groundbreaking initiatives in Agro-Based Projects, Turnago stands as a beacon of innovation. E-Turnago,
                Turnago Holdings, and the Humandity Charity Foundation further exemplify our commitment to holistic
                development and societal well-being. Join us as we redefine possibilities and inspire positive change
                with every endeavor.</p>
        </div>

        <div class="employee-activites">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mt-2">
                    <p class="text-success">Office Time: <?php echo $scin . " - " . $scout ?> </p>
                </div>

                <div class="input-on">
                    <label style="color: white;">E_ID:</label>
                    <input type="text" class="text-in" value="<?php echo isset($e_id) ? $e_id : ''; ?>" name="e_id" />
                    <?php echo $e_iderror; ?>
                </div>

                <div class="select-option" style="margin-top:1rem; border: none;">
                    <select name="activity"
                        style="background: rgba(255, 255, 255, 0.555); border-radius: 10px; padding:4px 10px; font-weight:600;">
                        <option value="in">Time In</option>
                        <option value="out">Time Out</option>
                    </select>
                    <?php echo $in; ?>
                    <?php echo $inerror; ?>
                    <?php echo $out; ?>
                    <?php echo $outerror; ?>
                    <?php echo $leave_message; ?>
                </div>

                <div class="button-on" style="margin:1.8rem auto;">
                    <button type="submit" name="attendance" class="submit-btn">Submit</button>
                </div>
            </form>

            <div id="time" class="timeshow" onload='showTime()'>
                <?php
                date_default_timezone_set("Asia/Dhaka");
                echo date("h : i : s a");
                ?>
            </div>
        </div>

        <footer>
            Developed by @bid Hasan © 2024.
        </footer>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" defer
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

        <script defer>
            // Function to update time
            function updateTime() {
                var now = new Date();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds();
                var meridian = hours >= 12 ? "PM" : "AM";

                // Convert hours to 12-hour format
                hours = hours % 12;
                hours = hours ? hours : 12; // If hours is 0, set it to 12

                // Pad single digit minutes and seconds with leading zeros
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                // Update the time display
                document.getElementById('time').innerHTML = hours + " : " + minutes + " : " + seconds + " " + meridian;
            }

            // Call updateTime initially
            updateTime();

            // Call updateTime every second (1000 milliseconds)
            setInterval(updateTime, 1000);
        </script>

</body>

</html>
