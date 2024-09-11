<?php
include "header.php";
?>


<?php

//  database connection
require_once "../connection.php";

// Pagination variables
$limit = 4; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM admin LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

$i = 1;
$you = "";


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
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>

<div class="container bg-white shadow">
    <div class="py-4 mt-3">
        <div class='text-center pb-2'>
            <h4>Manage Admin</h4>
        </div>
        <div class="table-responsive">
            <table style="width:100%" class="table-hover text-center ">
                <tr class="bg-dark">
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>

                    <th>Action</th>
                </tr>
                <?php

                if (mysqli_num_rows($result) > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $name = $rows["name"];
                        $email = $rows["email"];
                        $dob = $rows["dob"];
                        $gender = $rows["gender"];
                        $id = $rows["id"];
                        if ($gender == "") {
                            $gender = "Not Defined";
                        }

                        if ($dob == "") {
                            $dob = "Not Defined";
                            $age = "Not Defined";
                        } else {
                            $dob = date('jS F, Y', strtotime($dob));
                            // $date1 = date_create($dob);
                            // $date2 = date_create('now');
                            // $diff = date_diff($date1, $date2);
                            // $age = $diff->format('%y years');
                            $age = $dob;
                        }

                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td> <?php echo $name; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $gender; ?></td>
                            <td><?php echo $age; ?></td>

                            <td>

                                <?php

                                if ($email !== $_SESSION["email"]) {
                                    $edit_icon = "<a href='edit-admin.php?id= {$id}' class='btn-sm btn-primary float-right ml-3 '> <span ><i class='fa fa-edit '></i></span> </a>";
                                    $delete_icon = " <a href='delete-admin.php?id={$id}' id='bin' class='btn-sm btn-danger float-right'> <span ><i class='fa fa-trash '></i></span> </a>";
                                    echo $edit_icon . $delete_icon;
                                } else {
                                    echo "<a href='profile.php' class='btn btn-success float-right'>Profile</a>";
                                } ?>
                            </td>


                <?php
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No admin found</td></tr>";
                }
                ?>

                        </tr>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            $sql = "SELECT COUNT(id) AS total FROM admin";
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
