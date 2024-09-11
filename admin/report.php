<?php
// Include header and database connection
include 'header.php';
require_once '../connection.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch employees from the database
$sql_emp = "SELECT * FROM `employee`";
$result_emp = mysqli_query($conn, $sql_emp);

if (!$result_emp) {
    die("Error fetching employees: " . mysqli_error($conn));
}

$employees = mysqli_fetch_all($result_emp, MYSQLI_ASSOC);

// Pagination variables
$records_per_page = 10;
$total_records = count($employees);
$total_pages = ceil($total_records / $records_per_page);

// Determine current page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate start and end indexes for current page
$start_index = ($current_page - 1) * $records_per_page;
$end_index = min($start_index + $records_per_page - 1, $total_records - 1);

// Initialize variables
$filter_month = isset($_GET['filter_month']) ? $_GET['filter_month'] : date('Y-m');

// For Total Leave    
$first_day_of_month = date('Y-m-01', strtotime($filter_month));
$last_day_of_month = date('Y-m-t', strtotime($filter_month));

// Initialize salary filter
$filter_salary = isset($_GET['department']) ? $_GET['department'] : '';

// Query to fetch employee leave records and calculate TotalLeave for each email for the current month
$TotalLeaveQuery = "SELECT email, COUNT(*) AS TotalLeave 
                    FROM emp_leave 
                    WHERE status = 'Accepted' 
                    AND start_date >= '$first_day_of_month' 
                    AND last_date <= '$last_day_of_month' 
                    GROUP BY email";

$TotalLeaveResult = mysqli_query($conn, $TotalLeaveQuery);

if (!$TotalLeaveResult) {
    die("Error fetching total leave: " . mysqli_error($conn));
}

$TotalLeave = array();
while ($row = mysqli_fetch_assoc($TotalLeaveResult)) {
    $TotalLeave[$row['email']] = $row['TotalLeave'];
}

// Total Task count
$TotaltaskQuery = "SELECT emp_email, COUNT(*) AS Totaltask 
                    FROM task 
                    WHERE status = 'Done'
                    AND start_date >= '$first_day_of_month' 
                    AND end_date <= '$last_day_of_month' 
                    GROUP BY emp_email";

$TotaltaskResult = mysqli_query($conn, $TotaltaskQuery);

if (!$TotaltaskResult) {
    die("Error fetching total task: " . mysqli_error($conn));
}

$Totaltask = array();
while ($row = mysqli_fetch_assoc($TotaltaskResult)) {
    $Totaltask[$row['emp_email']] = $row['Totaltask'];
}

// Presents count
$TotalpresentsQuery = "SELECT email, COUNT(DISTINCT e_id) AS attendance_count, DATE(date) AS attendance_date
                        FROM attendance 
                        WHERE status = 'in' 
                        AND date BETWEEN '$first_day_of_month' AND '$last_day_of_month'
                        GROUP BY email, attendance_date";

$TotalpresentsResult = mysqli_query($conn, $TotalpresentsQuery);

if (!$TotalpresentsResult) {
    die("Error fetching total presents: " . mysqli_error($conn));
}

$Totalpresents = array();
while ($row = mysqli_fetch_assoc($TotalpresentsResult)) {
    $email = $row['email'];
    $attendance_date = $row['attendance_date'];
    $Totalpresents[$email][$attendance_date] = $row['attendance_count'];
}

?>

<div class="container bg-white shadow">
    <div class="py-4 mt-1">
        <h4 class="text-center">Report (Monthly)</h4>
        <!-- Filter options -->
        <form method="GET">
            <div class="form-row justify-content-center align-items-center d-flex mb-3 gap-3">
                <div class="col-md-2">
                    <label for="filter_month" class="sr-only">Filter Month</label>
                    <input type="month" class="form-control" id="filter_month" name="filter_month" max="<?php echo date('Y-m'); ?>" value="<?php echo htmlspecialchars($filter_month); ?>">
                </div>

                <div class="col-md-2">
                    <input type="search" class="form-control" id="employeeSearch" placeholder="Search Employee">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
                <div class="col-md-2">
                    <select class="form-control w-50" name="department">
                        <option value="">Select</option>
                        <?php
                        $sql_salary = "SELECT DISTINCT salary FROM `employee` ORDER BY `salary` ASC";
                        $result_salary = mysqli_query($conn, $sql_salary);
                        // Check if there are any salaries
                        if (mysqli_num_rows($result_salary) > 0) {
                            while ($row = mysqli_fetch_assoc($result_salary)) {
                                $selected = ($row['salary'] == $filter_salary) ? 'selected' : '';
                                echo "<option value='" . $row['salary'] . "' $selected>" . $row['salary'] . "</option>";
                            }
                        } else {
                            // If no salaries found
                            echo "<option value=''>No salary Found</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-1">
                    <button class='btn btn-danger' onclick='printTable()'><i class='fa fa-print'></i></button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="reportTable" class="table table-hover text-center">
                <tr class="bg-dark text-light">
                    <th>SL</th>
                    <th>Name</th>
                    <th>Mail</th>
                    <th>Total Leave</th>
                    <th>Total Task</th>
                    <th>Total Presents</th>
                    <th>Salary</th>
                </tr>
                <?php
                $sl = 1;
                foreach ($employees as $emp) {
                    if ($sl > $end_index) {
                        break;
                    }
                    $email = $emp['email'];
                    $name = $emp['name'];
                    $salary = $emp['salary'];

                    // Check if the employee matches the filter month and salary
                    if (($filter_salary == '' || $filter_salary == $salary)) {
                        $leaveCount = isset($TotalLeave[$email]) ? $TotalLeave[$email] : 0;
                        $taskCount = isset($Totaltask[$email]) ? $Totaltask[$email] : 0;
                        $presentCount = array_sum(isset($Totalpresents[$email]) ? $Totalpresents[$email] : []);

                        echo "<tr>";
                        echo "<td>$sl</td>";
                        echo "<td>$name</td>";
                        echo "<td>$email</td>";
                        echo "<td>$leaveCount</td>";
                        echo "<td>$taskCount</td>";
                        echo "<td>$presentCount</td>";
                        echo "<td>$salary</td>";
                        echo "</tr>";

                        $sl++;
                    }
                }
                ?>
            </table>
        </div>

        <!-- Pagination -->
        <div class="text-center">
            <ul class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active_class = ($current_page == $i) ? 'active' : '';
                    echo "<li class='page-item $active_class'><a class='page-link' href='?page=$i'>$i</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<script>
    // Function to filter table rows based on search input
    document.getElementById("employeeSearch").addEventListener("keyup", function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("employeeSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("reportTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Column index where employee name is placed
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    });

    // Function to print the table
    function printTable() {
        var title = "<h4 class='text-center mb-4'>Report (Monthly)</h4>";
        var printContents = title + document.getElementById("reportTable").outerHTML; // Include the title
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<?php include 'footer.php'; ?>
