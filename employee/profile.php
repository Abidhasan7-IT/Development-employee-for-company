<?php
include "header.php";

// database connection
require_once "../connection.php";

$sql_command = "SELECT * FROM employee WHERE email = '$_SESSION[email]' ";
$result = mysqli_query($conn, $sql_command);

if (mysqli_num_rows($result) > 0) {
    while ($rows = mysqli_fetch_assoc($result)) {
        $name = ucwords($rows["name"]);
        $gender = ucwords($rows["gender"]);
        $dob = $rows["dob"];
        $Position = $rows["Position"];
        $d_name = $rows["d_name"];
        $salary = $rows["salary"];
        $dp = $rows["dp"];
        $id = $rows["e_id"];
        $file_name = $rows['filename'];
    }

    // Initialize variables to avoid null warnings
    $name = isset($name) ? $name : "";
    $gender = isset($gender) ? $gender : "";
    $dob = isset($dob) ? $dob : "";
    $Position = isset($Position) ? $Position : "";
    $d_name = isset($d_name) ? $d_name : "";
    $salary = isset($salary) ? $salary : "";
    $dp = isset($dp) ? $dp : "";
    $id = isset($id) ? $id : "";
    $file_name = isset($file_name) ? $file_name : "";

    if (empty($gender)) {
        $gender = "Not Defined";
    }
    if (empty($dob)) {
        $dob = "Not Defined";
    } else {
        $dob = date('jS F Y', strtotime($dob));
    }

    if (empty($Position)) {
        $Position = "Not Available";
    }

    if (empty($salary)) {
        $salary = "Not Defined";
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-6 col-sm-12 col-md-6">
            <div class="card shadow" style="width: 23rem;">
                <img src="upload/<?php echo !empty($dp) ? $dp : "../img/logo.png"; ?>" class="rounded-circle img-fluid  card-img-top m-auto" style="width:290px;  height: 300px; " alt="Profile">
                <div class="card-body">
                    <h2 class="text-center mb-4"><?php echo $name; ?></h2>
                    <p class="card-text">Email: <?php echo $_SESSION["email"] ?></p>
                    <p class="card-text">Employee Id: <?php echo $id ?></p>
                    <p class="card-text">Gender: <?php echo $gender ?></p>
                    <p class="card-text">Date of Birth: <?php echo $dob; ?></p>
                    <p class="card-text">Dept.: <?php echo $d_name; ?></p>
                    <p class="card-text">Position: <?php echo $Position; ?></p>
                    <p class="card-text">Salary: <?php echo $salary . " TK."; ?></p>
                    <p class="card-text">CV: <?php
                                                $file_path = "../CV/" . $file_name;
                                                if (isset($file_path) && !empty($file_name) && !empty($file_path)) {
                                                    echo "<a href=\"$file_path\" class=\"btn btn-primary\" download><i class=\"fa fa-download\"></i></a>";
                                                } else {
                                                    echo "<a href=\"#\" class=\"btn btn-danger disabled\"><i class=\"fa fa-download\"></i></a>";
                                                }
                                                ?>
                    </p>
                    <p class="text-center">
                        <a href="edit-profile.php" class="btn btn-outline-primary">Edit Profile</a>
                        <a href="change-password.php" class="btn btn-outline-primary">Change Password</a>
                        <a href="profile-photo.php" class="mt-2 btn btn-outline-primary">Change profile photo</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
