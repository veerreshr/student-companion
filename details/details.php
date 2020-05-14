<?php
session_start();
if (!isset($_SESSION['name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ./login/login.php');
    return;
}
require '../db.php';
if(isset($_GET['editattendance'])){
    $_SESSION['editedafter']=date("Y-m-d H:i:s",time());
    header('location: ./details.php');
}
$_SESSION['weekid'] = 0;
$query = "select MAX(weekid) AS max from week where id='" . $_SESSION['id'] . "'";
$result = mysqli_query($db, $query)  or die(mysqli_error($db));
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $weekid = $row["max"];
    $_SESSION['weekid'] = $weekid;
}
if (isset($_POST['attendence'])) {
    $goal = $_POST['goal'];
    $query = "update register set goal='" . $goal . "' where id='" . $_SESSION['id'] . "'";
    if (!mysqli_query($db, $query)) {
        echo ("Error description: " . mysqli_error($db));
        return;
    }
    header('location: /student%20companion/index.php');
}


if (isset($_POST['submit'])) {
    $_SESSION['weekid'] += 1;
    $subject = strtoupper($_POST['subject']);
    $day = $_POST['day'];
    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    $query = "insert into week(id,weekid,subject,stime,etime,day) values(" . $_SESSION['id'] . "," . $_SESSION['weekid'] . ",'$subject','$starttime','$endtime','$day')";
    if (!mysqli_query($db, $query)) {
        echo ("Error description: " . mysqli_error($db));
        return;
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" type="text/css" href="details.css">

</head>

<body>
    
    <div class="classes">
        <?php
        $query = "Select * from week where id='" . $_SESSION['id'] . "'";
        if($_SESSION['editedafter']!=null){
            $query=$query."and lastupdate >= '".$_SESSION['editedafter']."'";
        }
        $result = mysqli_query($db, $query)  or die(mysqli_error($db));
        while ($user = mysqli_fetch_assoc($result)) {
        ?>
            <div class="row">
                <div class="column">
                    <div class="card">
                        <h3><?php echo $user['subject']; ?></h3>
                        <h4>Day : <?php echo $user['day']; ?></h4>
                        <p>Start time : <?php echo $user['stime']; ?></p>
                        <p>End time : <?php echo $user['etime']; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <button id="add" onclick="displaymodal()"><img src="../assets/addicon2.png" alt=""></button>
    </div>
    <?php $query = "select * from week where id='" . $_SESSION['id'] . "'";
    $result = mysqli_query($db, $query);
    $rowcount = mysqli_num_rows($result);
    ?>
    <div class="enter">
        
        <div id="modal">
            <div id="modal-content">
                <form action="details.php" method="post">
                    <h3>Enter your subject details</h3><br>
                    <label for="subject">Subject</label><br>
                    <input type="text" name="subject" placeholder="Name of subject"><br>
                    <label for="day">Day</label> <select name="day" id="day">
                        <option value="Mon">Monday</option>
                        <option value="Tue">Tuesday</option>
                        <option value="Wed">Wednesday</option>
                        <option value="Thu">Thursday</option>
                        <option value="Fri">Friday</option>
                        <option value="Sat">Saturday</option>
                        <option value="Sun">Sunday</option>
                    </select><br>
                    <label for="st">Start time</label> <input type="time" name="starttime" id="st"><br>
                    <label for="en">Endtime</label> <input type="time" name="endtime" id="en"><br>
                    <input type="submit" name="submit" value="submit">
                </form>
            </div>
        </div>
    </div>
    <!-- attendence goal and submit butten to be added -->
    <div class="goal">
        <form action='details.php' method="post">
            <label for="goal">Attendence goal :</label>
            <input type="text" name="goal" placeholder="Attendence goal"> %
            <input type="submit" <?php if ($rowcount == 0) { echo "disabled";} ?> name="attendence" value="Done">
        </form>
    </div>
    <script>
       
        //end for entry page
        modal = document.getElementById("modal");
        function displaymodal() {
            modal.style.display = "block";
            document.getElementsByClassName("classes").style.filter="blur(8px)";
        }
        document.getElementById("close").addEventListener("click", () => modal.style.display = "none");
    </script>
</body>

</html>