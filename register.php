<?php
require_once('db.php');

session_start();
if(!isset($_SESSION['auth'])){

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if(isset($name) AND !empty($name) AND isset($email) AND !empty($email) AND isset($phone) AND !empty($phone) AND isset($password) AND !empty($password) AND isset($password_confirm) AND !empty($password_confirm)) {
        
        if ($password == $password_confirm) {
            $pwd = sha1($password);
                
                $check_name = $db->prepare("SELECT * FROM users WHERE full_name = ?");
                $check_name->execute(array($name));
                if($check_name->rowCount() == 0) {
                    
                    $check_email = $db->prepare("SELECT * FROM users WHERE email = ?");
                    $check_email->execute(array($email));
                    if($check_email->rowCount() == 0) {
                        
                        $check_phone = $db->prepare("SELECT * FROM users WHERE phone_number = ?");
                        $check_phone->execute(array($phone));
                        if($check_phone->rowCount() == 0) {
                        
                            $sql = $db->prepare("INSERT INTO users(full_name, email, phone_number, password) VALUES(?,?,?,?)");
                            $result = $sql->execute(array($name, $email, $phone, $pwd));
                            
                            if($result){
                                $success = 'Registration was successfull. Go to login page or click <a href="login.php">here</a>';
                            }else{
                                $error = 'There were errors while saving the data.';
                            }
                        } else{
                            $error = "The user with this phone number already exist";
                        }
                    } else {
                        $error = "The user with this email already exist";
                    }
                }
                else {
                    $error = "The user with this name already exist";
                }
            
        }
        else {
            $error = "Passwords don't match";
        }
    } else {
        $error = "All the fields are required";
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
                <li><a href="wishlist.html">Our Wishlist</a></li>
                <li><a href="support.php">Support Us</a></li>
                <li><a class="login" href="login.php">Login</a></li>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        <div class="box">
            <h1 class="login-title">Sign Up</h1>
            <form method="post">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <?php if(isset($success)) {
                    ?>
                    <div class="success">
                        <strong><?= $success ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="name">Name:</label>
                    <input type="name" name="name" value="<?php if($_POST['name']) { echo $_POST['name']; } ?>" placeholder="Type your name here">
                </div>

                <div class="email-section">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php if($_POST['email']) { echo $_POST['email']; } ?>"  placeholder="Type your email here">
                </div>
                
                <div class="email-section">
                    <label for="phone">Phone number:</label>
                    <input type="number" name="phone" value="<?php if($_POST['phone']) { echo $_POST['phone']; } ?>"  placeholder="Type your phone number here">
                </div>

                <div class="password-section">
                    <label for="password">Password:</label>
                    <input type="password" name="password" placeholder="Type your password here">
                </div>

                <div class="password-section">
                    <label for="password_confirm">Confirm Password:</label>
                    <input type="password" name="password_confirm" placeholder="Repeat password">
                </div>

                <div class="btn-section">
                    <input class="login-btn" name="register" type="submit" value="Register">
                    <p>Already have an account? <a href="login.php" class="signup-link">Login</a> or Register as an organisation <a href="org_register.php" class="signup-link">here</a></p>
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