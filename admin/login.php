<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link href="../css/style.css" rel="stylesheet">

  <title>Admin Login</title>
</head>

<body>
<?php

  $email_err = $pass_err = $login_Err = "";
  $email = $pass = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_REQUEST["email"])) {
      $email_err = " <p style='color:red'> * Email Can Not Be Empty</p> ";
    } else {
      $email = $_REQUEST["email"];
    }

    if (empty($_REQUEST["password"])) {
      $pass_err =  " <p style='color:red'> * Password Can Not Be Empty</p> ";
    } else {
      $pass = $_REQUEST["password"];
    }

    if (!empty($email) && !empty($pass)) {

      // database connection
      require_once "../connection.php";

      $sql_query = "SELECT * FROM admin WHERE email='$email' && password = '$pass'  ";
      $result = mysqli_query($conn, $sql_query);

      if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
          session_start();
          session_unset();
          $_SESSION["email"] = $rows["email"];
          header("Location: index.php?login-sucess");
        }
      } else {
        $login_Err = "<div class='alert alert-warning alert-dismissible fade show'>
            <strong>Invalid Email/Password</strong>
            <button type='button' class='close' data-dismiss='alert' >
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
      }
    }
  }

  ?>
  


  <div class="bg ">
    <div class="login-form-bg">
      <div class="container">
        <div class="row justify-content-center align-item-center">
          <div class="col-xl-6">
            <div class="form-input-content mt-5">
              <div class="card login-form mb-0 bgconatiner">
                <div class="card-body pt-3 shadow">

                  <h3 class="text-center">Hello, Admin👨‍🎓</h3>
                  <div class="text-center my-3"> <?php echo $login_Err; ?> </div>
                  <form method="POST" action=" <?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">

                    <div class="form-group">
                      <label>Email :</label>
                      <input type="email" class="form-control input-rounded" value="<?php echo $email; ?>" name="email">
                      <?php echo $email_err; ?>
                    </div>

                    <div class="form-group">
                      <label>Password :</label>
                      <input type="password" class="form-control input-rounded" name="password">
                      <?php echo $pass_err; ?>
                    </div>

                    <div class="form-group">
                      <input type="submit" value="Log-In" class="btn btn-primary btn-lg w-100 " name="signin">
                    </div>
                    <p class=" login-form__footer">Not a Admin? <a href=".././employee/login.php" class="text-primary">Log-In </a>as Employee now</p>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


   <!-- style -->
   <style>
    body,
    html {
      height: 100%;
      margin: 0;
    }

    .bg {
      background-image: url("../image/firstbg.gif");
      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      background-attachment: fixed;
      color: black;
    }
    .bgconatiner{
      background: rgba(255, 255, 255, 0.655);
      font-weight: 600;
    }
  </style>


  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  <script src="./resorce/plugins/common/common.min.js"></script>
  <script src="./resorce/js/custom.min.js"></script>
  <script src="./resorce/js/settings.js"></script>
  <script src="./resorce/js/gleek.js"></script>
  <script src="./resorce/js/styleSwitcher.js"></script>
</body>

</html>
