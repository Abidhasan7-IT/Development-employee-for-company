<?php
include "header.php";

// database connection
require_once "../connection.php";

$email = $_SESSION['email'];

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query to fetch tasks for the logged-in employee
$sql = "SELECT * FROM task WHERE status = 'pending' AND emp_email='$email' LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

?>

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


<div class="container bg-white shadow">
    <div class="py-4 mt-3">
        <h4 class="text-center pb-3">Task Notice</h4>
        <div class="table-responsive">
            <table style="width:100%" class="table-hover text-center ">
                <tr class="bg-dark text-light">
                    <th>S.No.</th>
                    <th>TaskName</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>TotalDays</th>
                    <th>E-Name</th>
                    <th>Status</th>
                    <th>Progress</th>
                </tr>

                <?php

                if (mysqli_num_rows($result) > 0) {
                    $i = $start + 1;
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $start_date = $rows["start_date"];
                        $last_date = $rows["end_date"];
                        $name = $rows["name"];
                        $description = $rows["description"];
                        $empname = $rows["emp_name"];
                        $status = $rows["status"];
                        $id = $rows["id"];
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $description; ?> </td>
                            <td><?php echo $start_date; ?></td>
                            <td><?php echo $last_date; ?></td>
                            <td>
                                <?php
                                $date1 = date_create($start_date);
                                $date2 = date_create($last_date);
                                $diff = date_diff($date1, $date2);
                                echo $diff->format("%a days");
                                ?>
                            </td>
                            <td><?php echo $empname; ?></td>
                            <td><?php echo $status; ?></td>

                            <td>
                                <?php echo "<a href='progress-task.php?id={$id}' class='btn btn-sm btn-outline-success mr-2'><i class='fa fa-check'></i> </a> ";
                                echo " <a href='canceltask.php?id={$id}' class='btn btn-sm btn-outline-danger'> <i class='fas fa-times'></i> </a>"; ?>
                            </td>
                        </tr>
                <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='9'> No Task Found..</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            $sql = "SELECT COUNT(id) AS total FROM task WHERE status = 'pending' AND emp_email='$email' ";
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


<?php
include "footer.php";
?>
