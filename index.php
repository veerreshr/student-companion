<?php
session_start();
require './db.php';
if (isset($_GET['present'])) {
    $weekid = $_GET['weekid'];
    $present = $_GET['present'];
    $date = $_GET['date'];
    $query = "select * from daily where weekid=$weekid and date='$date' and id=" . $_SESSION['id'] . "";
    $result = mysqli_query($db, $query);
    if (!$result) {
        echo ("Error description: " . mysqli_error($db));
        return;
    }
    if (mysqli_num_rows($result) != 0) {
        $user = mysqli_fetch_assoc($result);
        if ($user['present'] == 1 || $user['present'] == 0) {
            $query = "update daily set present=$present ,holiday=0 where id=" . $_SESSION['id'] . " and weekid=$weekid and date='$date' ";
            mysqli_query($db, $query) or die(mysqli_error($db));
        }
    } else {

        $query = "insert into daily (id,date,weekid,present,holiday) values(" . $_SESSION['id'] . ",'$date',$weekid,$present,0)";
        mysqli_query($db, $query) or die(mysqli_error($db));
    }
    header('location: ./index.php');
    return;
}
if (isset($_GET['holiday'])) {
    $weekid = $_GET['weekid'];
    $date = $_GET['date'];
    $holiday = $_GET['holiday'];
    $query = "select * from daily where weekid=$weekid and date='$date' and id=" . $_SESSION['id'] . "";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) != 0) {
        $user = mysqli_fetch_assoc($result);
        if ($user['holiday'] == 1 || $user['present'] == 1 || $user['present'] == 0) {
            $query = "update daily set holiday=$holiday ,present=NULL where id=" . $_SESSION['id'] . " and weekid=$weekid and date='$date' ";
            mysqli_query($db, $query) or die(mysqli_error($db));
        }
    } else {
        $query = "insert into daily (id,date,weekid,holiday) values(" . $_SESSION['id'] . ",'$date',$weekid,$holiday)";
        mysqli_query($db, $query) or die(mysqli_error($db));
    }
    header('location: ./index.php');
    return;
}
require 'subject.php';

$now = time(); //for indian timestamp 

if (!isset($_SESSION['name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ./login/login.php');
    return;
}
$query = "select * from week where id='" . $_SESSION['id'] . "'";
$result = mysqli_query($db, $query);
$rowcount = mysqli_num_rows($result);
if ($rowcount == 0) {
    header('location: ./details/details.php');
}


if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();

    header("location: ./login/login.php");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="en">

<head>
    <script src="https://use.fontawesome.com/0eb2c0a554.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student companion </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="style1.css">


</head>

<body onload="start()" onresize="start()">
    <div class="header">
        <div class="navbar" id="navbar">
            <div class="menu" id="hamburger">
                <span class="burger "></span>
            </div>
            <div class="brand" id="brand">
                Student Companion<sub></sub>
            </div>
            <div class="menulinks">
                <div class="a"><a href="#">Home <i class="fa fa-home"></i></a></div>
                <div class="a"><a href="stats.php">Settings <i class="fa fa-cog" aria-hidden="true"></i></i></a></div>
                <!--<a href="#">Feedback<i class="fa fa-comment-o"></i></a>-->
                <div class="a"><a href="index.php?logout='1'">Logout <i class="fa fa-sign-out"></i></a></div>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="tab">
            <button class="tablinks" onclick="shuffle(event, 'timetable')" id='defaultOpen'>Timetable</button>
            <button class="tablinks" onclick="shuffle(event, 'todo')">Take note</button>
            <button class="tablinks" id="remarkslink" onclick="shuffle(event, 'remarks')">Remark</button>
        </div>
        <!-----------------------------------------------------------------TIMETABLE---------------------------------------------------------------------->
        <div id="timetable" class="tabcontent">

            <div class="row1">

                <?php
                $query = "select max(lastupdate)as last from week where id='" . $_SESSION['id'] . "'";
                $result = mysqli_query($db, $query);
                $latest = mysqli_fetch_assoc($result);
                $from = date("Y-m-d H:i:s", (strtotime($latest['last']) - (15 * 60)));
                $to = date("Y-m-d H:i:s", (strtotime($latest['last']) + (15 * 60)));
                $query = "select min(stime) as mini from week where id='" . $_SESSION['id'] . "' and lastupdate between '$from' and '$to'";
                $result = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($result);
                $startsAt = strtotime($user['mini']);
                $query = "select max(etime)as maxi from week where id='" . $_SESSION['id'] . "' and lastupdate between '$from' and '$to'";
                $result = mysqli_query($db, $query);
                $user = mysqli_fetch_assoc($result);
                $endsAt = strtotime($user['maxi']);
                $twh = ceil(($endsAt - $startsAt) / 3600); // approximated total working hours
                $starttime = $startsAt;
                $wte = 100 / $twh; //width of time element 
                ?>
                <div class="time" id="startendtime" start="<?php echo gmdate("H:i", $starttime);  ?>" end="<?php echo gmdate("H:i", $endsAt); ?>">Time <br>-<br> Day</div>
                <?php
                for ($i = 0; $i < $twh; $i++) {
                ?>
                    <div class="time"><?php echo gmdate("h:i a", $starttime) . "<br>-<br>" . gmdate("h:i a", $starttime + 3600); ?></div>
                <?php
                    $starttime = $starttime + 3600;
                }
                ?>
            </div>
            <?php subject($now); ?>

        </div>
        <!-----------------------------------------------------------------TODO--------------------------------------------------------------------------->
        <div id="todo" class="tabcontent">
            <iframe src="todo.php" frameborder="0" style="position: relative; height:100%; width:100%;"></iframe>
        </div>
        <!-----------------------------------------------------------------REMARKS------------------------------------------------------------------------->
        <div id="remarks" class="tabcontent">
            <?php
            $barcolor = array("#50d07d", "#00539CFF", "#DC3D24", "#D6ED17FF", "#DAA03DFF", "#ef1e25");
            $backcolor = array("#b2cecf", "#EEA47FFF", "#232B2B", "#606060FF", "#616247FF", "#aedaa6");
            $subjects = array();
            $query = "select goal from register where id=" . $_SESSION['id'];
            $result = mysqli_query($db, $query) or die(mysqli_error($db));
            $row = mysqli_fetch_assoc($result);
            $goal = $row['goal'];
            $query = "select subject from week  where id=1 group by subject";
            $result = mysqli_query($db, $query) or die(mysqli_error($db));
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($subjects, $row['subject']);
            }
            for ($i = 0; $i < count($subjects); $i++) {
                $colorindex = $i % 6;
                $lastsevendays = array();
                $query = "select count(*)as pre from daily inner join week on daily.weekid=week.weekid and daily.id=week.id where present=1 and subject='" . $subjects[$i] . "' and holiday <> 1 and daily.id=" . $_SESSION['id'];
                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                $row = mysqli_fetch_assoc($result);
                $attended = $row['pre'];
                $query = "select count(*)as abs from daily inner join week on daily.weekid=week.weekid and daily.id=week.id where present=0  and subject='" . $subjects[$i] . "' and holiday <> 1 and daily.id=" . $_SESSION['id'];
                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                $row = mysqli_fetch_assoc($result);
                $absent = $row['abs'];
                $total = $attended + $absent;   //total number of classes
                $percentage = ($attended / $total) * 100; //current att percentage
                $acceptableabsents = ((100 - $goal) * $total) / 100; //gives the no of absents that can be accepted for getting attendence goal
                $diff = $acceptableabsents - $absent; //if positive , u can absent for that many classes, if negative u need to attend those many, if zero, its perfect
                if ($diff > 0) {
                    if ($diff <= 0) {
                        $statement = "Thats awesome , you may leave next $diff classes";
                    } elseif ($diff <= 5) {
                        $statement = "Your going amazing, You may leave ur next $diff classes";
                    } else {
                        $statement = "Thats fantastic , you may leave ur next $diff classes";
                    }
                } elseif ($diff < 0) {
                    if ($diff >= -2) {
                        $statement = "almost there, still " . abs($diff) . " more classes";
                    } elseif ($diff >= -4) {
                        $statement = "U can cope up," . abs($diff) . " more classes to attend";
                    } elseif ($diff >= -8) {
                        $statement = "oh no , u need to attend next " . abs($diff) . " classes";
                    } else {
                        $statement = "Ur in a wrong way , " . abs($diff) . " more classes to attened";
                    }
                } else {
                    $statement = "Perfect, Your in the track ";
                }

            ?>
                <div class="outerbox" style="background-color: <?php echo $backcolor[$colorindex]; ?>">
                    <div class="card">
                        <div class="graph">
                            <div class="chart" data-percent="<?php echo $percentage; ?>" data-bar-color="<?php echo $barcolor[$colorindex];   ?>" data-scale-color="#ffb400">
                                <p><?php echo $percentage; ?></p>
                            </div>
                        </div>
                        <div class="description">
                            <h2><?php echo $subjects[$i]; ?></h2>
                            <h4></h4>
                            <h1><?php echo $attended . "/" . $total; ?></h1>
                            <h4></h4>
                            <p><?php echo $statement;  ?></p>
                            <div style="border-color:<?php echo $backcolor[$colorindex]; ?>;color:<?php echo $backcolor[$colorindex]; ?>"><button>calendar</button></div>


                        </div>
                    </div>
                </div>


            <?php
            }


            ?>
        </div>
    </div>
    <div class="footer"></div>
    <!-----------------------------------------------------------------JAVASCRIPT--------------------------------------------------------------------------->
    <!-----------------------------------------------------------------circular pointer effect--------------------------------------------------------------------------->

    <!-----------------------------------------------------------------REMARKS------------------------------------------------------------------------>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="jquery.easypiechart.js"></script>
    <script>
        $("#remarkslink").click(function() {
            $('.chart').easyPieChart({
                scaleLength: 8,
                size: 200,
                lineWidth: 6,
            });
        });
    </script>

    <script>
        //------------------------------------------------------------TIMETABLE---------------------------------------------------------------------------
        // for automating the sizes of subjects
        var oneunit;

        function autosize() {
            var y = document.getElementsByClassName('time');
            oneunit = y[0].offsetWidth;

            var x = document.getElementsByClassName('firstcol');
            var i;
            for (i = 0; i < 7; i++) {
                x[i].style.width = oneunit + 'px';
            }
            var z = document.getElementsByClassName('subject');
            for (i = 0; i < z.length; i++) {
                var end = getseconds(z[i].getAttribute('data-etime'));
                var start = getseconds(z[i].getAttribute('data-stime'));
                console.log(((end - start) / 3600) * oneunit);
                var size = ((end - start) / 3600) * oneunit;
                z[i].style.width = size + "px";

            }
        }
        // end of automating sizes
        //adding free classes to everyday
        function addfree(day) {
            var startsAt = document.getElementById('startendtime').getAttribute("start");
            var endsAt = document.getElementById('startendtime').getAttribute("end");

            var x = document.getElementById(day).childElementCount; // length of child elements of particular day

            var previoustime = getseconds(startsAt);

            var endtime = getseconds(endsAt);
            console.log(day);
            var list = document.getElementById(day);
            for (let i = 1; i < x; i++) {


                var childstime = getseconds(list.children[i].getAttribute('data-stime'));
                var childetime = getseconds(list.children[i].getAttribute('data-etime'));
                if (childstime != previoustime) {
                    var timegap = (childstime - previoustime) / 3600; //in hours
                    var childwidth = oneunit * timegap;
                    var newItem = document.createElement("div");
                    newItem.className = "free";
                    newItem.style.width = childwidth + "px";
                    var textnode = document.createTextNode(" ");
                    newItem.appendChild(textnode);
                    list.insertBefore(newItem, list.children[i]);
                    i++;
                    x++;
                }
                previoustime = childetime;

            }
            if (previoustime != endtime) {
                var timegap = (endtime - previoustime) / 3600; //in hours
                var childwidth = oneunit * timegap;
                var newItem = document.createElement("div");
                newItem.className = "free";
                newItem.style.width = childwidth + "px";
                var textnode = document.createTextNode(" ");
                newItem.appendChild(textnode);
                document.getElementById(day).appendChild(newItem);
            }

        }

        //end of everyday
        function getseconds(time) {
            var a = time.split(':'); // split it at the colons
            var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60;
            return seconds;
        }

        function start() {
            autosize();
            addfree("Mon");
            addfree("Tue");
            addfree("Wed");
            addfree("Thu");
            addfree("Fri");
            addfree("Sat");
            addfree("Sun");
        }

        //end of free classes

        //for nav tags
        var ele = document.getElementById("hamburger");
        var burgerclick = false;
        ele.addEventListener("click", function() {
            ele.classList.toggle("burgeron");
            document.getElementById("navbar").classList.toggle("navtoggle");
            document.getElementById("brand").classList.toggle("brandon");
        });
        //end for nav tags
        //for tabs
        function shuffle(evt, sectionname) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(sectionname).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
        //end for tags
    </script>
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.14.3/firebase-app.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/7.14.3/firebase-analytics.js"></script>

    <script>
        // Your web app's Firebase configuration
        var firebaseConfig = {
            apiKey: "AIzaSyA8AwDcUHy6ElmLCbofMHk2Klkxj8wnFtc",
            authDomain: "student-companion-90b21.firebaseapp.com",
            databaseURL: "https://student-companion-90b21.firebaseio.com",
            projectId: "student-companion-90b21",
            storageBucket: "student-companion-90b21.appspot.com",
            messagingSenderId: "324654948039",
            appId: "1:324654948039:web:41c74bf788d37ce1f028e2",
            measurementId: "G-HEEF847MJ1"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

    const messaging=firebase.messaging();
    messaging().requestPermission()
    .then(function(){
        console.log("p");
        return messaging.getToken();
    })
    .then(function(token){
        console.log(token);
    })
    .catch(function(err){
        console.log(err);
    });

    </script>

</body>

</html>