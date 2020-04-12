<?php
session_start();
require './db.php';
require 'subject.php';
if(isset($_GET['present'])){
    $weekid=$_GET['weekid'];
   $present=$_GET['present'];
    $date=$_GET['date'];
    $query="select * from daily where weekid=$weekid and date=$date and id=".$_SESSION['id']."";
    $result = mysqli_query($db, $query);
    $rowcount = mysqli_num_rows($result);
    if($rowcount==0){
        $query="insert into daily (id,date,weekid,present,holiday) values(".$_SESSION['id'].",'$date',$weekid,$present,0)";
        mysqli_query($db, $query) or die(mysqli_error($db));
    }else{
        $query="update daily set present=$present where id=".$_SESSION['id']." and weekid=$weekid and date='$date' ";
        mysqli_query($db, $query) or die(mysqli_error($db));
    }
}
if(isset($_GET['holiday'])){
    $weekid=$_GET['weekid']; 
    $date=$_GET['date'];
    $holiday=$_GET['holiday'];
    $query="select * from daily where weekid=$weekid and date=$date and id=".$_SESSION['id']."";
    $result = mysqli_query($db, $query);
    $rowcount = mysqli_num_rows($result);
    if($rowcount==0){
        $query="insert into daily (id,date,weekid,holiday) values(".$_SESSION['id'].",'$date',$weekid,$holiday)";
        mysqli_query($db, $query) or die(mysqli_error($db));
    }else{
        $query="update daily set holiday=$holiday ,present=NULL where id=".$_SESSION['id']." and weekid=$weekid and date='$date' ";
        mysqli_query($db, $query) or die(mysqli_error($db));
    }
}
$now = time() ; //for indian timestamp 

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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student companion </title>
    <link rel="stylesheet" type="text/css" href="style1.css">
    <script>
        

    </script>
</head>

<body onload="start()" onresize="start()" >
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

            <div class="row1">
                
                <?php
                 $query="select max(lastupdate)as last from week where id='".$_SESSION['id']."'";
                 $result = mysqli_query($db, $query);
                 $latest=mysqli_fetch_assoc($result);
                 $from=date("Y-m-d H:i:s",(strtotime($latest['last'])-(15*60)));
                 $to=date("Y-m-d H:i:s",(strtotime($latest['last'])+(15*60)));
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
                    <div class="time" ><?php echo gmdate("h:i a", $starttime) . "<br>-<br>" . gmdate("h:i a", $starttime + 3600); ?></div>
                <?php
                    $starttime = $starttime + 3600;
                }
                ?>
            </div>
                <?php subject($now); ?>

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
        // for automating the sizes of subjects
        var oneunit;
                function autosize(){
                        var y = document.getElementsByClassName('time');
                        oneunit=y[0].offsetWidth;
                       
                        var x = document.getElementsByClassName('firstcol');
                        var i;
                        for (i = 0; i < 7; i++){
                        x[i].style.width = oneunit+'px';
                        }
                var z= document.getElementsByClassName('subject');
                for (i = 0; i < z.length; i++){
                    var end=getseconds(z[i].getAttribute('data-etime'));
                    var start=getseconds(z[i].getAttribute('data-stime'));
                    console.log(((end-start)/3600)*oneunit);
                    var size=((end-start)/3600)*oneunit;
                    z[i].style.width=size+"px";

                }
                }

        // end of automating sizes
      
            
            
           
            //adding free classes to everyday
            function addfree(day){
               var startsAt=document.getElementById('startendtime').getAttribute("start");
               var endsAt=document.getElementById('startendtime').getAttribute("end");
               
                var x = document.getElementById(day).childElementCount; // length of child elements of particular day
              
                var previoustime=getseconds(startsAt);
                
                var endtime=getseconds(endsAt);
                console.log(day);
                var list = document.getElementById(day);
                for (let i = 1; i < x; i++) {
                   
                    
                    var childstime=getseconds(list.children[i].getAttribute('data-stime'));
                    var childetime=getseconds(list.children[i].getAttribute('data-etime'));
                     if( childstime!=previoustime){
                         var timegap=(childstime-previoustime)/3600; //in hours
                         var childwidth=oneunit*timegap;
                         var newItem = document.createElement("div");  
                         newItem.className="free";     
                         newItem.style.width=childwidth+"px";
                         var textnode = document.createTextNode(" ");  
                         newItem.appendChild(textnode);                   
                         list.insertBefore(newItem, list.children[i]);
                         i++;
                         x++;
                     }
                     previoustime=childetime;
                    
                }
            if(previoustime!=endtime){
                var timegap=(endtime-previoustime)/3600; //in hours
                var childwidth=oneunit*timegap;
                var newItem = document.createElement("div");  
                newItem.className="free";     
                newItem.style.width=childwidth+"px";
                var textnode = document.createTextNode(" ");  
                newItem.appendChild(textnode);                   
                document.getElementById(day).appendChild(newItem);
            }
           
            }
            
            //end of everyday
            function getseconds(time){
                var a = time.split(':'); // split it at the colons
                var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 ; 
                return seconds;
            }
            function start(){
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