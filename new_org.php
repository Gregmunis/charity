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
    $description = $_POST['description'];
    $evidence = $_FILES["evidence"];
    $paybill_no = $_POST["paybill_no"];
    $account_no = $_POST["account_no"];
    $password = $_POST['password'];

    if(isset($name) AND !empty($name) AND
    isset($email) AND !empty($email) AND
    isset($phone) AND !empty($phone) AND
    isset($evidence) AND !empty($evidence) AND
    isset($password) AND !empty($password) AND
    isset($description) AND !empty($description) AND
    isset($paybill_no) AND !empty($paybill_no) AND
    isset($account_no) AND !empty($account_no)) {
            
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
                                 $pwd = sha1($password);
                                 
                                    $sql = $db->prepare("INSERT INTO organisations(name, email, phone_number, evidence, paybill_no, account_no, description, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
                                    $result = $sql->execute(array($name, $email, $phone, $file_name, $paybill_no, $account_no, $description, $pwd));
                                    
                                    if($result){
                                        header("Location: organisations.php");
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
            <h1 class="login-title">Add new organisation</h1>
            <form method="post" enctype="multipart/form-data">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
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