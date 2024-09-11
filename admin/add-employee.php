<?php
// Include the header file
include "header.php";

// Define variables for error messages and form fields
$nameErr = $emailErr = $passErr = $salaryErr = $employeeidErr = $DepartErr = $fileErr = $uploadErr = $sizeErr = "";
$name = $email = $dob = $gender = $pass = $employeeid = $salary = $Position = $Depart = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and retrieve form data
    $name = validate_input($_POST["name"]);
    $email = validate_input($_POST["email"]);
    $dob = validate_input($_POST["dob"]);
    $gender = isset($_POST["gender"]) ? validate_input($_POST["gender"]) : ""; // Initialize $gender
    $pass = validate_input($_POST["pass"]);
    $employeeid = validate_input($_POST["employeeid"]);
    $Position = validate_input($_POST["Position"]);
    $Depart = validate_input($_POST["department"]);
    $salary = validate_input($_POST["salary"]);

    // Validate department selection
    if (empty($Depart)) {
        $DepartErr = "<p style='color:red'> * Department is required</p>";
    }

    // Check if a file is uploaded
    if (!empty($_FILES["file"]["name"])) {
        $target_dir = "../CV/";
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

    // If all required fields are filled and file uploaded successfully
    if (!empty($name) && !empty($email) && !empty($pass) && !empty($employeeid) && !empty($salary) && !empty($Depart) && empty($fileErr) && empty($uploadErr) && empty($sizeErr)) {
        // Include the database connection
        require_once "../connection.php";

        // SQL query to check if email already exists
        $sql_select_query = "SELECT email FROM employee WHERE email = '$email'";
        $result_select = mysqli_query($conn, $sql_select_query);

        if (mysqli_num_rows($result_select) > 0) {
            $emailErr = "<p style='color:red'> * Email Already Register</p>";
        } else {
            // SQL query to insert employee data
            $sql_insert_query = "INSERT INTO `employee`(`name`, `email`, `password`, `e_id`, `dob`, `gender`, `Position`, `d_name`, `salary`, `filename`, `filesize`, `filetype`, `status`) 
                                     VALUES ('$name', '$email', '$pass', '$employeeid', '$dob', '$gender', '$Position', '$Depart', '$salary', '$filename', '$filesize', '$filetype', 'active')";
            $result_insert = mysqli_query($conn, $sql_insert_query);

            if ($result_insert) {
                // Clear form fields on successful insertion
                $name = $email = $dob = $gender = $pass = $employeeid = $Position = $Depart = $salary = "";
                echo "<script>alert('Successfully Added');
                window.location.href = 'manage-employee.php'; </script>";
            } else {
                echo "Error: " . $sql_insert_query . "<br>" . mysqli_error($conn);
            }
        }
    }
}

// Function to validate form input
function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


<!-- adding new employee -->
<div class="login-form-bg h-100 mt-1">
    <div class="container h-100">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-3 shadow">
                            <h4 class="text-center">Add New Employee</h4>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                                <div class="form-group mb-2">
                                    <label>Full Name :</label>
                                    <input type="text" class="form-control" value="<?php echo $name; ?>" name="name">
                                    <?php echo $nameErr; ?>
                                </div>

                                <div class="form-group mb-2">
                                    <label>Email :</label>
                                    <input type="email" class="form-control" value="<?php echo $email; ?>" name="email">
                                    <?php echo $emailErr; ?>
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
                                    <label>Password: </label>
                                    <input type="password" class="form-control" value="<?php echo $pass; ?>" name="pass">
                                    <?php echo $passErr; ?>
                                </div>

                                <div class="form-group mb-2">
                                    <label>E_ID: </label>
                                    <input type="text" class="form-control" value="<?php echo $employeeid; ?>" name="employeeid">
                                    <?php echo $employeeidErr; ?>
                                </div>

                                <div class="form-group mb-2">
                                    <label>Position: </label>
                                    <input type="text" class="form-control" value="<?php echo $Position; ?>" name="Position">
                                </div>

                                <div class="form-group mb-2">
                                    <label for="" class="control-label">Department:</label>
                                    <select class="form-control w-50 <?php echo ($employeeErr != '') ? 'is-invalid' : ''; ?>" name="department">
                                        <option value="">Select Department</option>
                                        <?php
                                        // Include the database connection
                                        require_once "../connection.php";
                                        $sql_department = "SELECT d_name FROM department";
                                        $result_department = mysqli_query($conn, $sql_department);
                                        // Check if there are any departments
                                        if (mysqli_num_rows($result_department) > 0) {
                                            while ($row = mysqli_fetch_assoc($result_department)) {
                                                echo "<option value='" . $row['d_name'] . "'>" . $row['d_name'] . "</option>";
                                            }
                                        } else {
                                            // If no departments found
                                            echo "<option value=''>No Departments Found</option>";
                                        }
                                        ?>
                                    </select>
                                    <?php echo $DepartErr; ?>
                                </div>

                                <div class="form-group mb-2">
                                    <label>Salary :</label>
                                    <input type="number" class="form-control" value="<?php echo $salary; ?>" name="salary">
                                    <?php echo $salaryErr; ?>
                                </div>

                                <div class="form-group mb-2">
                                    <label>Date-of-Birth :</label>
                                    <input type="date" class="form-control" value="<?php echo $dob; ?>" name="dob">
                                </div>

                                <div class="form-group form-check form-check-inline">
                                    <label class="form-check-label">Gender :</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "Male") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Male" selected>
                                    <label class="form-check-label">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "Female") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Female">
                                    <label class="form-check-label">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "Other") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Other">
                                    <label class="form-check-label">Other</label>
                                </div>
                                <br>

                                <button type="submit" class="btn btn-success btn-block w-25 mt-2">Add</button>
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
