<?php
include "header.php";
include "../connection.php";

$email = $_SESSION['email'];

// Initialize default filter dates
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Declare formatted dates variables
$formatted_start_date = '';
$formatted_end_date = '';

// Pagination variables
$limit = 7; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Construct SQL query based on filter dates and status
$sql = "SELECT * FROM task WHERE status = 'Done' AND emp_email='$email'";

// Add date filter conditions
if (!empty($start_date) && !empty($end_date)) {
    // Format dates for SQL query
    $formatted_start_date = date('Y-m-d', strtotime($start_date));
    $formatted_end_date = date('Y-m-d', strtotime($end_date));
    $sql .= " AND start_date >= '$formatted_start_date' AND end_date <= '$formatted_end_date'";
}

$sql .= " ORDER BY sub_date DESC LIMIT $start, $limit";

$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
    echo "Error: " . mysqli_error($conn);
}

// Initialize counter for serial number
$i = ($page - 1) * $limit + 1;
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
    <div class="py-4 mt-1">
        <h4 class="text-center">Task Done List</h4>

        <!-- Date filter form -->
        <form action="" method="GET" class="mb-2 ">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">

            <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> </button>
        </form>

        <div class="table-responsive">
            <table style="width:100%" class="table-hover text-center ">
                <!-- Table headers -->
                <tr class="bg-success text-light">
                    <th>SL</th>
                    <th>Taskname</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>StartDate</th>
                    <th>EndDate</th>
                    <th>Employee Name</th>
                    <th>Submission Date/Time</th>
                </tr>
                <?php
                // Check if there are records
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each record
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $start_date = $rows["start_date"];
                        $end_date = $rows["end_date"];
                        $sub_date = $rows["sub_date"];
                        $description = $rows["description"];
                        $empname = $rows["emp_name"];
                        $status = $rows["status"];
                        $name = $rows["name"];
                        $id = $rows["id"];
                ?>
                        <tr>
                            <!-- Display task details -->
                            <td><?php echo $i; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $description; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo date("jS F", strtotime($start_date)); ?></td>
                            <td><?php echo date("jS F", strtotime($end_date)); ?></td>
                            <td><?php echo $empname; ?></td>
                            <td><?php echo date("Y-m-d g:i:s A", strtotime($sub_date)); ?></td>
                        </tr>
                <?php
                        $i++; // Increment counter
                    }
                } else {
                    // If no records found
                    echo "<tr><td colspan='8' class='text-center'>No tasks found.</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            // Count total records
            $total_query = "SELECT COUNT(*) AS total FROM task WHERE status = 'Done' AND emp_email='$email'";
            if (!empty($start_date) && !empty($end_date)) {
                $total_query .= " AND start_date >= '$formatted_start_date' AND end_date <= '$formatted_end_date'";
            }
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
