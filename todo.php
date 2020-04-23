<?php
session_start();
require './db.php';
if (isset($_POST['note'])) {
    $note = $_POST['note'];
    $dt = date("Y-m-d H:i:s", strtotime($_POST['dt']));
    $default = 'td';
    $query = "insert into todo (id,content,done,dt) values(" . $_SESSION['id'] . ",'$note',0,'$dt')";
    mysqli_query($db, $query) or die(mysqli_error($db));
}
if(isset($_GET['done'])){
    $count=$_GET['done'];
    $query="update todo set done=1 where  count=$count and id=".$_SESSION['id'];
    mysqli_query($db, $query) or die(mysqli_error($db));
}
if(isset($_GET['removeremainder'])){
    $count=$_GET['removeremainder'];
    $query="delete from remainder where count=$count and id=".$_SESSION['id'];
    mysqli_query($db, $query) or die(mysqli_error($db));
}
if(isset($_GET['remainder'])){
    $count=$_GET['remainder'];
    $query="select * from todo where count=$count and id=".$_SESSION['id'];
    $result=mysqli_query($db, $query) or die(mysqli_error($db));
    // $query="insert into remainder(id,count,content,dt) select id,count,content,dt from todo where count=$count and id=".$_SESSION['id'];
    $row=mysqli_fetch_assoc($result);
    $id=$row['id'];
    $count=$row['count'];
    $content=$row['content'];
    $dt=$row['dt'];
    $query="insert into remainder(id,count,content,dt) values($id,$count,'$content','$dt')";
    mysqli_query($db, $query) or die(mysqli_error($db));
    
}
if(isset($_GET['delete'])){
    $count=$_GET['delete'];
   
    $query="delete from todo where count=$count ";
    mysqli_query($db, $query) or die(mysqli_error($db));
    $query="delete from remainder where count=$count ";
    mysqli_query($db, $query) or die(mysqli_error($db));

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://use.fontawesome.com/0eb2c0a554.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="todo.css" />
</head>

<body>

    <div id="wrapper">
        <div id="todo-input">
            <p>My Todo</p>
            <div id="options">
                <form action="todo.php" method="post">
                    <div class="datetime">
                        <input type="datetime-local" name="dt" id="dt" required />
                    </div>
                    <div class="text">
                        <input type="text" name="note" id="note" placeholder="Enter Your Note" required />
                        <button type="submit"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div id="todo-output">
            <div class="todoheading">TODOS</div>
            <?php




            $query = "select * from todo where id=" . $_SESSION['id'];
            $result = mysqli_query($db, $query) or die(mysqli_error($db));
            while ($row = mysqli_fetch_assoc($result)) {
                $content = $row['content'];
                $dt = $row['dt'];
                $done = $row['done'];
                $count = $row['count'];
                $cantsetremainder = 0;
                $alreadyset = 0;
                $query2 = "select * from remainder where id=" . $_SESSION['id'] . " and content='$content' and dt='$dt'";
                $result2 = mysqli_query($db, $query2) or die(mysqli_error($db));
                if (mysqli_num_rows($result2) != 0) {
                    $alreadyset = 1;
                }
                if (strtotime($dt) < time()) {
                    $cantsetremainder = 1;
                }

            ?>
                <div class="todo <?php if ($done == 1) {
                                        echo "checked";
                                    } ?>">

                     <button onclick="location.href='todo.php?done=<?php echo $count; ?>'"><i class="fa fa-check fa-lg" aria-hidden="true"></i></i></button>

                    <li title="<?php echo $dt;  ?>">
                        <?php echo $content; ?>
                    </li>
                    <?php if ($cantsetremainder == 1 || $done==1) {
                        echo  "<button ><i class='fa fa-bell-slash fa-lg' aria-hidden='true'></i></button>";
                    } else {
                        if ($alreadyset == 1) {
                            echo  " <button onclick=\"location.href='todo.php?removeremainder=$count'\"><i class='fa fa-bell fa-lg notified' aria-hidden='true'></i></button>";
                        } else {
                            echo  "<button onclick=\"location.href='todo.php?remainder=$count'\"><i class='fa fa-bell fa-lg' aria-hidden='true'></i></button>";
                        }
                    }  ?>

                    <button onclick="location.href='todo.php?delete=<?php echo $count; ?>'"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                </div>

            <?php
            }

            ?>



        </div>
    </div>

</body>

</html>