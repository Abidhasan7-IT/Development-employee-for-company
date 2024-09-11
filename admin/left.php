<?php
include "header.php";

// Database connection
require_once "../connection.php";

// Pagination variables
$limit = 8; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search query
$search = isset($_GET['search']) ? $_GET['search'] : '';
$department_filter = isset($_GET['department']) ? $_GET['department'] : '';
$search_condition = '';
if (!empty($search)) {
    $search_condition = " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}
if (!empty($department_filter)) {
    $department_condition = " AND d_name = '$department_filter'";
    $search_condition .= empty($search_condition) ? " WHERE d_name = '$department_filter'" : $department_condition;
}

// Fetch data from the database based on search query and pagination
$sql = "SELECT * FROM employee $search_condition ORDER BY e_id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

// Check if form is submitted for updating status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $e_id = $_POST['e_id'];
    $new_status = $_POST['status'];

    // Update status in the database
    $update_sql = "UPDATE employee SET status='$new_status' WHERE e_id='$e_id'";
    if (mysqli_query($conn, $update_sql)) {
    
        echo "<script>alert('Successfully Updated'); window.location.href='left.php?id=$id';</script>";
        
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>

<style>
    table,
    th,
    td {
        border: 1px solid red;
        padding: 15px;
    }

    table {
        border-spacing: 10px;
    }

    /* Responsive styles */
    @media (max-width: 576px) {
        .manage-employees {
            font-size: 1.5rem;
        }

        .search-form {
            width: 100%;
        }
    }
</style>

<div class="container bg-white shadow">
    <div class="py-3 mt-1">
        <div class='text d-flex  pb-2'>
            <h4 class="manage-employees"></h4>
            <form class="search-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                <input type="text" class="col-md-6" placeholder="Search Employee" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>

        <div class="table-responsive">
            <table style="width:100%" class="table-hover text-center">
                <tr class="bg-primary text-light">
                    <th>E-Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Dept.</th>
                    <th>Status</th>
                    <th colspan="2">Action</th>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $name = htmlspecialchars($rows["name"]);
                        $email = htmlspecialchars($rows["email"]);
                        $depart = htmlspecialchars($rows["d_name"]);
                        $e_id = $rows["e_id"];
                        $Status = $rows["status"];
                ?>
                        <tr>
                            <td><?php echo $e_id; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $depart; ?></td>
                            <td><?php echo $Status; ?></td>
                            <td colspan="2">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                    <input type="hidden" name="e_id" value="<?php echo $e_id; ?>">
                                    <select name="status">
                                        <option value="active" <?php if ($Status == 'active') echo 'selected'; ?> class="text-success">Active</option>
                                        <option value="deactive" <?php if ($Status == 'deactive') echo 'selected'; ?> class="text-danger">Deactive</option>
                                    </select>
                                    <button class="bg-success ms-2 text-light" type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            $sql = "SELECT COUNT(e_id) AS total FROM employee $search_condition";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];
            $total_pages = ceil($total_records / $limit);
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "&search=$search&department=$department_filter' class='btn btn-sm btn-outline-secondary mr-2'>Previous</a>";
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=" . $i . "&search=$search&department=$department_filter' class='btn btn-sm " . ($page == $i ? 'btn-secondary' : 'btn-outline-secondary') . " mr-2'>$i</a>";
            }
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "&search=$search&department=$department_filter' class='btn btn-sm btn-outline-secondary'>Next</a>";
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
