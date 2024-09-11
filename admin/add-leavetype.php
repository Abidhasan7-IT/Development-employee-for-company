<?php
include "header.php";

$descripeErr = $typeErr = "";
$type = $describe = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_REQUEST["leavetype"])) {
        $typeErr = "<p style='color:red'> * leavetype is required</p>";
    } else {
        $type = $_REQUEST["leavetype"];
    }

    if (empty($_REQUEST["description"])) {
        $descripeErr = "<p style='color:red'> * Description is required</p> ";
    } else {
        $describe = $_REQUEST["description"];
    }

    if (!empty($type) && !empty($describe)) {

        // Database connection
        include "../connection.php";

        $sql = "INSERT INTO tblleavetype( LeaveType , Description ) VALUES( '$type' , '$describe')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $type = $describe = "";
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
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM tblleavetype LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-xl-6">
            <div class="form-input-content">
                <div class="card login-form mb-0">
                    <div class="card-body pt-3 shadow">
                        <h4 class="text-center">Add New LeaveType</h4>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                            <div class="form-group mb-2">
                                <label>Leave Type :</label>
                                <input class="form-control" value="<?php echo $type; ?>" name="leavetype" type="text" required>
                                <?php echo $typeErr; ?>
                            </div>

                            <div class="form-group mb-2">
                                <label>Short Description :</label>
                                <input class="form-control" value="<?php echo $describe; ?>" name="description" type="text" autocomplete="off" required>
                                <?php echo $descripeErr; ?>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block w-25 mt-3">Add</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All LeaveType Table -->
    <div class="container bg-white shadow mt-2">
        <div class="py-4 mt-5">
            <div class='text-center pb-2'>
                <h4>All Leave Type</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover ">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">S.No.</th>
                            <th scope="col">Leave Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Creation Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if (mysqli_num_rows($result) > 0) {
                            $i = $start + 1; // Initialize $i with the correct start value
                            while ($rows = mysqli_fetch_assoc($result)) {
                                $LeaveType = $rows["LeaveType"];
                                $Description = $rows["Description"];
                                $CreationDate = $rows["CreationDate"];
                                $id = $rows["Id"];

                        ?>
                                <tr>
                                    <th class="text-center" scope="row"><?php echo $i; ?></th>
                                    <td><?php echo $LeaveType; ?></td>
                                    <td><?php echo $Description; ?></td>
                                    <td><?php echo $CreationDate; ?></td>
                                    <td>
                                        <?php
                                        if (!empty($LeaveType)) {
                                            $edit_icon = "<a href='edit-leavetype.php?id={$id}' class='btn btn-primary btn-sm'>Edit</a>";
                                            $delete_icon = "<a href='delete-leavetype.php?id={$id}' class='btn btn-danger btn-sm'>Delete</a>";
                                            echo $edit_icon . ' ' . $delete_icon;
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='5'>No LeaveType found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="text-center mt-3">
                <?php
                $sql = "SELECT COUNT(id) AS total FROM tblleavetype";
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

<?php include "footer.php"; ?>
