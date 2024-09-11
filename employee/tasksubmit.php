<?php
include "header.php";
require_once "../connection.php";

$email = $_SESSION['email'];

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Construct SQL query based on filter dates and status
$sql = "SELECT * FROM task WHERE status = 'progress' AND emp_email='$email' LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);
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
        <h4 class="text-center">Task Submit</h4>

        <div class="table-responsive">
            <table style="width:100%" class="table-hover text-center ">
                <tr class="bg-success text-light">
                    <th>SL</th>
                    <th>Taskname</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>StartDate</th>
                    <th>EndDate</th>
                    <th>Filename</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
                <?php
                // Check if there are records
                if (mysqli_num_rows($result) > 0) {
                    $i = $start + 1;
                    // Loop through each record
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $start_date = $rows["start_date"];
                        $last_date = $rows["end_date"];
                        $description = $rows["description"];
                        $empname = $rows["emp_name"];
                        $status = $rows["status"];
                        $name = $rows["name"];
                        $id = $rows["id"];
                        $file_name = $rows['filename'];
                ?>

                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $description; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo date("jS F", strtotime($start_date)); ?></td>
                            <td><?php echo date("jS F", strtotime($last_date)); ?></td>
                            <td><?php echo $file_name; ?></td>
                            <td>
                                <?php
                                $file_path = "../tasks/" . $rows['filename'];
                                if (isset($file_path) && !empty($file_name) && !empty($file_path) ) {
                                    echo "<a href=\"$file_path\" class=\"btn btn-primary\" download><i class=\"fa fa-download\"></i></a>";
                                } else {
                                    echo "<a href=\"#\" class=\"btn btn-danger disabled\"><i class=\"fa fa-download\"></i></a>";
                                }
                                ?>
                            </td>


                            <td>
                                <?php echo "<a href='accept-task.php?id={$id}' class='btn btn-sm btn-outline-success mr-2'> Submit </a> " ?>
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
            $total_query = "SELECT COUNT(*) AS total FROM task WHERE status = 'progress' AND emp_email='$email'";
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