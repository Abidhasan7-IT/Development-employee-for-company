<?php 
include './header.php';

$name="";
$nameErr= "";	

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_REQUEST["d_name"])) {
        $nameErr = "<p style='color:red'> * Department name is required</p>";
    } else {
        $name = $_REQUEST["d_name"];
    }

    if (!empty($name)) {

        // Database connection
        include "../connection.php";

        $sql = "INSERT INTO `department`(`d_name`) VALUES ( '$name')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $name ="";
            echo "<script>
                 alert('Successfully Added');
              </script>
              ";
        } else {
            echo "<script>alert('Failed to Added record');</script>";
        }
    }
}
// Database connection
require_once "../connection.php";

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM department LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);


?>



<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="form-input-content">
                <div class="card login-form mb-0">
                    <div class="card-body pt-3 shadow">
                        <h4 class="text-center">Add New Department</h4>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                            <div class="form-group mb-2">
                                <label>Department Name :</label>
                                <input class="form-control" value="<?php echo $name; ?>" name="d_name" type="text" required>
                                <?php echo $nameErr; ?>
                            </div>

                            <button type="submit" class="btn btn-success btn-block w-25 mt-3">Add</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Department Table -->
    <div class="container bg-white shadow mt-2">
        <div class="py-4 mt-5">
            <div class='text-center pb-2'>
                <h4>All Department</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover ">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th scope="col">S.No.</th>
                            <th scope="col">Department Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if (mysqli_num_rows($result) > 0) {
                            $i = $start + 1; // Initialize $i with the correct start value
                            while ($rows = mysqli_fetch_assoc($result)) {
                                $d_name = $rows["d_name"];
                                $id = $rows["id"];
                        ?>
                                <tr>
                                    <th class="text-center" scope="row"><?php echo $i; ?></th>
                                    <td class="text-center" ><?php echo $d_name; ?></td>
                                    <td class="text-center">
                                        <?php
                                        if (!empty($d_name)) {
                                            $edit_icon = "<a href='edit-department.php?id={$id}' class='btn btn-primary btn-sm'>Edit</a>";
                                            $delete_icon = "<a href='delete-department.php?id={$id}' class='btn btn-danger btn-sm'>Delete</a>";
                                            echo $edit_icon . ' ' . $delete_icon;
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='5'>No department found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="text-center mt-3">
                <?php
                $sql = "SELECT COUNT(id) AS total FROM department";
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
</div>

















<?php 
include './footer.php';
?>