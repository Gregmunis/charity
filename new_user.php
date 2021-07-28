<?php
require_once('db.php');

session_start();
if(isset($_SESSION['auth'])){
    
    $user = $_SESSION['auth']; 
    
    $req = $db->prepare("SELECT * FROM admins WHERE id = ?");
    $req->execute(array($user['id']));

    if($req->rowCount() == 0) {
        $req = $db->prepare("SELECT * FROM users WHERE id = ?");
        $req->execute(array($user['id']));  
    }

    $current = $req->fetch();
    if ($current['is_admin']) {

        if(isset($_POST['register'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
        
            if(isset($name) AND !empty($name) AND isset($email) AND !empty($email) AND isset($phone) AND !empty($phone) AND isset($password) AND !empty($password)) {
                        
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
                                        header("Location: users.php");
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
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="items.php">Items</a></li>
                <li class="active"><a href="users.php">Users</a></li>
                <li><a href="organisations.php">Organisations</a></li>
                <li><a href="wishlists.php">Wishlists</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><?= $current['full_name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        <div class="box">
            <h1 class="login-title">Add new user</h1>
            <form method="post">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="name">Name:</label>
                    <input type="name" name="name" placeholder="Type the name here">
                </div>

                <div class="email-section">
                    <label for="email">Email:</label>
                    <input type="email" name="email" placeholder="Type the email here">
                </div>
                
                <div class="email-section">
                    <label for="phone">Phone number:</label>
                    <input type="number" name="phone" placeholder="Type the phone number here">
                </div>

                <div class="password-section">
                    <label for="password">Password:</label>
                    <input type="password" name="password" placeholder="Type the password here">
                </div>

                <div class="btn-section">
                    <input class="login-btn" name="register" type="submit" value="Save">
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
    header("Location: home.php");
    }
}
else {
    header("Location: login.php");
}
?>