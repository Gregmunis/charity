<?php

require_once('db.php');

session_start();
if(!isset($_SESSION['auth'])){

    if(isset($_POST['login']))
    {
    
        if(isset($_POST['email']) AND !empty($_POST['email']) AND isset($_POST['password']) AND !empty($_POST['password']))
        {
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
        
            $req = $db->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
            $req->execute(array($email, $password));
            $user_exist = $req->rowCount();

            if($user_exist == 1) {
                $user = $req->fetch();
                $_SESSION['auth'] = $user;
                header("Location: dashboard.php");
            }
            else {

                $req = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
                $req->execute(array($email, $password));
                $user_exist = $req->rowCount();
                if($user_exist == 1)
                {
                    $user = $req->fetch();
                    $_SESSION['auth'] = $user;
        
                    if($user['is_admin'])
                    {
                        header("Location: dashboard.php");
                    }
                    else 
                    {
                        header("Location: support.php");
                    }
                }
                elseif($user_exist == 0) {
                    $req = $db->prepare("SELECT * FROM organisations WHERE email = ? AND password = ?");
                    $req->execute(array($email, $password));
                    $user_exist = $req->rowCount();
                    if($user_exist == 1)
                    {
                        $user = $req->fetch();
                        if($user['is_active']) {
                            $_SESSION['auth'] = $user;
                
                            header("Location: home.php");
                        } else {
                            $error = "Account not yet activated, please contact the Admin";
                        }
                    }
                    else
                    {
                        $error = "Username or password incorrect !";
                        
                    }
                }
                else
                {
                    $error = "Username or password incorrect !";
                    
                }
            }
        }
        else
        { 
            $error = "All the field are required !";
        }
    }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css">
    <title>Charity</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo.png" width="50" alt="">
        </div>
        <div class="menu">
            <ul>
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="wish.php">Our Wishlist</a></li>
                <li><a href="support.php">Support Us</a></li>
                <li><a class="login" href="login.php">Login</a></li>
            </ul>
        </div>
    </header>
    <section class="loginSection">
        <div class="box">
            <h1 class="login-title">Sign In</h1>
            <form method="post">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>"  placeholder="Type your email here">
                </div>
                <div class="password-section">
                    <label for="password">Password:</label>
                    <input type="password" name="password"  placeholder="Type your password here">
                </div>
                <div class="btn-section">
                    <input class="login-btn" name="login" type="submit" value="Login">
                    <p>Don't have an account yet? <a href="register.php" class="signup-link">Signup</a></p>
                </div>
            </form>
        </div>
    </section>
    <footer>
        <p>&copy 2021. Charity, All rights reserved.</p>
    </footer>
</body>
</html>

<?php 
}
else {
    $user = $_SESSION['auth'];
    if($user['is_admin']) {
        header("Location: dashboard.php");
    }
    elseif($user['is_active']) {
        header("Location: home.php");
    }
    else {
        header("Location: support.php");
    }
}
?>