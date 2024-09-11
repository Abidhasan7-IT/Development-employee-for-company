<?php
include "header.php";

// Database connection
require_once "../connection.php";

$type = $describe = "";
$typeErr = $descripeErr = "";

// Fetch record to edit based on ID
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tblleavetype WHERE Id = $id";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Check if the keys exist in the fetched row
        if(isset($row['leavetype'])) {
            $type = $row["leavetype"];
        }
        if(isset($row['Description'])) {
            $describe = $row["Description"];
        }
    } else {
        echo "Record not found";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Form validation
    if (empty($_POST["leavetype"])) {
        $typeErr = "<p style='color:red'> * Leave type is required</p>";
    } else {
        $type = $_POST["leavetype"];
    }

    if (empty($_POST["description"])) {
        $descripeErr = "<p style='color:red'> * Description is required</p>";
    } else {
        $describe = $_POST["description"];
    }

    // Update record if validation passes
    if (!empty($type) && !empty($describe)) {
        $sql = "UPDATE tblleavetype SET leavetype = '$type', Description = '$describe' WHERE Id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Successfully Updated'); window.location.href='edit-leavetype.php?id=$id';</script>";
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
                        <h4 class="text-center">Edit Leave Type</h4>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=$id"); ?>">
                            <div class="form-group mb-2">
                                <label>Leave Type:</label>
                                <input class="form-control" value="<?php echo $type; ?>" name="leavetype" type="text" required>
                                <?php echo $typeErr; ?>
                            </div>
                            <div class="form-group mb-2">
                                <label>Short Description:</label>
                                <input class="form-control" value="<?php echo $describe; ?>" name="description" type="text" autocomplete="off" required>
                                <?php echo $descripeErr; ?>
                            </div>
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group">
                                    <input type="submit" value="Save Changes" class="btn btn-primary w-20" name="save_changes">
                                </div>
                                <div class="input-group">
                                    <a href="add-leavetype.php" class="btn btn-primary w-20">Close</a>
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
