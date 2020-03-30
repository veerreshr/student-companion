<?php 
 $weekid=0;

function block($day){ ?>
<div class="block">
    <form method="post" action="">
    <input type="text" placeholder="Subject name"><br>
    <input type="checkbox" name="break" id=""> break time <br>
    start time <input type="time" name="starttime" id=""><br>
    end time <input type="time" name="endtime" id=""><br>
    <input type="submit" value="Done">
    </form>
    
</div>
<?php    
}?>