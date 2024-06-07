<?php
session_start();
if (!isset($_SESSION['uname'])) {
    header("Location: login.php");
    exit();
}

$sqluser = "root";
$sqlpassword = "";
$sqldatabase = "login";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['current_pass']) || empty($_POST['new_pass']) || empty($_POST['confirm_pass'])) {
        $empty_fields = true;
    } else {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=".$sqldatabase, $sqluser, $sqlpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
        $st = $pdo->prepare('SELECT * FROM list WHERE user_name=?');
        $st->execute(array($_SESSION['uname']));
        $r = $st->fetch();
        if ($r != null && password_verify($_POST['current_pass'], $r["password"])) {
            if ($_POST['new_pass'] == $_POST['confirm_pass']) {
                $new_password_hashed = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
                $st = $pdo->prepare('UPDATE list SET password=? WHERE user_name=?');
                $st->execute(array($new_password_hashed, $_SESSION['uname']));
                $password_changed = true;
            } else {
                $password_mismatch = true;
            }
        } else {
            $incorrect_current_password = true;
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<style type="text/css">
    body {
        margin: 0px;
        padding: 0px;
        font-family: sans-serif;
        font-size: .9em;
        background-image: url("img/mike-kenneally-TD4DBagg2wE-unsplash.jpg");
    }
    div {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        position: absolute;
        width: 600px;
        height: 450px;
        color: white;
        padding: 10px 20px;
        border-radius: 7px;
        box-shadow: 0px 0px 30px rgba(227, 228, 237, 0.37);
        border: 2px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(30px);
    }
    input {
        display: inline-block;
        border: none;
        height: 40px;
        width: 100%;
        border-radius: 2px;
        margin: 5px 0px;
        padding: 7px;
        box-sizing: border-box;
        box-shadow: 0px 0px 2px #ccc;
    }
    #submit {
        border: none;
        background-color: #d3a27f;
        color: beige;
        font-size: 1em;
        box-shadow: 0px 0px 3px #777;
        padding: 10px 0px;
    }
    span {
        color: red;
        font-size: 0.75em;
    }
    p {
        text-align: center;
        font-size: 1.75em;
    }
    a {
        text-decoration: none;
        color: whitesmoke;
        font-weight: bold;
    }
</style>
</head>
<body>
<div>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p>Change Password</p>
    <label>Current Password</label><br>
    <input type="password" name="current_pass" placeholder="Current Password"><br><br>
    <label>New Password</label><br>
    <input type="password" name="new_pass" placeholder="New Password"><br><br>
    <label>Confirm New Password</label><br>
    <input type="password" name="confirm_pass" placeholder="Confirm New Password"><br><br>
    <?php
    if (!empty($empty_fields) && $empty_fields) echo "<span>Please fill all the fields.</span><br>";
    if (!empty($incorrect_current_password) && $incorrect_current_password) echo "<span>Incorrect current password.</span><br>";
    if (!empty($password_mismatch) && $password_mismatch) echo "<span>New password and confirm password do not match.</span><br>";
    if (!empty($password_changed) && $password_changed) echo "<span>Password changed successfully.</span><br>";
    ?>
    <br>
    <input type="submit" id="submit" value="Change Password"><br><br>
    <a href="index.php">Back to Home</a>
</form>
</div>
</body>
</html>
