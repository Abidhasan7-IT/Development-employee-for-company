<?php
include "header.php"; // Include header file
require_once "../connection.php";

// Pagination variables
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$email = $_SESSION['email'];
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');

$sql = "SELECT * FROM attendance WHERE email= '$email' AND date = '$filter_date' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Initialize total hours worked
$total_hours_worked = 0;

// Calculate total hours worked
while ($rows = mysqli_fetch_assoc($result)) {
    $timein = $rows["time"]; // Time in
    $timeout = ""; // Initialize time out

    // Check status to determine time in or time out
    if ($rows["status"] == "in") {
        $timeoutin = $rows["time"]; // Time out when status is "out"
    }
    if ($rows["status"] == "out") {
        $timeout = $rows["time"]; // Time out when status is "out"
    }

    // Calculate hours worked for each entry
    $hours_worked = 0;
    if ($timeoutin != "" && $timeout != "") {
        // Convert in time and out time to DateTime objects
        $time_in = new DateTime($timeoutin);
        $time_out = new DateTime($timeout);

        // Calculate the time difference in minutes
        $time_diff_minutes = ($time_out->getTimestamp() - $time_in->getTimestamp()) / 60;

        // Calculate hours and minutes separately
        $hours = floor($time_diff_minutes / 60);
        $minutes = $time_diff_minutes % 60;

        // Set the total hours worked
        $hours_worked = $hours + ($minutes / 100); // Convert minutes to a fractional part of an hour

        // Add hours worked to total
        $total_hours_worked += $hours_worked;
    }
}
?>

<!-- CSS styles for table -->
<style>
    table,
    th,
    td {
        border: 1px solid black;
        padding: 10px;
    }

    table {
        border-spacing: 10px;
    }
</style>

<div class="container bg-white shadow">
    <div class="py-4 mt-3">
        <h4 class="text-center">Attendance Board</h4>
        <!-- Filter options -->
        <form method="GET">
            <div class="form row justify-content-center align-items-center mb-3 gap-2">
                <div class="col-md-2">
                    <label for="filter_date" class="sr-only">Filter Date</label>
                    <input type="date" class="form-control" id="filter_date" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="Filter Date">
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> </button>
                </div>
            </div>
        </form>

        <!-- Total hours worked -->
        <div class="mb-3">
            <h5 class="text-center">Total Hours Worked:
                <?php
                // Format the output with two decimal places for minutes
                echo number_format($total_hours_worked, 2) . " h";
                ?>
            </h5>
        </div>

        <!-- Table to display attendance -->
        <div class="table-responsive">
            <table class="table table-hover text-center">
                <tr class="bg-warning text-light">
                    <th>SL</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours Worked</th>
                </tr>
                <?php
                // Initialize counter for serial number
                $i = 1;

                if (mysqli_num_rows($result) > 0) {
                    mysqli_data_seek($result, 0); // Reset the result pointer
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $date = $rows["date"];
                        $name = $rows["name"];
                        $timein = $rows["time"]; // Time in
                        $timeout = ""; // Initialize time out

                        // Check status to determine time in or time out
                        if ($rows["status"] == "in") {
                            $timein = $rows["time"]; 
                        }
                        if ($rows["status"] == "out") {
                            $timeout = $rows["time"]; 
                        }

                        // How many hours works by in and out time
                        $hours_worked = 0;
                        if ($timeoutin != "" && $timeout != "") {
                            // Convert in time and out time to DateTime objects
                            $time_in = new DateTime($timeoutin);
                            $time_out = new DateTime($timeout);

                            // Calculate the time difference in minutes
                            $time_diff_minutes = ($time_out->getTimestamp() - $time_in->getTimestamp()) / 60;

                            // Calculate hours and minutes separately
                            $hours = floor($time_diff_minutes / 60);
                            $minutes = $time_diff_minutes % 60;

                            // Set the total hours worked
                            $hours_worked = $hours + ($minutes / 100); // Convert minutes to a fractional part of an hour
                        }

                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $date; ?></td>
                            <td class="text-success"><?php echo $timein //date('g:i A', strtotime($timein)); ?></td>
                            <td class="text-danger"><?php echo $timeout //date('g:i A', strtotime($timeout)); ?></td>
                            <td><?php echo number_format($hours_worked, 2) . " h"; ?></td>
                        </tr>
                <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No attendance found.</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center">
            <?php
            $sql = "SELECT COUNT(*) AS total FROM attendance WHERE email= '$email' AND date = '$filter_date'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];
            $total_pages = ceil($total_records / $limit);

            echo "<ul class='pagination'>";
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>&laquo; Previous</a></li>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . $i . "'>" . $i . "</a></li>";
            }

            if ($page < $total_pages) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next &raquo;</a></li>";
            }
            echo "</ul>";
            ?>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>