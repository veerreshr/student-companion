<?php
session_start();
require './db.php';
$id = $_SESSION['id'];
$subjectopted = 0;
$edit = 0;
if (isset($_POST['editattendance'])) {
    $subject = $_POST['subject'];
    $attendance = $_POST['attendance'];
    $dateofedit = $_POST['dateofedit'];
    $dayOfWeek = date("D", strtotime($dateofedit));
    $lastupdate = date("Y-m-d H:i:s",strtotime($dateofedit)+86399);
    $query = "select weekid from week where id=" . $_SESSION['id'] . " and day='$dayOfWeek' and subject='$subject' and lastupdate <= '$lastupdate' ";
    $results = mysqli_query($db, $query);
    if (!$results) {
        echo ("Error description: " . mysqli_error($db)." for getting weekid".$lastupdate);
        return;
    }
    $user = mysqli_fetch_assoc($results);
    $weekid=$user['weekid'];
    
    
    
    if ($attendance == 3) {
        $query = "update daily set present=null , holiday=1 where weekid =$weekid and date='$dateofedit' and id=" . $_SESSION['id'];
        $results = mysqli_query($db, $query);
        if (!$results) {
            echo ("Error description: " . mysqli_error($db));
            return;
        }
    }
    if ($attendance == 2) {
        $query = "update daily set present=0 , holiday=0 where weekid=$weekid and date='$dateofedit' and id=" . $_SESSION['id'];
        $results = mysqli_query($db, $query);
        if (!$results) {
            echo ("Error description: " . mysqli_error($db));
            return;
        }
    }
    if ($attendance == 1) {
        $query = "update daily set present=1 , holiday=0 where weekid=$weekid and date='$dateofedit' and id=" . $_SESSION['id'];
        $results = mysqli_query($db, $query);
        if (!$results) {
            echo ("Error description: " . mysqli_error($db));
            return;
        }
    }
    header("Location: ./calendar.php?subject=$subject&edit=1"); 
}
$list = array();
if (isset($_GET['subject'])) {
    $subjectopted = 1;
    $subject = $_GET['subject'];
    $query = "select d.date ,d.present ,d.holiday from daily d ,week w where d.id=w.id and w.subject='$subject' and d.weekid=w.weekid ";
    $results = mysqli_query($db, $query);
    if (!$results) {
        echo ("Error description: " . mysqli_error($db));
        return;
    }
    if ($results != null) {
        while ($user = mysqli_fetch_assoc($results)) {
            $a = array($user['date'], $user['present'], $user['holiday']);
            array_push($list, $a);
        }
    }
    $edit = $_GET['edit'] == null ? 0 : 1;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Calendar </title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="calendar.css">
    <style>

    </style>
</head>

<body>
    <?php if ($subjectopted) { ?>
        <div class="container col-sm-4 col-md-7 col-lg-4 mt-5">
            <div class="card">
                <h3 class="card-header" id="monthAndYear"></h3>
                <table class="table table-bordered table-responsive-sm" id="calendar">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>

                    <tbody id="calendar-body">

                    </tbody>
                </table>

                <div class="form-inline">

                    <button class="btn btn-outline-primary col-sm-6" id="previous" onclick="previous()">Previous</button>

                    <button class="btn btn-outline-primary col-sm-6" id="next" onclick="next()">Next</button>
                </div>
                <br />
                <form class="form-inline">
                    <label class="lead mr-2 ml-2" for="month">Jump To: </label>
                    <select class="form-control col-sm-4" name="month" id="month" onchange="jump()">
                        <option value=0>Jan</option>
                        <option value=1>Feb</option>
                        <option value=2>Mar</option>
                        <option value=3>Apr</option>
                        <option value=4>May</option>
                        <option value=5>Jun</option>
                        <option value=6>Jul</option>
                        <option value=7>Aug</option>
                        <option value=8>Sep</option>
                        <option value=9>Oct</option>
                        <option value=10>Nov</option>
                        <option value=11>Dec</option>
                    </select>


                    <label for="year"></label><select class="form-control col-sm-4" name="year" id="year" onchange="jump()">
                        <option value=1990>1990</option>
                        <option value=1991>1991</option>
                        <option value=1992>1992</option>
                        <option value=1993>1993</option>
                        <option value=1994>1994</option>
                        <option value=1995>1995</option>
                        <option value=1996>1996</option>
                        <option value=1997>1997</option>
                        <option value=1998>1998</option>
                        <option value=1999>1999</option>
                        <option value=2000>2000</option>
                        <option value=2001>2001</option>
                        <option value=2002>2002</option>
                        <option value=2003>2003</option>
                        <option value=2004>2004</option>
                        <option value=2005>2005</option>
                        <option value=2006>2006</option>
                        <option value=2007>2007</option>
                        <option value=2008>2008</option>
                        <option value=2009>2009</option>
                        <option value=2010>2010</option>
                        <option value=2011>2011</option>
                        <option value=2012>2012</option>
                        <option value=2013>2013</option>
                        <option value=2014>2014</option>
                        <option value=2015>2015</option>
                        <option value=2016>2016</option>
                        <option value=2017>2017</option>
                        <option value=2018>2018</option>
                        <option value=2019>2019</option>
                        <option value=2020>2020</option>
                        <option value=2021>2021</option>
                        <option value=2022>2022</option>
                        <option value=2023>2023</option>
                        <option value=2024>2024</option>
                        <option value=2025>2025</option>
                        <option value=2026>2026</option>
                        <option value=2027>2027</option>
                        <option value=2028>2028</option>
                        <option value=2029>2029</option>
                        <option value=2030>2030</option>
                    </select></form>
            </div>
        </div>
        <!--<button name="jump" onclick="jump()">Go</button>-->
        <?php if ($edit) { ?>
            <div class="edit">
                <form action="calendar.php" method="post">
                    <div class="form-group">
                        <label for="dateofedit">select a date for which you want to edit</label>
                        <input type="date" class="form-control" name="dateofedit" max=<?php echo date('Y-m-d'); ?>>
                    </div>
                    <div class="form-group">
                        <select name="attendance" id="">
                            <option value="1">present</option>
                            <option value="2">absent</option>
                            <option value="3">holiday</option>
                        </select>
                    </div>
                    <input type="hidden" name="subject" value="<?php echo $subject; ?>">
                    <input type="submit" value="edit" name="editattendance" class="btn btn-primary">
                </form>
            </div>
        <?php } ?>
    <?php }
    if (!$subjectopted) {
        $sub = array();
        $query = "select subject from week  where id=" . $_SESSION['id'] . " group by subject";
        $result = mysqli_query($db, $query) or die(mysqli_error($db));
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($sub, $row['subject']);
        }

    ?>
        <div class="choosesubject">
            <label for="choose">Select the subject u want to access calendar view</label><select class="form-control col-sm-4" name="choose" id="choose">
                <?php
                $i = 0;
                while ($subj = $sub[$i]) {
                    $i = $i + 1;
                    echo "<option value=$subj>$subj</option>";
                }
                ?>

            </select>
            <button onclick="activateCalendar()">Next</button>
        </div>

    <?php
    }
    ?>
    <!-- for getting calendar and dynamically allocating colors to dates -->
    <script>
        /* for choose subject */
        function activateCalendar() {
            var subject = document.getElementById("choose").value;
            window.location.href = "./calendar.php?subject=" + subject + "&edit=1";
        }
        arrayofdata = <?php echo json_encode($list); ?>;
        for (m = 0; m < arrayofdata.length; m++) {
            if (arrayofdata[m][0] == null) {
                console.log("no data");
            } else
                console.log(arrayofdata[m][0]);
        }
        today = new Date();
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        selectYear = document.getElementById("year");
        selectMonth = document.getElementById("month");

        months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        monthAndYear = document.getElementById("monthAndYear");
        showCalendar(currentMonth, currentYear);

        function gettodaysdetails(a, b, c) {
            let dt = a + "-" + b + "-" + c;
            let pah = "";
            for (l = 0; l < arrayofdata.length; l++) {
                if (dt == arrayofdata[l][0]) {
                    if (arrayofdata[l][1] == 1 && arrayofdata[l][2] == 0) {
                        pah += "P ";
                    }
                    if (arrayofdata[l][1] == 0 && arrayofdata[l][2] == 0) {
                        pah += "A ";
                    }
                    if (arrayofdata[l][2] == 1) {
                        pah += "H ";
                    }
                }
            }
            return pah;
        }

        function next() {
            currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
            currentMonth = (currentMonth + 1) % 12;
            showCalendar(currentMonth, currentYear);
        }

        function previous() {
            currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
            currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
            showCalendar(currentMonth, currentYear);
        }

        function jump() {
            currentYear = parseInt(selectYear.value);
            currentMonth = parseInt(selectMonth.value);
            showCalendar(currentMonth, currentYear);
        }

        function showCalendar(month, year) {
            let yearforstringmatching = year;
            let monthforstringmatching = month >= 9 ? (month + 1) : "0" + (month + 1);
            let firstDay = (new Date(year, month)).getDay();

            tbl = document.getElementById("calendar-body"); // body of the calendar

            // clearing all previous cells
            tbl.innerHTML = "";

            // filing data about month and in the page via DOM.
            monthAndYear.innerHTML = months[month] + " " + year;
            selectYear.value = year;
            selectMonth.value = month;

            // creating all cells
            let date = 1;
            for (let i = 0; i < 6; i++) {
                // creates a table row
                let row = document.createElement("tr");
                //creating individual cells, filing them up with data.
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        cell = document.createElement("td");
                        cellText = document.createTextNode("");
                        cell.appendChild(cellText);
                        row.appendChild(cell);
                    } else if (date > daysInMonth(month, year)) {
                        break;
                    } else {
                        cell = document.createElement("td");
                        getpah = gettodaysdetails(yearforstringmatching, monthforstringmatching, date < 10 ? "0" + date : date);
                        cellText = document.createTextNode(date);
                        linebreak = document.createElement("br");
                        pahtext = document.createTextNode(getpah);
                        if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                            cell.classList.add("bg-info");
                        } // color today's date
                        cell.appendChild(cellText);
                        cell.appendChild(linebreak);
                        cell.appendChild(pahtext);
                        row.appendChild(cell);
                        date++;
                    }

                }

                tbl.appendChild(row); // appending each row into calendar body.
            }

        }


        // check how many days in a month code from https://dzone.com/articles/determining-number-days-month
        function daysInMonth(iMonth, iYear) {
            return 32 - new Date(iYear, iMonth, 32).getDate();
        }
    </script>

    <!-- Optional JavaScript for bootstrap -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>




</body>

</html>