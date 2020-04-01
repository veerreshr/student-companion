<?php include('server.php') ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="login1.css">
</head>

<body>
    <div class="superwrap">
        <div class="wrap">
            <div class="tab">
                <button class="tablinks" onclick="shuffle(event, 'register')"
                <?php
                if (!isset($_POST['login_user']))echo "id='defaultOpen'" ?>                
                >Register</button>
                <button class="tablinks" onclick="shuffle(event, 'login')"
               <?php if (isset($_POST['login_user'])) echo "id='defaultOpen'"    ?>
                >Login</button>
            </div>
            <div id="register" class="tabcontent">
                <div class="container">
                    <form method="post" action="login.php">
                        <centre>
                            <h3 style="color: black;">Register here</h3>
                        </centre><br>
                        <?php include('error.php'); ?><br>
                        <label for="name">
                            NAME
                        </label><br>
                        <input type="text" placeholder="Name" name="name" id="name" required><br>
                        <label for="email">Email</label><br>
                        <input type="email" name="email" id="email" placeholder="Email" required><br>
                        <label for="psw">
                            PASSWORD
                        </label><br>
                        <input type="password" placeholder="password" name="password_1" id="psw" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}" title="Must contain at least one number and one uppercase and lowercase letter , and at least 8 or more characters" required><br>
                        
                        <label for="cpsw">
                            CONFIRM PASSWORD
                        </label><br>
                        <input type="password" placeholder="confirm password" name="password_2" id="cpsw" required onchange="conPassCheck()"><br>
                        <span id="passMessage"></span><br>
                       <input type="submit" id="submit" name="reg_user" value="Register"><br>
                       
                    </form>
                    <div id="message">
                    <h3>
                        Password must contain the following:
                    </h3>
                    <p id="letter" class="invalid">A <b>lowercase
                        </b> letter</p>
                    <p id="capital" class="invalid">A <b>uppercase
                        </b> letter</p>
                    <p id="number" class="invalid">A <b>number
                        </b></p>
                    <p id="length" class="invalid">minimum <b>8 characters</b></p>
                </div>
                </div>
                

            </div>
            <div id="login" class="tabcontent">
                <div class="container">
                    <form action="login.php" method="post">
                        <centre>
                            <h3 style="color: black;">Enter Login Details!</h3>
                        </centre><br>
                        <?php include('error.php'); ?><br>
                        <label for="email">
                            Email
                        </label><br>
                        <input type="text" placeholder="Email" name="email" id="email" required><br>
                        <label for="psw">
                            PASSWORD
                        </label><br>
                        <input type="password" placeholder="password" name="password" required><br>



                        <input type="submit" id="submit" name="login_user" value="Login"><br>
                       

                    </form>
                    </container>
                </div>
            </div>
        </div>
        <script>
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
            document.getElementById("defaultOpen").click();
            //end for tabs
            //for register
            var myInput = document.getElementById("psw");
            var conPass = document.getElementById("cpsw");
            var letter = document.getElementById("letter");
            var capital = document.getElementById("capital");
            var number = document.getElementById("number");
            var length = document.getElementById("length");
            myInput.onfocus = function() {
                document.getElementById("message").style.display = "block";
            }
            myInput.onblur = function() {
                document.getElementById("message").style.display = "none";
            }
            //validation
            myInput.onkeyup = function() {
                var lowerCaseLetters = /[a-z]/g;
                if (myInput.value.match(lowerCaseLetters)) {
                    letter.classList.remove("invalid");
                    letter.classList.add("valid");
                } else {
                    letter.classList.remove("valid");
                    letter.classList.add("invalid");
                }
                var upperCaseLetters = /[A-Z]/g;
                if (myInput.value.match(upperCaseLetters)) {
                    capital.classList.remove("invalid");
                    capital.classList.add("valid");
                } else {
                    capital.classList.remove("valid");
                    capital.classList.add("invalid");
                }
                var numbers = /[0-9]/g;
                if (myInput.value.match(numbers)) {
                    number.classList.remove("invalid");
                    number.classList.add("valid");
                } else {
                    number.classList.remove("valid");
                    number.classList.add("invalid");
                }

                if (myInput.value.length >= 8) {
                    length.classList.remove("invalid");
                    length.classList.add("valid");
                } else {
                    length.classList.remove("valid");
                    length.classList.add("invalid");
                }



            }

            function conPassCheck() {
                if (myInput.value === conPass.value) {
                    document.getElementById("passMessage").style.color = "green";
                    document.getElementById("passMessage").innerHTML = "Password matched<br>";
                    document.getElementById("submit").disabled = false;

                } else {
                    document.getElementById("passMessage").style.color = "red";
                    document.getElementById("passMessage").innerHTML = "Password not matched<br>";
                    document.getElementById("submit").disabled = true;

                }
            }

            //end for registers
        </script>
</body>

</html>