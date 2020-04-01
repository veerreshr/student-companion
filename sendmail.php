<?php

/* Namespace alias. */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '.\composer\vendor\autoload.php';



$mail = new PHPMailer(TRUE);

try {
   /* Set the mail sender. */
   $mail->setFrom('veeresh.ravipati.7@gmail.com', 'veeresh r');

   /* Add a recipient. */
   $mail->addAddress('venkatsureshr77@gmail.com', 'veeresh r');

   /* Set the subject. */
   $mail->Subject = 'trying it';

   /* Set the mail message body. */
   $mail->Body = 'once upon a time';

   /* Finally send the mail. */
   $mail->send();
}
catch (Exception $e)
{
   
   echo $e->errorMessage();
}
catch (\Exception $e)
{
    echo $e->getMessage();
}
