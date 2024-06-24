<?php
session_start();

// Include file konfigurasi
include 'confiq.php';

$post = $_SERVER['REQUEST_METHOD']=='POST';

if ($post) {
    if (
        empty($_POST['uname']) ||
        empty($_POST['fname']) ||
        empty($_POST['lname']) ||
        empty($_POST['email']) ||
        empty($_POST['pass']) ||
        empty($_POST['repass'])
    ) {
        $empty_fields = true;
    } else {
       
            $st = $pdo->prepare('SELECT * FROM list WHERE user_name=?');
            $st->execute(array($_POST['uname']));
            $uname_err = $st->fetch() != null;
            $st = $pdo->prepare('SELECT * FROM list WHERE email=?');
            $st->execute(array($_POST['email']));
            $email_err = $st->fetch() != null;
            if (!$uname_err && !$email_err) {
                $stmt = 'INSERT INTO list(user_name,first_name,last_name,email,password) VALUES (?,?,?,?,?)';
                $hashed_password = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                $pdo->prepare($stmt)->execute(array(
                    $_POST['uname'],
                    $_POST['fname'],
                    $_POST['lname'],
                    $_POST['email'],
                    $hashed_password
                ));
                $_SESSION["login"] = true;
                $_SESSION["uname"] = $_POST["uname"];
                $_SESSION["fname"] = $_POST["fname"];
                header("Location: index.php");
                exit();
            }
        
    }
}
?>



<!DOCTYPE HTML>
<html>
<head>
<style type="text/css">
    body {
        margin:0px;
        padding:0px;
        font-family: sans-serif;
        font-size:.9em;
        background-image: url("../img/mike-kenneally-TD4DBagg2wE-unsplash.jpg");
    }
    div {
        top:50%;
        left:50%;
        transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
        -moz-transform: translate(-50%,-50%);
        -webkit-transform: translate(-50%,-50%);
        position:absolute;
        width:350px;
        color: white;
        /* background:#eee; */
        padding:10px 20px;
        border-radius: 7px;
        box-shadow:0px 0px 10px #aaa;
        box-sizing:border-box;
        backdrop-filter: blur(30px);
        box-shadow: 0px 0px 30px rgba(227, 228, 237, 0.37);
        border: 2px solid rgba(255, 255, 255, 0.18);
    }
    input {
        display: inline-block;
        border: none;
        width:100%;
        border-radius:2px;
        margin:5px 0px;
        padding:7px;
        box-sizing: border-box;
        box-shadow: 0px 0px 2px #ccc;
    }
    #submit {
        border:none;
        background-color: #d3a27f;
        color: beige;
        font-size:1em;
        box-shadow: 0px 0px 3px #777;
        padding:10px 0px;
    }
    span {
        color:red;
        font-size: 0.75em;
    }
    p {
        text-align: center;
        font-size: 1.75em;
    }
    a {
        text-decoration: none;
        color:#d3a27f;
        font-weight: bold;
    }
</style>
<script src="../js/validate.js"></script>
</head> 
<body>
<div>
<form name="signupForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return validateform()">
    <p>SignUp</p>
    Username<br>
    <input type="text" name="uname" placeholder="Username"><br>
    <span id="uname-error"></span> <?php if (!empty($uname_err) && $uname_err) echo '<span>Username taken. Try another username.</span>';?><br>

    
    Name<br>
    <input type="text" name="fname" placeholder="First Name"><br>
    <span id="fname-error"></span><br>
    <input type="text" name="lname" placeholder="Last Name"><br>
    <span id="lname-error"></span><br>
    
    E-mail<br>
    <input type="text" name="email" placeholder="email@example.com"><br>
    <span id="email-error"></span> <?php if(!empty($email_err)&&$email_err) echo '<span>Email already registered. Enter another email.</span>';?><br>
    
    Password<br>
    <input type="password" name="pass" placeholder="Password"><br>
    <span id="pass-error"></span><br>
    <input type="password" name="repass" placeholder="Retype password"><br>
    <span id="repass-error"></span><br>
    
    <br>
    <input type="submit" id="submit" value="SignUp"><br><br>
    Already have an account? <a href="login.php">LogIn</a>.<br><br>
</form>
</div>
</body>
</html>