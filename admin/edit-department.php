<?php
include "header.php";

// Database connection
require_once "../connection.php";

$name ="";
$nameErr = "";

// Fetch record to edit based on ID
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM department WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if(isset($row['d_name'])) {
            $name = $row["d_name"];
        }
    } else {
        echo "Record not found";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Form validation
    if (empty($_POST["d_name"])) {
        $nameErr = "<p style='color:red'> * Leave type is required</p>";
    } else {
        $name = $_POST["d_name"];
    }


    // Update record if validation passes
    if (!empty($name)) {
        $sql = "UPDATE department SET d_name = '$name' WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Successfully Updated'); window.location.href='edit-department.php?id=$id';</script>";
        } else {
            echo "<script>alert('Failed to update record');</script>";
        }
    }
}
?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="form-input-content">
                <div class="card login-form mb-0">
                    <div class="card-body pt-3 shadow">
                        <h4 class="text-center">Edit Department</h4>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=$id"); ?>">
                        <div class="form-group mb-2">
                                <label>Department Name :</label>
                                <input class="form-control" value="<?php echo $name; ?>" name="d_name" type="text" required>
                                <?php echo $nameErr; ?>
                            </div>

                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group">
                                    <input type="submit" value="Save Changes" class="btn btn-primary w-20" name="save_changes">
                                </div>
                                <div class="input-group">
                                    <a href="department.php" class="btn btn-primary w-20">Close</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
