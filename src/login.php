<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

// Include file konfigurasi
include 'confiq.php';

$post = $_SERVER['REQUEST_METHOD'] == 'POST';
if ($post) {
    if (empty($_POST['uname']) || empty($_POST['pass'])) {
        $empty_fields = true;
    } else {
        $st = $pdo->prepare('SELECT * FROM list WHERE user_name=?');
        $st->execute(array($_POST['uname']));
        $r = $st->fetch();
        if ($r != null && password_verify($_POST['pass'], $r["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["uname"] = $_POST["uname"];
            $_SESSION["fname"] = $r["first_name"];
            
            header("Location: index.php");
            exit();
        } else {
            $login_err = true;
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
        /* filter: blur(30px); */
        
        
    }
    div {
        top:50%;
        left:50%;
        transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
        -moz-transform: translate(-50%,-50%);
        -webkit-transform: translate(-50%,-50%);
        position:absolute;
        width:600px;
        height: 390px;
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
        height: 40px;
        width:100%;
        border-radius:2px;
        margin:5px 0px;
        padding:7px;
        box-sizing: border-box;
        box-shadow: 0px 0px 2px #ccc;
        box-sizing: border-box;
    }

    #remember{
        display: inline-block;
        border: none;
        height: 15px;
        width:15px;
        border-radius:2px;
        margin:5px 0px;
        padding:7px;
        box-sizing: border-box;
        box-shadow: 0px 0px 2px #ccc;
        box-sizing: border-box;
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
</head> 
<body>
<div>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <p>Login</p>
    <?php 
    echo 'Username<br><input type="text" name="uname" value="'.$_POST['uname'].'" placeholder="Username"><br>';
    echo '<br>Password<br><input type="password" name="pass" value="'.$_POST['pass'].'" placeholder="Password"><br>';
    if(!empty($login_err)&&$login_err) echo "<span>Incorrect Username or password.</span>";
    if(!empty($empty_fields)&&$empty_fields) echo "<span>Enter username and password.</span>";
    ?>
    <br>
    <input type="submit" id="submit" value="Login"><br><br>
    
    
    
    Don't have a account? <a href="signup.php">SignUp</a>.<br><br>
</form>
</div>
</body>
</html>