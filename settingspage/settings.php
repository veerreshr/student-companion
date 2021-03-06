<?php 
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();

    header("location: ../login/login.php");
}
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();

    header("location: ../login/login.php");
}
require "../db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="settings.css">
</head>
<body>
    <?php $name=$_SESSION['name'];
    $email=$_SESSION['email'];
    $avatar=$name[0];
    ?>
    <div class="profile">
       <div class="avatar"><?php echo $avatar; ?></div>
       <h1 class="name"><?php echo $name; ?></h1>
       <h3 class="email"><?php echo $email; ?></h3>
    </div>
    <div class="options">
        <button onclick="window.location.href='../calendar.php'">
            view and edit attendance for a particular day
        </button>
        
        <button onclick="window.location.href='../details/details.php?editattendance=1'">
            Edit timetable
        </button>
        <button onclick="window.location.href='../changepassword/password.php'">
            change password
        </button>
        <button>
            privacy policy
        </button>
        <button onclick="window.location.href='./settings.php?logout=1'">
           logout
        </button>
    </div>
    
</body>
</html>