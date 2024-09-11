<?php 
    include "header.php";
 
    $id = $_GET["id"];
    require_once "../connection.php";

    $sql = "SELECT * FROM employee WHERE id = $id ";
    $result = mysqli_query($conn , $sql);

    if(mysqli_num_rows($result) > 0 ){
        
        while($rows = mysqli_fetch_assoc($result) ){
            $name = $rows["name"];
            $email = $rows["email"];
            $dob = $rows["dob"];
            $gender = $rows["gender"];
            $Position = $rows["Position"];
            $salary = $rows["salary"];
        }
    }

    $nameErr = $emailErr = $passErr = $salaryErr = "";
    $pass = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Retrieve form data
        $name = $_POST["name"];
        $email = $_POST["email"];
        $dob = $_POST["dob"];
        $gender = $_POST["gender"];
        $Position = $_POST["Position"];
        $salary = $_POST["salary"];
        $pass = $_POST["pass"];

        // If salary is not empty and has been changed, update the database
        if(!empty($salary)) {
            if($salary != $rows['salary']) {
                $sql = "UPDATE employee SET name = '$name', email = '$email', password ='$pass' , dob='$dob', gender='$gender' , Position= '$Position', salary='$salary' WHERE id = $id";
                $result = mysqli_query($conn , $sql);
                if($result){
                    echo "<script>
                        alert('Successfully Updated!');
                        window.location.href = 'manage-employee.php';
                    </script>";
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "<script>alert('Salary remains the same. No update required.')</script>";
            }
        }
    }
?>

<div class="login-form-bg h-100">
    <div class="container  h-100">
        <div class="row justify-content-center h-100">
            <div class="col-xl-6">
                <div class="form-input-content">
                    <div class="card login-form mb-0">
                        <div class="card-body pt-4 shadow">                       
                            <h4 class="text-center">Edit Employee profile</h4>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=$id"); ?>">
                                <div class="form-group">
                                    <label >Full Name:</label>
                                    <input type="text" class="form-control" value="<?php echo $name; ?>"  name="name">
                                </div>
                                <div class="form-group">
                                    <label >Email:</label>
                                    <input type="email" class="form-control" value="<?php echo $email; ?>"  name="email">
                                    <?php echo $emailErr; ?>
                                </div>
                                <div class="form-group">
                                    <label >Password:</label>
                                    <input type="password" class="form-control" value="<?php echo $pass; ?>" name="pass">
                                </div>
                                <div class="form-group">
                                    <label >Salary:</label>
                                    <input type="number" class="form-control" value="<?php echo $salary; ?>" name="salary">
                                    <?php echo $salaryErr; ?>            
                                </div>
                                <div class="form-group">
                                    <label >Position:</label>
                                    <input type="text" class="form-control" value="<?php echo $Position; ?>" name="Position">
                                </div>
                                <div class="form-group">
                                    <label >Date-of-Birth:</label>
                                    <input type="date" class="form-control" value="<?php echo $dob; ?>" name="dob">
                                </div>
                                <div class="form-group form-check form-check-inline mb-2">
                                    <label class="form-check-label">Gender:</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" <?php if($gender == "Male"){ echo "checked"; } ?>  value="Male"  selected>
                                    <label class="form-check-label">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" <?php if($gender == "Female"){ echo "checked"; } ?>  value="Female">
                                    <label class="form-check-label">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" <?php if($gender == "Other"){ echo "checked"; } ?>  value="Other">
                                    <label class="form-check-label">Other</label>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary btn-block">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include "footer.php";
?>
