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
        
        $id = $_GET['id'];
        
        if(isset($id) AND !empty($id)) {
            $wish_check = $db->prepare("SELECT * FROM wishlists WHERE id = ?");
            $wish_check->execute(array($id));
            
            if($wish_check->rowCount() > 0) {
                $wish = $wish_check->fetch();
        
                if(isset($_POST['save'])) {
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $quantity = $_POST['quantity'];
                    
                    $success = null;
                    
                    if(isset($title) AND !empty($title) AND $title != $wish['title']) {
                            
                        $sql = $db->prepare("UPDATE wishlists SET title = ? WHERE id = ?");
                        $result = $sql->execute(array($title, $id));
                                    
                        if($result){
                            $success = 1; 
                        }else{
                            $error = 'There were errors while saving the data.';
                        }
                    }
                    
                    if(isset($description) AND !empty($description) AND $description != $wish['description']) {
                            
                        $sql = $db->prepare("UPDATE wishlists SET description = ? WHERE id = ?");
                        $result = $sql->execute(array($description, $id));
                                    
                        if($result){
                            $success = 1; 
                        }else{
                            $error = 'There were errors while saving the data.';
                        }
                    }
                    
                    if(isset($quantity) AND !empty($quantity) AND $quantity != $wish['quantity']) {
                            
                        $sql = $db->prepare("UPDATE wishlists SET quantity = ? WHERE id = ?");
                        $result = $sql->execute(array($quantity, $id));
                                    
                        if($result){
                            $success = 1; 
                        }else{
                            $error = 'There were errors while saving the data.';
                        }
                    }
                    
                    if(isset($success)) {
                        header("Location: wishlist.php");
                    }
   
                }
            }
            else {
                header("Location: wishlist.php");
            }
        }
        else {
             header("Location: wishlist.php");
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
                <li><a href="index.html">Home</a></li>
                <li class="active"><a href="wishlist.php">Wishlists</a></li>
                <li><a href="org_donations.php">Donations</a></li>
                <li><?= $current['name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    
    
    <section class="homeSection">
        
        <div class="box">
            <h3 class="login-title">Edit Wishlist</h3>
            <form method="post">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label>Title:</label>
                    <input type="text" name="title"  value="<?= $wish['title'] ?>">
                </div>
                
                <div class="email-section">
                    <label>Description:</label>
                    <textarea name="description" rows="7"><?= $wish['description'] ?></textarea>
                </div>
                
                <div class="email-section">
                    <label>Quantity (Target):</label>
                    <input type="text" name="quantity" value="<?= $wish['quantity'] ?>">
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