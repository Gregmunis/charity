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
        
        $id = $_GET['id'];
        
        if(isset($id) AND !empty($id)) {
            $check_user = $db->prepare("SELECT * FROM users WHERE id = ?");
            $check_user->execute(array($id));
            if($check_user->rowCount() > 0) {
                $user = $check_user->fetch();
                
                if(isset($_POST['save'])) {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $password = $_POST['password'];
                            
                    $success = null;
                    
                    if(isset($name) AND !empty($name) AND $name != $user['full_name']) {
                            
                        $sql = $db->prepare("UPDATE users SET full_name = ? WHERE id = ?");
                        $result = $sql->execute(array($name, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($email) AND !empty($email) AND $email != $user['email']) {
                            
                        $sql = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
                        $result = $sql->execute(array($email, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($phone) AND !empty($phone) AND $phone != $user['phone_number']) {
                            
                        $sql = $db->prepare("UPDATE users SET phone_number = ? WHERE id = ?");
                        $result = $sql->execute(array($phone, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($password) AND !empty($password) AND $password != sha1($user['password'])) {
                        
                        $pwd = sha1($password);
                            
                        $sql = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $result = $sql->execute(array($pwd, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($success)) {
                        header("Location: users.php");
                    }

                }
            }
            else {
                header("Location: users.php");
            }
        }
        else {
             header("Location: users.php");
        }
?>
<!DOCTYPE html>
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
                <lia href="items.php">Items</a></li>
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
            <h3 class="login-title">Update User</h3>
            <form method="post" enctype="multipart/form-data">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="name">Name:</label>
                    <input type="name" name="name" value="<?= $user['full_name'] ?>" placeholder="Type your name here">
                </div>

                <div class="email-section">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?= $user['email'] ?>" placeholder="Type your email here">
                </div>
                
                <div class="email-section">
                    <label for="phone">Phone number:</label>
                    <input type="number" name="phone" value="<?= $user['phone_number'] ?>" placeholder="Type your phone number here">
                </div>

                <div class="password-section">
                    <label for="password">Password:</label>
                    <input type="password" name="password" placeholder="Type default password here">
                </div>

                <div class="btn-section">
                    <input class="login-btn" name="save" type="submit" value="Update">
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