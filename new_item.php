<?php
require_once('db.php');

session_start();
if(isset($_SESSION['auth'])){
    
    $user = $_SESSION['auth']; 
    $req = $db->prepare("SELECT * FROM users WHERE id = ?");
    $req->execute(array($user['id']));
    $current = $req->fetch();
    if ($current['is_admin']) {
        
        if(isset($_POST['save'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            if(isset($name) AND !empty($name) AND isset($description) AND !empty($description)) {
                $check_name = $db->prepare("SELECT * FROM items WHERE item_name = ?");
                $check_name->execute(array($name));
                if($check_name->rowCount() == 0) {
                    
                    $sql = $db->prepare("INSERT INTO items(item_name, description) VALUES(?,?)");
                    $result = $sql->execute(array($name, $description));
                            
                    if($result){
                        header("Location: items.php");
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
            <h3 class="login-title">New Item</h3>
            <form method="post">
                <?php if(isset($error)) {
                    ?>
                    <div class="error">
                        <strong><?= $error ?></strong>
                    </div>
                <?php } ?>
                
                <div class="email-section">
                    <label for="name">Name:</label>
                    <input type="text" name="name"  placeholder="Type Item name here">
                </div>
                
                <div class="email-section">
                    <label for="description">Description:</label>
                    <textarea name="description" rows="7"  placeholder="Type the Item description here"></textarea>
                </div>
                
                <div class="btn-section">
                    <input class="login-btn" name="save" type="submit" value="Save">
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