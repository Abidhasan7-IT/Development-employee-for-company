<?php
include "header.php";
require_once "../connection.php";

// Retrieve employees from the database
$sql_emp = "SELECT * FROM `employee`";
$result_emp = mysqli_query($conn, $sql_emp);
$employees = mysqli_fetch_all($result_emp, MYSQLI_ASSOC);

// Set default filter date to today
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');

// Retrieve employee filter from GET parameter
$employee_filter = isset($_GET['employee']) ? $_GET['employee'] : '';

// Construct SQL query based on filter options
$sql = "SELECT * FROM attendance WHERE date = '$filter_date'";
if (!empty($employee_filter)) {
    $sql .= " AND name = '$employee_filter'";
}

// Execute SQL query
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

// Pagination variables
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Modify SQL query to include pagination
$sql .= " LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

?>

<div class="container bg-white shadow">
    <div class="py-4 mt-1">
        <h4 class="text-center">Attendance Board</h4>
        <!-- Filter options -->
        <form method="GET">
            <div class="form-row justify-content-center align-items-center d-flex mb-3 gap-3">
                <div class="col-md-2">
                    <label for="filter_date" class="sr-only">Filter Date</label>
                    <input type="date" class="form-control" id="filter_date" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="Filter Date">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="employee">
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $emp) : ?>
                            <option value="<?php echo $emp['name']; ?>" <?php if ($employee_filter == $emp['name']) echo 'selected="selected"'; ?>><?php echo $emp['name'] . " - " . $emp['email']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i></button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" onclick="printTable()"><i class="fa fa-print"></i></button>
                </div>

            </div>
        </form>

        <!-- Total hours worked -->
        <?php  if (!empty($employee_filter)) {  ?>
        <div class="mb-3">
            <h5 class="text-center">Total Hours Worked:
                <?php
                // Format the output with two decimal places for minutes
                echo number_format($total_hours_worked, 2) . " h";
                ?>
            </h5>
        </div>
        <?php  } ?>

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
                            $timeoutin = $rows["time"]; // Time out when status is "out"
                        }
                        if ($rows["status"] == "out") {
                            $timein='';
                            $timeout = $rows["time"]; // Time out when status is "out"
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
                            <td class="text-success"><?php echo $timein; ?></td>
                            <td class="text-danger"><?php echo $timeout; ?></td>
                            <td><?php echo number_format($hours_worked, 2); ?></td>
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
            // Get total number of records for pagination
            $sql_count = "SELECT COUNT(*) AS total FROM attendance WHERE date = '$filter_date'";
            if (!empty($employee_filter)) {
                $sql_count .= " AND name = '$employee_filter'";
            }
            $result_count = mysqli_query($conn, $sql_count);
            $row_count = mysqli_fetch_assoc($result_count);
            $total_records = $row_count['total'];
            $total_pages = ceil($total_records / $limit);

            echo "<ul class='pagination'>";
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "&filter_date=" . $filter_date . "&employee=" . $employee_filter . "'>&laquo; Previous</a></li>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . $i . "&filter_date=" . $filter_date . "&employee=" . $employee_filter . "'>" . $i . "</a></li>";
            }

            if ($page < $total_pages) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "&filter_date=" . $filter_date . "&employee=" . $employee_filter . "'>Next &raquo;</a></li>";
            }
            echo "</ul>";
            ?>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>

<script>
    function printTable() {
        var title = "<h3 class='text-center mb-4'>Attendance List</h3>";
        var printContents = title + document.querySelector(".table-hover").outerHTML; // Include the title
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
