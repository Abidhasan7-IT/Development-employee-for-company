<?php
include "header.php";

// Initialize error variables
$nameErr = $startDateErr = $endDateErr = $employeeErr = $fileErr = $uploadErr = $sizeErr = "";
$name = $start_date = $end_date = $employee = $description = "";

// Database connection
require_once "../connection.php";

// Fetch employees from the database with email
$sql = "SELECT * FROM `employee` ORDER BY `name` ASC";
$result = mysqli_query($conn, $sql);

$employees = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
        $nameErr = "Task Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }

    // Check if a file is uploaded
    if (!empty($_FILES["file"]["name"])) {
        $target_dir = "../tasks/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $file_size = $_FILES["file"]["size"];

        // Check if the file is allowed (you can modify this to allow specific file types)
        $allowed_types = array("jpg", "docx", "png", "doc", "pdf");
        if (!in_array($file_type, $allowed_types)) {
            $fileErr = "Sorry, only JPG, DOCX, PNG, DOC, and PDF files are allowed.";
        } else {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // File upload success, now store information in the database
                $filename = $_FILES["file"]["name"];
                $filesize = $_FILES["file"]["size"];
                $filetype = $_FILES["file"]["type"];
            } else {
                $uploadErr = "Sorry, there was an error uploading your file.";
            }
        }

        if ($file_size > 18 * 1024 * 1024) {
            $sizeErr = "File size exceeds the maximum limit (18MB).";
        }
    } else {
        // No file uploaded, set filename and filetype as null
        $filename = null;
        $filetype = null;
        $filesize = null;
    }

    $current_date = date("Y-m-d");
    if (empty($_POST["start_date"])) {
        $startDateErr = "Start Date is required";
    } elseif ($_POST["start_date"] < $current_date) {
        $startDateErr = "Start Date must be today or later";
    } else {
        $start_date = test_input($_POST["start_date"]);
    }

    if (empty($_POST["end_date"])) {
        $endDateErr = "End Date is required";
    } elseif ($_POST["end_date"] < $start_date) {
        $endDateErr = "End Date cannot be earlier than Start Date";
    } else {
        $end_date = test_input($_POST["end_date"]);
    }

    if (empty($_POST["employee"])) {
        $employeeErr = "Employee is required";
    } else {
        $employee = test_input($_POST["employee"]);
        // Fetch email of selected employee
        $sql_emp = "SELECT * FROM `employee` WHERE `name`='$employee'";
        $result_emp = mysqli_query($conn, $sql_emp);
        $row_emp = mysqli_fetch_assoc($result_emp);
        $employee_email = $row_emp['email'];

        // Check if the selected employee has leave on the task dates
        $sql_leave = "SELECT * FROM `emp_leave` WHERE `email`='$employee_email' AND `start_date` <= '$start_date' AND `last_date` >= '$end_date'";
        $result_leave = mysqli_query($conn, $sql_leave);
        if (mysqli_num_rows($result_leave) > 0) {
            $employeeErr = "Employee has leave on selected dates";
        }
    }

    if (isset($_POST["description"])) {
        $description = test_input($_POST["description"]);
    }

    // If all fields are filled out and employee doesn't have leave, proceed with further processing
    if (empty($nameErr) && empty($fileErr) && empty($startDateErr) && empty($endDateErr) && empty($employeeErr) && empty($sizeErr)) {
        // Perform database insertion
        $sql_insert = "INSERT INTO `task`(`name`, `description`, `status`, `start_date`, `end_date`, `emp_name`, `emp_email`, `date_created`, `filename`, `filesize`, `filetype`) 
                      VALUES ('$name', '$description', 'pending', '$start_date', '$end_date', '$employee', '$employee_email', NOW(), '$filename', '$filesize', '$filetype')";
        if (mysqli_query($conn, $sql_insert)) {
            // Notify the selected employee
            echo "<div class='alert alert-success alert-dismissible fade show'> <strong> Task added successfully! </strong>
             <button type='button' class='close bg-transparent' data-dismiss='alert' >
            <span aria-hidden='true'>&times;</span>
          </button> </div>";

            // Clear input fields
            $name = $start_date = $end_date = $employee = $description = "";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . mysqli_error($conn) . " <button type='button' class='close bg-transparent' data-dismiss='alert' >
            <span aria-hidden='true'>&times;</span>
          </button>
          </div>";
        }
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


<!-- HTML form -->
<div class="login-form-bg h-100 mt-1">
    <div class="container h-100">
        <div class="row justify-content-center ">
            <div class="col-xl-7">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-3 shadow">
                            <h4 class="text-center">Add New Task</h4>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">

                                <!-- Task Name -->
                                <div class="form-group mb-1">
                                    <label>Task Name :</label>
                                    <input type="text" class="form-control" value="<?php echo $name; ?>" name="name">
                                    <span class="text-danger"><?php echo $nameErr; ?></span>
                                </div>

                                <!-- File Upload -->
                                <div class="form-group mb-2">

                                    <label for="file" class="form-label">Select file (Max: 18MB)</label>
                                    <input type="file" class="form-control" name="file" id="file">
                                    <span class="text-danger"><?php echo $uploadErr; ?></span>
                                    <span class="text-danger"><?php echo $sizeErr; ?></span>
                                    <span class="text-danger"><?php echo $fileErr; ?></span>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="" class="control-label">Start Date</label>
                                    <input type="date" class="form-control w-75 form-control-sm" autocomplete="off" name="start_date" value="<?php echo $start_date; ?>">
                                    <span class="text-danger"><?php echo $startDateErr; ?></span>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="" class="control-label">End Date</label>
                                    <input type="date" class="form-control w-75 form-control-sm" autocomplete="off" name="end_date" value="<?php echo $end_date; ?>">
                                    <span class="text-danger"><?php echo $endDateErr; ?></span>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="" class="control-label">Employee</label>
                                    <select class="form-control w-50 <?php echo ($employeeErr != '') ? 'is-invalid' : ''; ?>" name="employee">
                                        <option value="">Select Employee</option>
                                        <?php foreach ($employees as $emp) : ?>
                                            <option value="<?php echo $emp['name']; ?>"><?php echo $emp['name'] . " - " . $emp['email']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="text-danger"><?php echo $employeeErr; ?></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="" class="control-label">Description</label>
                                    <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-success text-light btn-block w-50 mt-2">Add Task</button>
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