<?php
// Include header
include "header.php";

// Get user's email from session
$email = $_SESSION['email'];

// Database connection
require_once "../connection.php";

// Check if it's a new month
// $currentMonth = date('m');
// if(!isset($_SESSION['last_checked_month']) || $_SESSION['last_checked_month'] != $currentMonth) {
//     // If it's a new month, delete expired records
//     $sqlDelete = "DELETE FROM emp_leave WHERE MONTH(last_date) < '$currentMonth' AND email = '$email'";
//     mysqli_query($conn, $sqlDelete);

//     // Update last checked month in session
//     $_SESSION['last_checked_month'] = $currentMonth;
// }

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// SQL query to fetch leave records for the current page
$sql = "SELECT * FROM emp_leave WHERE email = '$email' LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

// Initialize counter for serial number
$i = ($page - 1) * $limit + 1;
?>

<!-- CSS styles for table -->
<style>
    table,
    th,
    td {
        border: 1px solid black;
        padding: 15px;
    }

    table {
        border-spacing: 10px;
    }
</style>

<!-- HTML content -->
<div class="container bg-white shadow">
    <div class="py-4 mt-1">
        <h4 class="text-center">Leave Status</h4>
        <!-- <p class="text-center text-secondary">Expired Delete Accepted leave per month </p> -->
        <div class="table-responsive">
            <table style="width:100%" class="table-hover text-center">
                <thead>
                    <tr class="bg-success text-light">
                        <th>S.No.</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Total Days</th>
                        <th>Leave Type</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are records
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through each record
                        while ($rows = mysqli_fetch_assoc($result)) {
                            $start_date = $rows["start_date"];
                            $last_date = $rows["last_date"];
                            $LeaveType = $rows["LeaveType"];
                            $reason = $rows["reason"];
                            $status = $rows["status"];
                            $id = $rows["id"];
                    ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo date("jS F", strtotime($start_date)); ?></td>
                                <td><?php echo date("jS F", strtotime($last_date)); ?></td>
                                <td>
                                    <?php
                                    $date1 = date_create($start_date);
                                    $date2 = date_create($last_date);
                                    // Increase $date2 by one day
                                    date_add($date2, date_interval_create_from_date_string('1 day'));

                                    $diff = date_diff($date1, $date2);
                                    echo $diff->format("%a days ");
                                    ?>
                                </td>
                                <td><?php echo $LeaveType; ?></td>
                                <td><?php echo $reason; ?></td>
                                <td><?php echo $status; ?></td>
                                <td>
                                    <a href='delete-leave.php?id=<?php echo $id; ?>' id='bin' class='btn-sm btn-danger '> <span><i class='fa fa-trash'></i></span> </a>
                                </td>
                            </tr>
                    <?php
                            $i++; // Increment counter
                        }
                    } else {
                        // If no records found
                        echo "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            // Count total records
            $total_query = "SELECT COUNT(*) AS total FROM emp_leave WHERE email = '$email'";
            $total_result = mysqli_query($conn, $total_query);
            $total_row = mysqli_fetch_assoc($total_result);
            $total_records = $total_row['total'];
            $total_pages = ceil($total_records / $limit);

            // Previous page link
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "' class='btn btn-sm btn-outline-secondary mr-2'>Previous</a>";
            }
            // Page links
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=" . $i . "' class='btn btn-sm " . ($page == $i ? 'btn-secondary' : 'btn-outline-secondary') . " mr-2'>$i</a>";
            }
            // Next page link
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "' class='btn btn-sm btn-outline-secondary'>Next</a>";
            }
            ?>
        </div>
    </div>

</div>

<?php
include "footer.php";
?>