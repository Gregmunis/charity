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
            $wish_check = $db->prepare("SELECT 
            organisations.name, 
            wishlists.id, wishlists.title, 
            wishlists.description, 
            wishlists.quantity, 
            wishlists.created_at 
            FROM 
            wishlists 
            JOIN organisations ON wishlists.organisation_id = organisations.id
            WHERE wishlists.id = ?
            ORDER BY id DESC");
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
                        header("Location: wishlists.php");
                    }
   
                }
            }
            else {
                header("Location: wishlists.php");
            }
        }
        else {
             header("Location: wishlists.php");
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
                <li class="active"><a href="items.php">Items</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="organisations.php">Organisations</a></li>
                <li><a href="wishlists.php">Wishlists</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><?= $current['full_name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        
        <div class="box">
            <span>Edit Wishlist for: <h1 class="login-title"><?= $wish['name'] ?></h1></span>
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
    header("Location: home.php");
    }
}
else {
    header("Location: login.php");
}
?>