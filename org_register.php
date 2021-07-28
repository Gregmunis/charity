<?php
require_once('db.php');

session_start();
if(!isset($_SESSION['auth'])){

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $evidence = $_FILES["evidence"];
    $paybill_no = $_POST["paybill_no"];
    $account_no = $_POST["account_no"];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if(isset($name) AND !empty($name) AND
    isset($email) AND !empty($email) AND
    isset($phone) AND !empty($phone) AND
    isset($address) AND !empty($address) AND
    isset($evidence) AND !empty($evidence) AND
    isset($password) AND !empty($password) AND
    isset($password_confirm) AND !empty($password_confirm) AND
    isset($description) AND !empty($description) AND
    isset($paybill_no) AND !empty($paybill_no) AND
    isset($account_no) AND !empty($account_no)) {
        
        if ($password == $password_confirm) {
            $pwd = sha1($password);
            
            $errors= array();
          $file_name = $_FILES['evidence']['name'];
          $file_size =$_FILES['evidence']['size'];
          $file_tmp =$_FILES['evidence']['tmp_name'];
          $file_type=$_FILES['evidence']['type'];
          $file_ext=strtolower(end(explode('.',$_FILES['evidence']['name'])));
          
          $extensions= array("jpeg","jpg","png");
          
          if(in_array($file_ext,$extensions)=== false){
             $errors[]="extension not allowed, please choose a JPEG or PNG file.";
          }
          
          if($file_size > 2097152){
             $errors[]='File size must be excately 2 MB';
          }
            
            // $picture = $_FILES["evidence"]["name"];
            // $tempname = $_FILES["evidence"]["tmp_name"];    
            // $folder = "org_certificate/".$picture;
            
                $check_name = $db->prepare("SELECT * FROM organisations WHERE name = ?");
                $check_name->execute(array($name));
                if($check_name->rowCount() == 0) {
                    
                    $check_email = $db->prepare("SELECT * FROM organisations WHERE email = ?");
                    $check_email->execute(array($email));
                    if($check_email->rowCount() == 0) {
                        
                        $check_phone = $db->prepare("SELECT * FROM organisations WHERE phone_number = ?");
                        $check_phone->execute(array($phone));
                        if($check_phone->rowCount() == 0) {
                            
                            if(empty($errors)==true){
                                 move_uploaded_file($file_tmp,"org_certificates/".$file_name);
                                 
                                    $sql = $db->prepare("INSERT INTO organisations(name, email, phone_number, address, evidence, paybill_no, account_no, description, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    $result = $sql->execute(array($name, $email, $phone, $address, $file_name, $paybill_no, $account_no, $description, $pwd));
                                    
                                    if($result){
                                        $success = 'Registration was successfull. Go to login page or click <a href="login.php">here</a>';
                                    }else{
                                        $error = 'There were errors while saving the data.';
                                    }
                              }else{
                                 print_r($errors);
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
            <form method="post" enctype="multipart/form-data">
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
                    <input type="name" name="name" value="<?php if(isset($_POST['name'])) { echo $_POST['name']; } ?>" placeholder="Type your name here">
                </div>

                <div class="email-section">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>"  placeholder="Type your email here">
                </div>
                
                <div class="email-section">
                    <label for="phone">Phone number:</label>
                    <input type="number" name="phone" value="<?php if(isset($_POST['phone'])) { echo $_POST['phone']; } ?>"  placeholder="Type your phone number here">
                </div>

                <div class="email-section">
                    <label for="address">Address:</label>
                    <input type="text" name="address" value="<?php if(isset($_POST['address'])) { echo $_POST['address']; } ?>"  placeholder="Type your address here">
                </div>
                
                <div class="email-section">
                    <label for="description">Description:</label>
                    <textarea name="description" rows="7"  placeholder="Type the Organisation description here"><?php if(isset($_POST['description'])) { echo $_POST['description']; } ?></textarea>
                </div>

                <div class="email-section">
                    <label for="role">Upload Certificate:</label>
                    <input type="file" name="evidence">
                </div>
                
                <div class="email-section">
                    <label>Paybill number:</label>
                    <input type="number" name="paybill_no" value="<?php if(isset($_POST['paybill_no'])) { echo $_POST['paybill_no']; } ?>"  placeholder="Type your Paybill number here">
                </div>
                
                <div class="email-section">
                    <label>Account number:</label>
                    <input type="text" name="account_no" value="<?php if(isset($_POST['account_no'])) { echo $_POST['account_no']; } ?>"  placeholder="Type your Account number here">
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
                    <p>Already have an account? <a href="login.php" class="signup-link">Login</a></p>
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