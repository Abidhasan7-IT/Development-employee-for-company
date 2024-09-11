<?php
include './header.php';
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
                                    <td class="text-center"><?php echo $d_name; ?></td>
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