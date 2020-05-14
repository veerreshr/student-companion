<?php
session_start();
require "../db.php";
$errors = array();
$checkmode = 0;
if (isset($_POST['submit'])) {
    $checkmode = 1;
    $pp = mysqli_real_escape_string($db, $_POST['previous_password']);
    $np = mysqli_real_escape_string($db, $_POST['new_password']);
    if (empty($np)) {
        array_push($errors, "Password is required");
    }
    $pp=md5($pp);
    $np=md5($np);
    $email=$_SESSION['email'];
    $query = "SELECT * FROM register WHERE email='$email' AND password='$pp'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
        $query="update register set password=$np where email='$email' and id='".$_SESSION['id']."'";
    }else{
        array_push($errors, "Incorrect old password entered");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

</head>

<body>
    <?php if ($checkmode == 0) { ?>
        <div class="container col-sm-4 col-md-7 col-lg-4 mt-5">
            <h3 class="card-header">Change password</h3><br>
            <form action="./password.php" method="post">
                <div class="form-group">
                    <label for="previous_password">Enter the previous password</label>
                    <input type="password" name="previous_password" id="previous_password" class="form-control" placeholder="Enter old password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New password :</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password" required>
                    <label for="confirm_password">Confirm password :</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required onchange="conPassCheck()">
                    <span id="passMessage"></span><br>
                </div>
                <input type="submit" value="Confirm changes" name="submit" id="submit" class="btn btn-primary">
            </form>
        </div>
    <?php }  ?>
    <?php if ($checkmode == 1) { ?>
        <div class="jumbotron">
  <h1 class="display-4"><?php if(count($errors)!=0){echo "Something went wrong!";}else{ echo "Password changed!";} ?></h1>
  <p class="lead"></p>
  <hr class="my-4">
  <p><?php if(count($errors)!=0){ 
      
      for($i=0;$i<count($errors);$i++){
          echo $errors[$i]."<br>";
          $i++;
      }
  }else{
      echo "click the below to redirect to home page";
  } ?></p>
  <p class="lead">
  <?php if(count($errors)!=0){ ?>
    <a class="btn btn-primary btn-lg" href="./password.php" role="button">Retry</a>
  <?php }else{ ?>
    <a class="btn btn-primary btn-lg" href="../index.php" role="button">Home</a>
    
  <?php } ?>
  </p>
</div>
        <?php }  ?>

    <script>
        function conPassCheck() {
            myInput = document.getElementById('new_password');
            conPass = document.getElementById('confirm_password');
            if (myInput.value === conPass.value) {
                document.getElementById("passMessage").style.color = "green";
                document.getElementById("passMessage").innerHTML = "Password matched<br>";
                document.getElementById("submit").disabled = false;

            } else {
                document.getElementById("passMessage").style.color = "red";
                document.getElementById("passMessage").innerHTML = "Password not matched<br>";
                document.getElementById("submit").disabled = true;

            }
        }
    </script>
</body>

</html>