<?php 
require_once('db.php');
session_start();

$list = $db->prepare("SELECT * FROM organisations WHERE is_active = 1 ORDER BY id DESC");
$list->execute();
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

                <?php
                if(isset($_SESSION['auth'])){ 
                $user = $_SESSION['auth']; 
                ?>
                <li><?php if($user['full_name']) { echo $user['full_name']; } ?> <a class="login" href="logout.php">Logout</a></li>
                <?php } else { ?>
                <li><a class="login" href="login.php">Login</a></li>
                <?php } ?>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        <h3>Support Us</h3>
        
        <div class="org-box" style="display:flex; flex-wrap: wrap; width: 90%; justify-content: space-between">
            <?php
                if($list->rowCount() > 0) {
                  while($org = $list->fetch()) {
            ?>
            
            <div class="org" style="width:45%; margin: 20px 0px">
                <div class="logo-org" style="width: 80%; background: #aaa; padding: 50px; display:flex; justify-content:center; align-items:center">
                    <img style="height:250px" src="org_logos/<?= $org['logo'] ?>"/>
                </div>
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
                <a href="donate.php?id=<?= $org['id'] ?>" class="login">Donate</a>
            </div>
                  
            <?php } } else { ?>
                <p>
                    No Organisation found!
                </p>
            <?php } ?>
        </div>
    </section>
    <footer>
        <p>&copy; 2021. Charity, All rights reserved.</p>
    </footer>
</body>
</html>