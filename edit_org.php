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
            $check_org = $db->prepare("SELECT * FROM organisations WHERE id = ?");
            $check_org->execute(array($id));
            if($check_org->rowCount() > 0) {
                $org = $check_org->fetch();
                
                if(isset($_POST['save'])) {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $address = $_POST['address'];
                    $description = $_POST['description'];
                    $paybill_no = $_POST["paybill_no"];
                    $account_no = $_POST["account_no"];
                    $password = $_POST['password'];
                            
                    $success = null;
                    
                    if(isset($name) AND !empty($name) AND $name != $org['name']) {
                            
                        $sql = $db->prepare("UPDATE organisations SET name = ? WHERE id = ?");
                        $result = $sql->execute(array($name, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($email) AND !empty($email) AND $email != $org['email']) {
                            
                        $sql = $db->prepare("UPDATE organisations SET email = ? WHERE id = ?");
                        $result = $sql->execute(array($email, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($phone) AND !empty($phone) AND $phone != $org['phone_number']) {
                            
                        $sql = $db->prepare("UPDATE organisations SET phone_number = ? WHERE id = ?");
                        $result = $sql->execute(array($phone, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }

                    if(isset($address) AND !empty($address) AND $address != $org['address']) {
                            
                        $sql = $db->prepare("UPDATE organisations SET address = ? WHERE id = ?");
                        $result = $sql->execute(array($address, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($description) AND !empty($description) AND $description != $org['description']) {
                            
                        $sql = $db->prepare("UPDATE organisations SET description = ? WHERE id = ?");
                        $result = $sql->execute(array($description, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($paybill_no) AND !empty($paybill_no) AND $paybill_no != $org['paybill_no']) {
                            
                        $sql = $db->prepare("UPDATE organisations SET paybill_no = ? WHERE id = ?");
                        $result = $sql->execute(array($paybill_no, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($account_no) AND !empty($account_no) AND $account_no != $org['account_no']) {
                            
                        $sql = $db->prepare("UPDATE organisations SET account_no = ? WHERE id = ?");
                        $result = $sql->execute(array($account_no, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($password) AND !empty($password) AND $password != sha1($org['password'])) {
                        
                        $pwd = sha1($password);
                            
                        $sql = $db->prepare("UPDATE organisations SET password = ? WHERE id = ?");
                        $result = $sql->execute(array($pwd, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($success)) {
                        header("Location: organisations.php");
                    }

                }
            }
            else {
                header("Location: organisations.php");
            }
        }
        else {
             header("Location: organisations.php");
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
                <li><a href="users.php">Users</a></li>
                <li class="active"><a href="organisations.php">Organisations</a></li>
                <li><a href="wishlists.php">Wishlists</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><?= $current['full_name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        <div class="box">
            <h3 class="login-title">Update Organisation</h3>
            <form method="post" enctype="multipart/form-data">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="name">Name:</label>
                    <input type="name" name="name" value="<?= $org['name'] ?>" placeholder="Type your name here">
                </div>

                <div class="email-section">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?= $org['email'] ?>" placeholder="Type your email here">
                </div>
                
                <div class="email-section">
                    <label for="phone">Phone number:</label>
                    <input type="number" name="phone" value="<?= $org['phone_number'] ?>" placeholder="Type your phone number here">
                </div>

                <div class="email-section">
                    <label for="address">Address:</label>
                    <input type="text" name="address" value="<?= $org['address'] ?>"  placeholder="Type your address here">
                </div>
                
                <div class="email-section">
                    <label for="description">Description:</label>
                    <textarea name="description" rows="7" placeholder="Type the Organisation description here"><?= $org['description'] ?></textarea>
                </div>
                
                <div class="email-section">
                    <label>Paybill number:</label>
                    <input type="number" name="paybill_no" value="<?= $org['paybill_no'] ?>" placeholder="Type your Paybill number here">
                </div>
                
                <div class="email-section">
                    <label>Account number:</label>
                    <input type="text" name="account_no" value="<?= $org['account_no'] ?>" placeholder="Type your Account number here">
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