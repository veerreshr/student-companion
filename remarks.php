<?php
session_start();
require "db.php";
            function needtoattend($a,$b,$c){
                $d=0;
                while(($a+$d)/($b+$d)<$c){
                    $d++;
                }
                return $d;
            }
            function mayleave($a,$b,$c){
                $d=0;
                    while(($a-$d)/($b-$d)>=$c){
                        $d++;
                    }
                    $d=$d-1;
                    return $d;
            }
            $barcolor = array("#50d07d", "#00539CFF", "#DC3D24", "#D6ED17FF", "#DAA03DFF", "#ef1e25");
            $backcolor = array("#b2cecf", "#EEA47FFF", "#232B2B", "#606060FF", "#616247FF", "#aedaa6");
            $subjects = array();
            $query = "select goal from register where id=" . $_SESSION['id'];
            $result = mysqli_query($db, $query) or die(mysqli_error($db));
            $row = mysqli_fetch_assoc($result);
            $goal = $row['goal'];
            $query = "select subject from week  where id=" . $_SESSION['id']." group by subject";
            $result = mysqli_query($db, $query) or die(mysqli_error($db));
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($subjects, $row['subject']);
            }
            for ($i = 0; $i < count($subjects); $i++) {
                $colorindex = $i % 6;
                
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
                if($percentage<$goal){
                   $need=needtoattend($attended,$total,$goal);
                    if ($need1 >= 8) {
                        $statement = "Ur in a wrong way , " . $need1 . " more classes to attened";
                        
                    } elseif ($need1 >= 6) {
                        
                        $statement = "U can cope up," . $need1 . " more classes to attend";
                    } elseif ($need1 >= 3) {
                        $statement = "oh no , u need1$need1 to attend next " . $need1 . " classes";
                    } else {
                        $statement = "almost there, still " . $need1 . " more classes";
                    }

                }elseif($percentage>$goal){
                    $leave1=mayleave($attended,$total,$goal);
                    if ($leave1 <= 2) {
                        $statement = "Thats awesome , you may leave1 next ".$leave1." classes";
                    } elseif ($leave1 <= 5) {
                        $statement = "Your going amazing, You may leave1 ur next ".$leave1." classes";
                    } else {
                        $statement = "Thats fantastic , you may leave1 ur next ".$leave1." classes";
                    }
                }elseif($percentage==$goal){
                    $statement = "Perfect, Your in the track ";
                }
            ?>
                <div class="outerbox" style="background-color: <?php echo $backcolor[$colorindex]; ?>">
                    <div class="card">
                        <div class="graph">
                            <div class="chart" data-percent="<?php echo $percentage; ?>" data-bar-color="<?php echo $barcolor[$colorindex];   ?>" data-scale-color="#ffb400">
                                <p><?php echo round($percentage,2); ?></p>
                            </div>
                        </div>
                        <div class="description">
                            <h2><?php echo $subjects[$i]; ?></h2>
                            <h4></h4>
                            <h1><?php echo "$attended /  $total"; ?></h1>
                            <h4></h4>
                            <p><?php echo $statement;  ?></p>
                            <div style="border-color:<?php echo $backcolor[$colorindex]; ?>;color:<?php echo $backcolor[$colorindex]; ?>"><button>calendar</button></div>


                        </div>
                    </div>
                </div>


            <?php
            }
            ?>

