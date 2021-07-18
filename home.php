<?php
require_once('db.php');

session_start();
if(isset($_SESSION['auth'])){
    
    $user = $_SESSION['auth']; 
    
    if(!$user['is_admin']) {
    
    $req = $db->prepare("SELECT * FROM organisations WHERE id = ?");
    $req->execute(array($user['id']));
    $current = $req->fetch();
    
    
    if ($current['is_active']) {
        
        if (isset($_POST['save'])) {
            $logo = $_FILES["logo"];
            
            if(isset($logo) AND !empty($logo)) {
            
                $errors= array();
                $file_name = $_FILES['logo']['name'];
                $file_size =$_FILES['logo']['size'];
                $file_tmp =$_FILES['logo']['tmp_name'];
                $file_type=$_FILES['logo']['type'];
                $file_ext=strtolower(end(explode('.',$file_name)));
              
                $extensions= array("jpeg","jpg","png");
              
                if(in_array($file_ext,$extensions)=== false){
                    $error = "extension not allowed, please choose a JPEG or PNG file.";
                }
              
                if($file_size > 2097152){
                    $error = 'File size must be excately 2 MB';
                }
                
                if(empty($errors)==true){
                    move_uploaded_file($file_tmp,"org_logos/".$file_name);
                                 
                    $organisation = $db->prepare("UPDATE organisations SET logo = ? WHERE id = ?");
                    $result = $organisation->execute(array($file_name, $current['id']));
                                    
                    if($result){
                        header("Location: home.php");
                    }else{
                        $error = 'There were errors while saving the data.';
                    }
                }else{
                    print_r($errors);
                }
            } else {
                $error = "No file selected, select one to continue";
            }
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
                <li class="active"><a href="index.html">Home</a></li>
                <li><a href="wishlist.php">Wishlists</a></li>
                <li><a href="org_donations.php">Donations</a></li>
                <li><?= $current['name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    
    <?php
        if($current['logo']) {
    ?>
    
    <section class="homeSection">
        login as: 
        <br><br>
        <img width="100" src="org_logos/<?= $current['logo'] ?>"/>
        <h3><?= $current['name'] ?></h3>

        email: <h3><?= $current['email'] ?></h3>

        phone: <h3><?= $current['phone_number'] ?></h3>
    </section>
    
    <?php } else { ?>
    
    <section class="homeSection">
        <div class="box">
            <h3 class="login-title">Upload logo to continue</h3>
            <form method="post" enctype="multipart/form-data">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="logo">Select logo:</label>
                    <input type="file" name="logo">
                </div>
                
                <div class="btn-section">
                    <input class="login-btn" name="save" type="submit" value="Continue">
                </div>
            </form>
        </div>

    </section>
    
    <?php } ?>
    <footer>
        <p>&copy 2021. Charity, All rights reserved.</p>
    </footer>
</body>
</html>

<?php 
        }
        else {
        header("Location: logout.php");
        }
    }
    else {
    header("Location: dashboard.php");
    }
}
else {
    header("Location: login.php");
}
?>