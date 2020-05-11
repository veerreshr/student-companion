<?php
session_start();
if (!isset($_SESSION['name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ./login/login.php');
    return;
}
require '../db.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="details.css">

</head>

<body>
    <section>
        <div class="frontpage" id="pg1"></div>
        <div class="frontpage" id="pg2"></div>
    </section>
    <div class="content"></div>
    <div class="content">
        <h1>Student companion</h1>
        <h4>keep noted , keep focused</h4>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum odit labore at recusandae sint reprehenderit nihil consectetur, ab est debitis reiciendis tempore repudiandae voluptatibus velit sequi officiis corrupti. Tenetur fugit quo omnis odit
            ex doloremque aut necessitatibus ipsa, earum quod? Corrupti labore culpa optio repellat dolores sequi reprehenderit omnis id, praesentium explicabo delectus maxime veniam itaque officia tempore, excepturi aperiam commodi beatae saepe autem
            nulla natus nihil corporis dolorum. Similique repellendus ea ipsam. Officia repellendus sapiente consequuntur quo ducimus incidunt nisi ea ipsum ipsam fuga placeat animi harum molestiae soluta, distinctio omnis facere, aperiam molestias quas
            enim vel. Incidunt tempore delectus placeat quae quisquam at quos ipsa nemo cumque, sint itaque, veritatis eum iusto ea consectetur enim libero. Mollitia non voluptate totam distinctio quo asperiores fugit quibusdam in doloribus ad sequi id
            pariatur vitae exercitationem harum iusto iste, illum consectetur autem error ratione? Molestias eum reiciendis voluptas accusamus, quas, corrupti ea vitae laboriosam debitis deserunt mollitia, laudantium voluptates iste praesentium! Ratione
            tenetur reprehenderit sint, voluptas maxime, quos similique, blanditiis laudantium necessitatibus dolor iste voluptate veniam! Veritatis similique totam unde placeat expedita dignissimos earum error veniam quo. Iste, molestias, perferendis
            exercitationem, amet illum voluptatum numquam hic eveniet perspiciatis doloremque mollitia distinctio.</p>
    </div>
    <div class="classes">
        <?php
        $query = "Select * from week where id='" . $_SESSION['id'] . "'";
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
        <button id="add" onclick="displaymodal()"><i class="fa fa-plus" aria-hidden="true"></i></button>
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
         var pg1 = document.getElementById('pg1');
        var pg2 = document.getElementById('pg2');
        window.addEventListener("scroll", () => {
            pg1.style.left = -(window.pageYOffset) + "px";
            pg2.style.left = (window.pageYOffset) + "px";
        });
        //end for entry page
        modal = document.getElementById("modal");
        function displaymodal() {
            modal.style.display = "block";

        }
        document.getElementById("close").addEventListener("click", () => modal.style.display = "none");
    </script>
</body>

</html>