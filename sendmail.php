<?php

/* Namespace alias. */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '.\composer\vendor\autoload.php';

require 'db.php';
$query="select register.id,register.name,register.email,remainder.subject,remainder,remainder.body,remainder.dt from remainder inner join register on register.id=remainder.id";
$result = mysqli_query($db, $query)  or die("connection failed at retrive");
while($row = mysqli_fetch_assoc($result)){
$dt=$row['dt'];
if($dt>=(time()-600) && $dt<=time()){
$mail = new PHPMailer(TRUE);

try {
   /* Set the mail sender. */
   $mail->setFrom('veeresh.ravipati.7@gmail.com', 'veeresh r');
   $recievermail=$row['email'];
   $recievername=$row['name'];
   /* Add a recipient. */
   $mail->addAddress('$recievermail', '$recievername');

   /* Set the subject. */
   $sub=$row['subject'];
   $mail->Subject = '$sub';

   /* Set the mail message body. */
   $body=$row['body'];
   $mail->Body = '$body';

   /* Finally send the mail. */
   $mail->send();
   $id=$row['id'];

   $query="delete from remainder where id='$id' and dt='$dt' and body='$body'";
   mysqli_query($db, $query)  or die("failed deleting completed remainder");

}
catch (Exception $e)
{
   
   echo $e->errorMessage();
}
catch (\Exception $e)
{
    echo $e->getMessage();
}
}
}
?>