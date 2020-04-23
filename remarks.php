<?php
session_start();
require './db.php';
$subjects=array();
$query="select goal from register where id=1";
$result= mysqli_query($db, $query) or die(mysqli_error($db));
$goal=mysqli_fetch_field($result);
$query="select subject from week where id=1 group by subject";
$result= mysqli_query($db, $query) or die(mysqli_error($db));
while($row=mysqli_fetch_assoc($result)){
    array_push($subjects,$row['subject']);
    print_r($row['subject']);
}
for($i=0;$i<count($subjects);$i++){
    $colorindex=$i%6;
    $lastsevendays=array();
    $query="select count(*)as pre from daily where present=1 and subject='".$subjects[$i]."' and holiday <> 1 and id=".$_SESSION['id'];
    $result= mysqli_query($db, $query) or die(mysqli_error($db));
    $row=mysqli_fetch_assoc($result);
    $attended=$row['pre'];
    $query="select count(*)as abs from daily where present=0 and subject='".$subjects[$i]."' and holiday <> 1 and id=".$_SESSION['id'];
    $result= mysqli_query($db, $query) or die(mysqli_error($db));
    $row=mysqli_fetch_assoc($result);
    $absent=$row['abs'];
    $total=$attended+$absent;   //total number of classes
    $percentage=($attended*100)/$total; //current att percentage
    $acceptableabsents=((100-$goal)*$total)/100; //gives the no of absents that can be accepted for getting attendence goal
    $diff=$acceptableabsents-$absent; //if positive , u can absent for that many classes, if negative u need to attend those many, if zero, its perfect
    if($diff>0){
        if($diff<=0){
            $statement="Thats awesome , you may leave next $diff classes";
        }elseif($diff<=5){
            $statement="Your going amazing, You may leave ur next $diff classes";
        }else{
            $statement="Thats fantastic , you may leave ur next $diff classes";
        }
    }elseif($diff<0){
        if($diff>=-2){
            $statement="almost there, still ".abs($diff)." more classes";
        }elseif($diff>=-4){
            $statement="U can cope up,".abs($diff)." more classes to attend";
        }elseif($diff>=-8){
            $statement="oh no , u need to attend next ".abs($diff)." classes";
        }else{
            $statement="Ur in a wrong way , ".abs($diff)." more classes to attened";
        }
    }else{
        $statement="Perfect, Your in the track ";
    }
    $query="select count(*)as total from daily where subject='".$subjects[$i]."' and id=".$_SESSION['id'];
    $result= mysqli_query($db, $query) or die(mysqli_error($db));
    $row=mysqli_fetch_assoc($result);
    $total=$row['total'];
   
    $query="select * from daily where subject='".$subjects[$i]."' and id=".$_SESSION['id']." LIMIT 7 OFFSET $total-7";
    $result= mysqli_query($db, $query) or die(mysqli_error($db));
    while($row=mysqli_fetch_assoc($result)){
        if($row['holiday']==1){  array_push($lastsevendays,'hol'); 
             }elseif($row['present']==1){  array_push($lastsevendays,'pre');
                }elseif($row['present']==0){  array_push($lastsevendays,'abs'); }
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
                <h1><?php echo $attended."/".$total;?></h1>
                <h4><?php 
                for($j=0;$j<7;$j++){
                    if($lastsevendays[$j]=='pre'){
                        echo "<i class='fa fa-circle' aria-hidden='true' style='color:green;'></i>  ";
                    }elseif($lastsevendays[$j]=='abs'){
                        echo "<i class='fa fa-circle' aria-hidden='true' style='color:red;'></i>  ";
                    }elseif($lastsevendays[$j]=='abs'){
                        echo "<i class='fa fa-circle' aria-hidden='true' style='color:blue;'></i>  ";
                    }
                }
                ?></h4>
                <p><?php echo $statement;  ?></p>
                <button>calendar</button>

            </div>
        </div>
    </div>


<?php
}


?>



