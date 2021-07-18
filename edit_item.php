<?php
require_once('db.php');

session_start();
if(isset($_SESSION['auth'])){
    
    $user = $_SESSION['auth']; 
    $req = $db->prepare("SELECT * FROM users WHERE id = ?");
    $req->execute(array($user['id']));
    $current = $req->fetch();
    if ($current['is_admin']) {
        
        $id = $_GET['id'];
        
        if(isset($id) AND !empty($id)) {
            $check_item = $db->prepare("SELECT * FROM items WHERE id = ?");
            $check_item->execute(array($id));
            if($check_item->rowCount() > 0) {
                $item = $check_item->fetch();
                
                if(isset($_POST['save'])) {
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                            
                    $success = null;
                    
                    if(isset($name) AND !empty($name) AND $name != $item['item_name']) {
                            
                        $sql = $db->prepare("UPDATE items SET item_name = ? WHERE id = ?");
                        $result = $sql->execute(array($name, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($description) AND !empty($description) AND $description != $item['description']) {
                            
                        $sql = $db->prepare("UPDATE items SET description = ? WHERE id = ?");
                        $result = $sql->execute(array($description, $id));
                                    
                        if($result){
                            $success = 1;
                        }else{
                            $error = 'There were errors while saving the data.';
                            $success = null;
                        }
                    }
                    
                    if(isset($success)) {
                        header("Location: items.php");
                    }

                }
            }
            else {
                header("Location: items.php");
            }
        }
        else {
             header("Location: items.php");
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
            <h3 class="login-title">Update Item</h3>
            <form method="post">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?= $item['item_name'] ?>" placeholder="Type Item name here">
                </div>
                
                <div class="email-section">
                    <label for="description">Description:</label>
                    <textarea name="description" rows="7"  placeholder="Type the Item description here"><?= $item['description'] ?></textarea>
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