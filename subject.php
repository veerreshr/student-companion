<?php  

            
            function subject($time){
                $daylist=array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
                $presentday = date('D', $time+19800);
                switch($presentday){
                    case 'Mon' :  
                                    for($i=0;$i<7;$i++){
                                        row($time+($i*86400),$daylist[$i],$time);
                                    }                
                                    break;
                    case 'Tue' :
                                    row($time-86400,$daylist[0],$time);
                                    for($i=0;$i<6;$i++){
                                        row($time+($i*86400),$daylist[$i+1],$time);
                                    } 
                                    break;
                    case 'Wed' :    $j=0;
                                    for($i=2;$i>0;$i--){
                                        row($time-86400*($i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    for($i=0;$i<5;$i++){
                                        row($time+(86400*$i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    break;
                    case 'Thu' :
                                    $j=0;
                                    for($i=3;$i>0;$i--){
                                        row($time-86400*($i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    for($i=0;$i<4;$i++){
                                        row($time+(86400*$i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    break;
                    case 'Fri' :
                                    $j=0;
                                    for($i=4;$i>0;$i--){
                                        row($time-86400*($i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    for($i=0;$i<3;$i++){
                                        row($time+(86400*$i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    break;
                    case 'Sat' :
                                    $j=0;
                                    for($i=5;$i>0;$i--){
                                        row($time-86400*($i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    for($i=0;$i<2;$i++){
                                        row($time+(86400*$i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    break;
                    case 'Sun' :
                                    $j=0;
                                    for($i=6;$i>0;$i--){
                                        row($time-86400*($i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    for($i=0;$i<1;$i++){
                                        row($time+(86400*$i),$daylist[$j],$time);
                                        $j=$j+1;
                                    }
                                    break;
                    default : echo "something went wrong!";    
                }
            }
            function row($time,$day,$today){
                require './db.php';
               
                $query="select max(lastupdate)as last from week where day='$day' and id='".$_SESSION['id']."'";
                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                $latest=mysqli_fetch_assoc($result);
                $from=date("Y-m-d H:i:s",(strtotime($latest['last'])-(15*60)));
                $to=date("Y-m-d H:i:s",(strtotime($latest['last'])+(15*60)));
                $query="select * from week where day='$day' and id='".$_SESSION['id']."' and lastupdate between '$from' and '$to' order by stime asc";
                $result = mysqli_query($db, $query) or die(mysqli_error($db)); 
?> 
 
            <div id="<?php echo $day; ?>">
            <div class="firstcol" <?php if($time==$today)echo 'today'?>>    <?php echo $day ?></div>
                <?php
                while($row = mysqli_fetch_assoc($result)){
            $weekid=$row['weekid'];
            $subject=$row['subject'];
            $stime=gmdate("H:i",strtotime($row['stime']));
            $etime=gmdate("H:i",strtotime($row['etime']));
            $dtime=strtotime($etime)-strtotime($stime);
            $query="select * from daily where id=".$_SESSION['id']." and weekid=$weekid and date='".date("Y-m-d ",$time+19800)."'";
            $result2 = mysqli_query($db, $query) or die(mysqli_error($db));
            $classname='subject';
            if(mysqli_num_rows($result2)!=0){
                $row2= mysqli_fetch_assoc($result2);
                if($row2['present']==0){$classname='absent';}
                if($row2['present']==1){$classname='present';}
                if($row2['holiday']==1){$classname='holiday';}
            }
           

            ?>
            <div class="subject <?php echo $classname; ?>" data-stime="<?php echo $stime;  ?>" data-etime="<?php echo $etime;  ?>" data-weekid="<?php echo $weekid; ?>">
            <div class="inner"><?php  echo $subject; ?></div>
            <div class="links">
                <a title="present" href="<?php if($time<=$today)echo "index.php?present=1&weekid=$weekid"."&date=".date("Y-m-d",$time+19800);   ?>"><svg class="yes" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z"/></svg></a>
                <a title="absent" href="<?php if($time<=$today)echo "index.php?present=0&weekid=$weekid"."&date=".date("Y-m-d",$time+19800);   ?>"><svg class="no" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none" opacity=".87"/><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.59-13L12 10.59 8.41 7 7 8.41 10.59 12 7 15.59 8.41 17 12 13.41 15.59 17 17 15.59 13.41 12 17 8.41z"/></svg></a>
                <a title="holiday" href="<?php if($time<=$today)echo "index.php?holiday=1&weekid=$weekid"."&date=".date("Y-m-d",$time+19800);   ?>"><svg class="hday" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M7 11h2v2H7v-2zm14-5v14c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2l.01-14c0-1.1.88-2 1.99-2h1V2h2v2h8V2h2v2h1c1.1 0 2 .9 2 2zM5 8h14V6H5v2zm14 12V10H5v10h14zm-4-7h2v-2h-2v2zm-4 0h2v-2h-2v2z"/></svg></a>
            </div>
            </div>
            <?php
                }
                ?>
            </div>
            <?php
            }
            ?>