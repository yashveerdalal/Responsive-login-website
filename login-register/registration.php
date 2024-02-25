
<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YD university</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div  class="container-fluid">
    <a class="navbar-brand" href="#">  <h1  style="letter-spacing: 2px;  " >YD  UNIVERSITY</h1></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse-navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="#">Home</a>
        <a class="nav-link" href="#">About Us</a>
        <a class="nav-link" href="#">Programs</a>
        <a class="nav-link" href="#">Admission</a>
        </div>
    </div>
  </div>
</nav>


<div class="c1" style="display: flex;" >
           <img  class="logo" src="logo.png" alt="">
           <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];

           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();

           if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{

            $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            }else{
                die("Something went wrong");
            }
           }
          }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Username:  " required  >
            </div>
            <div style="margin-top: 5%;" class="form-group">
                <input   type="email" class="form-control" name="email" placeholder="E-mail:" required>
            </div>
            <div style="margin-top: 5%;" class="form-group">
                <input   type="password" class="form-control" name="password" placeholder="Password:"required>
            </div>
            <div  style="margin-top: 5%;" class="form-group">
                <input   type="password" class="form-control" name="repeat_password" placeholder="Re-type Password:" required>
            </div>
            <div style="margin-top: 2%;  "class="form-btn">
               
                <input  class="btn btn-primary" type="submit" value="Register"  name="submit" >
            </div>
        </form>
        <div>
        <div class="alr" ><p>Already Registered ?<a href="login.php">Login Here</a></p></div>
      </div>
    </div>
      
  </div>
  <div>
      <h1 style="margin-top: 2%;" >
         About
      </h1>
      <p>
      A university with the infrastructure of global standards, internationally benchmarked curricula, rich diversity of students, innovative pedagogy for participative and experiential learning with focus on research, innovation and entrepreneurship.
      </p>
    </div>
  
    
   

    </form>
</div>
    

        
      
  </body>
</html>