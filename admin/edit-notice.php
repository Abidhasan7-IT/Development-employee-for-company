<?php
include "header.php";

// Database connection
require_once "../connection.php";

$notice ="";
$noticeErr = "";

// Fetch record to edit based on ID
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM notice WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if(isset($row['notice'])) {
            $notice = $row["notice"];
        }
    } else {
        echo "Record not found";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Form validation
    if (empty($_POST["notice"])) {
        $noticeErr = "<p style='color:red'> * Notice is required</p>";
    } else {
        $notice = $_POST["notice"];
    }


    // Update record if validation passes
    if (!empty($name)) {
        $sql = "UPDATE notice SET notice = '$notice' WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Successfully Updated'); window.location.href='edit-notice.php?id=$id';</script>";
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
                        <h4 class="text-center">Edit NoticeBoard</h4>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=$id"); ?>">
                        <div class="form-group mb-2">
                                <label> Notice :</label>
                                <input class="form-control" value="<?php echo $notice; ?>" name="notice" type="text" required>
                                <?php echo $noticeErr; ?>
                            </div>

                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group">
                                    <input type="submit" value="Save Changes" class="btn btn-success w-20" name="save_changes">
                                </div>
                                <div class="input-group">
                                    <a href="noticeboard.php" class="btn btn-primary w-20">Close</a>
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
