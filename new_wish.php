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
        
        if(isset($_POST['save'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $quantity = $_POST['quantity'];
            
            if(isset($title) AND !empty($title) AND isset($description) AND !empty($description) AND isset($quantity) AND !empty($quantity)) {
                $check_title = $db->prepare("SELECT * FROM wishlists WHERE title = ?");
                $check_title->execute(array($title));
                if($check_title->rowCount() == 0) {
                    
                    $sql = $db->prepare("INSERT INTO wishlists(organisation_id, title, description, quantity) VALUES(?,?,?,?)");
                    $result = $sql->execute(array($current['id'], $title, $description, $quantity));
                            
                    if($result){
                        header("Location: wishlist.php");
                    }else{
                        $error = 'There were errors while saving the data.';
                    }
                }
                else{
                    $error = "This Item already exist";
                }
            }
            else {
                $error = "All fields are required!";
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
                <li><a href="index.html">Home</a></li>
                <li class="active"><a href="wishlist.php">Wishlists</a></li>
                <li><a href="org_donations.php">Donations</a></li>
                <li><?= $current['name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    
    <?php
        if($current['logo']) {
    ?>
    
    <section class="homeSection">
        
        <div class="box">
            <h3 class="login-title">New Wishlist</h3>
            <form method="post">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="name">Title:</label>
                    <input type="text" name="title"  placeholder="Type Item name here">
                </div>
                
                <div class="email-section">
                    <label for="description">Description:</label>
                    <textarea name="description" rows="7"  placeholder="Type the Item description here"></textarea>
                </div>
                
                <div class="email-section">
                    <label for="name">Quantity (Target):</label>
                    <input type="text" name="quantity"  placeholder="Quantity Targetted">
                </div>
                
                <div class="btn-section">
                    <input class="login-btn" name="save" type="submit" value="Save">
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