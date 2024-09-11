<?php
include "header.php";
?>

<?php
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
$sql = "SELECT * FROM employee $search_condition order by e_id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

$i = 1;
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
    <div class="py-3 mt-2">
        <div class='text d-flex justify-content-around pb-2'>
            <h4 class="manage-employees">Manage Employees</h4>
            <!-- Filter by Department -->
            <form method="GET" action="" class="search-form">
                <div class="input-group mb-2">
                    <select class="form-control" name="department">
                        <option value="">All Dept.</option>
                        <?php
                        // Fetch distinct departments from database
                        $dept_query = "SELECT DISTINCT d_name FROM employee ";
                        $dept_result = mysqli_query($conn, $dept_query);
                        while ($dept_row = mysqli_fetch_assoc($dept_result)) {
                            $selected = ($department_filter == $dept_row['d_name']) ? 'selected' : '';
                            echo "<option value='" . $dept_row['d_name'] . "' $selected>" . $dept_row['d_name'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="text" class="form-control" placeholder="Search Employee" name="search" value="<?php echo $search; ?>">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class="table-responsive"> <!-- Make the table responsive -->
            <table style="width:100%" class="table-hover text-center ">
                <tr class="bg-dark text-light">
                    <th>E-Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Dept.</th>
                    <th>CV</th>
                    <th>Position</th>
                    <th>Salary in Rs</th>
                    <th>Action</th>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $name = $rows["name"];
                        $email = $rows["email"];
                        $file_name = $rows['filename'];
                        $depart = $rows["d_name"];
                        $e_id = $rows["e_id"];
                        $id = $rows["id"];
                        $Position = $rows["Position"];
                        $salary = $rows["salary"];
                        $gender = ""; // Initialize $gender
                        if ($gender == "") {
                            $gender = "Not Defined";
                        }

                        $file_name = isset($file_name) ? $file_name : "";

                        if (empty($Position)) {
                            $Position = "Not Available";
                        }
                        if ($salary == "") {
                            $salary = "Not Defined";
                        }
                ?>
                        <tr>
                            <td><?php echo $e_id; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $depart; ?></td>
                            <td> <?php
                                    $file_path = "../CV/" . $file_name;
                                    if (isset($file_path) && !empty($file_name) && !empty($file_path)) {
                                        echo "<a href=\"$file_path\" class=\"btn btn-primary\" download><i class=\"fa fa-download\"></i></a>";
                                    } else {
                                        echo "<a href=\"#\" class=\"btn btn-danger disabled\"><i class=\"fa fa-download\"></i></a>";
                                    }
                                    ?> </td>
                            <td><?php echo $Position; ?></td>
                            <td><?php echo $salary; ?></td>
                            <td>
                                <?php
                                $edit_icon = "<a href='edit-employee.php?id= {$id}' class='btn-sm btn-primary float-right ml-3'> <span ><i class='fa fa-edit '></i></span> </a>   ";
                                $delete_icon = " <a href='delete-employee.php?id={$id}' id='bin' class='btn-sm btn-danger float-right'> <span ><i class='fa fa-trash '></i></span> </a>";
                                echo $edit_icon . " " . $delete_icon;
                                ?>
                            </td>
                    <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No records found</td></tr>";
                }
                    ?>
                        </tr>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            $sql = "SELECT COUNT(id) AS total FROM employee $search_condition";
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