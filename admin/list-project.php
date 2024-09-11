<?php
include "header.php";
include "../connection.php";

// Pagination variables
$limit = 8; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Filter variables
$filter_start_date = isset($_GET['filter_start_date']) ? $_GET['filter_start_date'] : "";
$filter_end_date = isset($_GET['filter_end_date']) ? $_GET['filter_end_date'] : "";
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : "";

// Construct WHERE clause based on filters
$where_clause = "";
if (!empty($filter_start_date)) {
    $where_clause .= " AND start_date >= '$filter_start_date'";
}
if (!empty($filter_end_date)) {
    $where_clause .= " AND end_date <= '$filter_end_date'";
}
if (!empty($filter_status)) {
    $where_clause .= " AND status = '$filter_status'";
}

// Construct SQL query based on filter dates, status, ordering by id in descending order
$sql = "SELECT * FROM task WHERE 1 $where_clause ORDER BY id DESC LIMIT $start, $limit";
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
        padding: 10px;
    }

    table {
        border-spacing: 10px;
    }
</style>

<div class="container bg-white shadow">
    <div class="py-4 mt-1">
        <h4 class="text-center">Task Board</h4>

        <!-- Filter options -->
        <form method="GET">
            <div class="form row justify-content-center align-items-center mb-3 gap-2">
                <div class="col-md-2">
                    <label for="filter_start_date" class="sr-only">Start Date</label>
                    <input type="date" class="form-control" id="filter_start_date" name="filter_start_date" value="<?php echo $filter_start_date; ?>" placeholder="Start Date">
                </div>
                <div class="col-md-2">
                    <label for="filter_end_date" class="sr-only">End Date</label>
                    <input type="date" class="form-control" id="filter_end_date" name="filter_end_date" value="<?php echo $filter_end_date; ?>" placeholder="End Date">
                </div>
                <div class="col-md-2">
                    <label for="filter_status" class="sr-only">Filter Status</label>
                    <select class="form-control" id="filter_status" name="filter_status">
                        <option value="">Select Status</option>
                        <option value="pending" <?php echo ($filter_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="progress" <?php echo ($filter_status == 'progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="done" <?php echo ($filter_status == 'done') ? 'selected' : ''; ?>>Done</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> </button>
                </div>
                <!-- Print button -->
                <div class="col-md-1">
                    <button onclick="printTable()" class="btn btn-success"><i class="fa fa-print"></i> </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover text-center">
                <tr class="bg-success text-light">
                    <th>SL</th>
                    <th>Taskname</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>StartDate</th>
                    <th>EndDate</th>
                    <th>emp_name</th>
                    <th>Sub_date/Time</th>
                    <th>Action</th>
                </tr>
                <?php
                // Check if there are records
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each record
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $start_date = $rows["start_date"];
                        $last_date = $rows["end_date"];
                        $sub_date = $rows["sub_date"];
                        $description = $rows["description"];
                        $empname = $rows["emp_name"];
                        $status = $rows["status"];
                        $name = $rows["name"];
                        $id = $rows["id"];
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $description; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo date("jS F", strtotime($start_date)); ?></td>
                            <td><?php echo date("jS F", strtotime($last_date)); ?></td>

                            <td><?php echo $empname; ?></td>
                            <!-- Conditionally display sub_date -->
                            <td>
                                <?php
                                if ($status == 'progress' || $status == 'pending') {
                                    echo "Not submitted yet";
                                } else {
                                    echo date("Y-m-d g:i:s A", strtotime($sub_date));
                                }
                                ?>
                            </td>
                            <td>
                                <a href='delete-task.php?id=<?php echo $id; ?>' id='bin' class='btn-sm btn-danger '> <span><i class='fa fa-trash'></i></span> </a>
                            </td>
                        </tr>
                <?php
                        $i++; // Increment counter
                    }
                } else {
                    // If no records found
                    echo "<tr><td colspan='9' class='text-center'>No task found.</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            // Count total records
            $total_query = "SELECT COUNT(*) AS total FROM task WHERE 1 $where_clause";
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

<!-- JavaScript function for printing table -->
<script>
    function printTable() {
        var printWindow = window.open('', '', 'height=500,width=800');
        printWindow.document.write('<html><head><title>Leave List</title>');
        printWindow.document.write('</head><body class="text-center">');
        printWindow.document.write('<h3 class="text-center">Leave List</h3>');
        printWindow.document.write(document.querySelector('.table-hover').outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
