<?php
session_start();
require 'db.php';
$date = date("Y-m-d");

if (!isset($_SESSION['name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ./login/login.php');
    return;
}
$query="select * from week where id='".$_SESSION['id']."'";
$result = mysqli_query($db, $query);
$rowcount=mysqli_num_rows($result);
if($rowcount==0){
    header('location: ./details/details.php');
}


if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
   
    header("location: ./login/login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student companion </title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>

<body>
    <div class="header">
        <div class="navbar" id="navbar">
            <div class="menu" id="hamburger">
                <span class="burger "></span>
            </div>
            <div class="brand" id="brand">
                Student Companion<sub></sub>
            </div>
            <div class="menulinks">
                <a href="#">Home<i class="fa fa-home"></i></a>
                <a href="stats.php">Settings<i class="fa fa-bar-chart"></i></a>
                <!--<a href="#">Feedback<i class="fa fa-comment-o"></i></a>-->
                <a href="index.php?logout='1'">Logout<i class="fa fa-sign-out"></i></a>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="tab">
            <button class="tablinks" onclick="shuffle(event, 'timetable')" id="defaultOpen">Timetable</button>
            <button class="tablinks" onclick="shuffle(event, 'todo')">Take note</button>
            <button class="tablinks" onclick="shuffle(event, 'remarks')">Remark</button>
        </div>
        <div id="timetable" class="tabcontent">

            <table>
                <tr>
                    <th></th>
                    <?php
                    $query = "select min(stime) as mini from week where id='" . $_SESSION['id'] . "'";
                    $result = mysqli_query($db, $query);
                    $user = mysqli_fetch_assoc($result);
                    $startsAt = strtotime($user['mini']);
                    $query = "select max(etime)as maxi from week where id='" . $_SESSION['id'] . "'";
                    $result = mysqli_query($db, $query);
                    $user = mysqli_fetch_assoc($result);
                    $endsAt = strtotime($user['maxi']);
                    $twh = ceil(($endsAt - $startsAt) / 3600); // approximated total working hours
                    $starttime = $startsAt;
                    $wte=100/$twh;//width of time element 
                    for ($i = 0; $i < $twh; $i++) {
                    ?>
                        <th class="time" style><?php echo gmdate("H:i:s", $starttime) . "-" . gmdate("H:i:s", $starttime + 3600); ?></th>
                    <?php
                        $starttime = $starttime + 3600;
                    }
                    ?>
                </tr>
                <tr>
                    <th>Mon</th>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>

                </tr>
                <tr>
                    <th>Tue</th>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                </tr>
                <tr>
                    <th>Wed</th>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                </tr>
                <tr>
                    <th>Thu</th>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                </tr>
                <tr>
                    <th>fri</th>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                </tr>
                <tr>
                    <th>sat</th>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                </tr>
                <tr>
                    <th>sun</th>
                    <td class="free">12</td>
                    <td class="free">12</td>
                    <td class="free">12</td>
                    <td class="free">12</td>
                    <td class="free">12</td>
                    <td class="free">12</td>
                    <td class="free">12</td>
                </tr>
            </table>

        </div>
        <div id="todo" class="tabcontent">
            <div id="myDIV" class="todoheader">
                <h2 style="margin:5px">My To Do List</h2>
                <input type="text" id="myInput" placeholder="Title...">
                <input type="date" name="" id="">
                <input type="time" name="" id="">
                <input type="week" name="" id="">

                <span onclick="newElement()" class="addBtn">Add</span>

            </div>

            <ul id="myUL">
                <li>Hit the gym</li>
                <li class="checked">Pay bills</li>
                <li>Meet George</li>
                <li>Buy eggs</li>
                <li>Read a book</li>
                <li>Organize office</li>
            </ul>

        </div>
        <div id="remarks" class="tabcontent">
            <center>student remarks in all subjects</center>
        </div>
    </div>
    <div class="footer"></div>
    <script>
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
        //for todo
        // Create a "close" button and append it to each list item
        var myNodelist = document.getElementsByTagName("LI");
        var i;
        for (i = 0; i < myNodelist.length; i++) {
            var span = document.createElement("SPAN");
            var txt = document.createTextNode("\u00D7");
            span.className = "close";
            span.appendChild(txt);
            myNodelist[i].appendChild(span);
        }

        // Click on a close button to hide the current list item
        var close = document.getElementsByClassName("close");
        var i;
        for (i = 0; i < close.length; i++) {
            close[i].onclick = function() {
                var div = this.parentElement;
                div.style.display = "none";
            }
        }

        // Add a "checked" symbol when clicking on a list item
        var list = document.querySelector('ul');
        list.addEventListener('click', function(ev) {
            if (ev.target.tagName === 'LI') {
                ev.target.classList.toggle('checked');
            }
        }, false);

        // Create a new list item when clicking on the "Add" button
        function newElement() {
            var li = document.createElement("li");
            var inputValue = document.getElementById("myInput").value;
            var t = document.createTextNode(inputValue);
            li.appendChild(t);
            if (inputValue === '') {
                alert("You must write something!");
            } else {
                document.getElementById("myUL").appendChild(li);
            }
            document.getElementById("myInput").value = "";

            var span = document.createElement("SPAN");
            var txt = document.createTextNode("\u00D7");
            span.className = "close";
            span.appendChild(txt);
            li.appendChild(span);

            for (i = 0; i < close.length; i++) {
                close[i].onclick = function() {
                    var div = this.parentElement;
                    div.style.display = "none";
                }
            }
        }

        //end for todo
    </script>
</body>

</html>