<?php
include "header.php";
require_once "../connection.php";

$sched_inerr = $sched_outerr = "";
$sched_in = $sched_out = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["sched_in"])) {
        $sched_inerr = "<p style='color:red'>* sched_in is Required</p>";
    } else {
        // Convert 24-hour format to 12-hour format
        $sched_in = date("h:i A", strtotime($_POST["sched_in"]));
    }
    if (empty($_POST["sched_out"])) {
        $sched_outerr = "<p style='color:red'>* sched_out is Required</p>";
    } else {
        // Convert 24-hour format to 12-hour format
        $sched_out = date("h:i A", strtotime($_POST["sched_out"]));
    }
}

if (!empty($sched_in) && !empty($sched_out)) {
    $sql = "INSERT INTO schedule (sched_in, sched_out) VALUES ('$sched_in', '$sched_out')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $name = $email = $dob = $gender = $pass = "";
        echo "<script>alert('Successfully Added');</script>";
    }
}

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM schedule LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

?>

<div class="container mt-5 mb-3">
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="form-input-content">
                <div class="card login-form mb-0">
                    <div class="card-body pt-3 shadow">
                        <h4 class="text-center">Set Schedule Time</h4>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="form-group mb-2 w-50">
                                <label>sched_in:</label>
                                <input type="time" class="form-control" value="<?php echo $sched_in; ?>" name="sched_in">
                                <?php echo $sched_inerr; ?>
                            </div>
                            <div class="form-group mb-3 w-50">
                                <label>sched_out:</label>
                                <input type="time" class="form-control" value="<?php echo $sched_out; ?>" name="sched_out">
                                <?php echo $sched_outerr; ?>
                            </div>
                            <button type="submit" class="btn btn-success btn-block w-25 mt-3">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule time table -->
<div class="container bg-white shadow mt-2">
    <div class="py-4 mt-5">
        <div class='text-center pb-2'>
            <h4>Schedule</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover ">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">S.No.</th>
                        <th scope="col">ScheduleIN</th>
                        <th scope="col">ScheduleOUT</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $i = $start + 1; // Initialize $i with the correct start value
                        while ($rows = mysqli_fetch_assoc($result)) {
                            $sched_in = $rows["sched_in"];
                            $sched_out = $rows["sched_out"];
                            $id = $rows["s_id"];
                    ?>
                            <tr>
                                <th class="text-center" scope="row"><?php echo $i; ?></th>
                                <td><?php echo $sched_in; ?></td>
                                <td><?php echo $sched_out; ?></td>
                                <td>
                                    <?php
                                    if (!empty($sched_in) && !empty($sched_out)) {
                                        $delete_icon = "<a href='delete-schedule.php?id={$id}' class='btn btn-danger btn-sm'>Delete</a>";
                                        echo  $delete_icon;
                                    }
                                    ?>
                                </td>
                            </tr>
                    <?php
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='4'>No Schedule found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            $sql = "SELECT COUNT(s_id) AS total FROM schedule";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];
            $total_pages = ceil($total_records / $limit);

            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "' class='btn btn-sm btn-outline-secondary mr-2'>Previous</a>";
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=" . $i . "' class='btn btn-sm " . ($page == $i ? 'btn-secondary' : 'btn-outline-secondary') . " mr-2'>$i</a>";
            }
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "' class='btn btn-sm btn-outline-secondary'>Next</a>";
            }
            ?>
        </div>

        <!-- Showing entries -->
        <div class="text-center mt-3">
            <?php
            $end = min($start + $limit, $total_records);
            echo "Showing " . ($start + 1) . " to $end of $total_records entries";
            ?>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
