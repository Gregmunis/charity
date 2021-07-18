<?php 
require_once('db.php');
session_start();

if(isset($_SESSION['auth'])){
    
    $user = $_SESSION['auth']; 
    
    $req = $db->prepare("SELECT * FROM users WHERE id = ?");
    $req->execute(array($user['id']));
    $current = $req->fetch();

    if(isset($_GET['id'])){
        $id = $_GET['id'];
                
        // Check if this organisation exist before updating...
        $check = $db->prepare("SELECT * FROM organisations WHERE id = ?");
        $check->execute(array($id));
        if($check->rowCount() > 0) {
            $org = $check->fetch();
            $items = $db->prepare("SELECT * FROM items ORDER BY id DESC");
            $items->execute();
            
            if(isset($_POST['donate'])) {
                $item = $_POST['item'];
                $amount = $_POST['amount'];
            
                if(isset($item) AND !empty($item) AND isset($amount) AND !empty($amount)) {
                                    
                    $sql = $db->prepare("INSERT INTO donations(user_id, organisation_id, item_id, quantity) VALUES(?,?,?,?)");
                    $result = $sql->execute(array($current['id'], $org['id'], $item, $amount));
                                        
                    if($result){
                        $success = 'Thank you for changing lives by donating';
                    }else{
                        $error = 'There were errors while saving the data.';
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
                <li><a href="index.php">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="wish.php">Our Wishlist</a></li>
                <li class="active"><a href="support.php">Support Us</a></li>
                <li><?= $current['full_name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        <div class="box">
        <h3 class="login-title">Donate to <?= $org['name'] ?></h3>
        
        <div>
            <img width="100" src="org_logos/<?= $org['logo'] ?>"/>
            <p><?= $org['description'] ?></p>
                <p>
                    Email: <?= $org['email'] ?>
                    <br>
                    Phone: <?= $org['phone_number'] ?>
                    <br>
                    Paybill Number: <?= $org['paybill_no'] ?>
                    <br>
                    Account Number: <?= $org['account_no'] ?>
                </p>
        </div>
        
            <form method="post">
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
                    <label for="logo">Select Item :</label>
                    <select name="item">
                        <?php
                        while($item = $items->fetch()) {
                        ?>
                        <option value="<?= $item['id'] ?>"><?= $item['item_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="email-section">
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount" placeholder="Type the amount you would like to donate">
                </div>
                
                <div class="btn-section">
                    <input class="login-btn" name="donate" type="submit" value="Donate">
                </div>
            </form>
        
        </div>
    </section>
    <footer>
        <p>&copy; 2021. Charity, All rights reserved.</p>
    </footer>
</body>
</html>

<?php 
        }
        else {
            header("Location: support.php");
        }
    } else {
        header("Location: support.php");
    }
}
else {
    header("Location: login.php");
}
?>