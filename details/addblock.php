<?php
$_SESSION['id']=1;//remove later
$db = mysqli_connect('localhost', 'root', '', 'dbms_project') or die("connection failed at begin");
$query = "select ifnull(max(weekid),0) from week where id='".$_SESSION['id']."'";
$result = mysqli_query($db, $query);
// $user = mysqli_fetch_assoc($result);
$row = mysqli_fetch_array($result);
$weekid=$row[0];
$_SESSION['weekid'] = $weekid;
function addblock($day)
{
  $_SESSION['weekid'] = $_SESSION['weekid'] + 1;
?>
  <button class="myBtn" onclick="displaymodal(<?php echo $_SESSION['weekid']; ?>)">&#43;</button>

  <!-- The Modal -->
  <div class="modal" id="<?php echo $_SESSION['weekid']; ?>">

    <!-- Modal content -->
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="block">
        <form method="post" action="details.php">
          <?php
          echo $day;
          echo $_SESSION['weekid'];
          ?>
          <input type="hidden" name="day" value="<?php echo $day; ?>">
          <input type="text" name="subject" placeholder="Subject name"><br>
          <input type="checkbox" name="break" value="break"> break time <br>
          start time <input type="time" name="starttime" id=""><br>
          end time <input type="time" name="endtime" id=""><br>
          <input type="submit" value="Done" name="submit">
        </form>
      </div>

    </div>
  </div>
<?php }
?>