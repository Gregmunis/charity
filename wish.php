<?php 
require_once('db.php');
session_start();

// $list = $db->prepare("SELECT * FROM organisations WHERE is_active = 1 ORDER BY id DESC");
// $list->execute();

$list = $db->prepare("SELECT 
        organisations.name,
        organisations.email,
        organisations.logo,
        organisations.phone_number,
        organisations.paybill_no,
        organisations.account_no,
        wishlists.id, wishlists.title, 
        wishlists.description, 
        wishlists.quantity, 
        wishlists.created_at 
        FROM 
        wishlists 
        JOIN organisations ON wishlists.organisation_id = organisations.id
        ORDER BY id DESC");
$list->execute();

if(isset($_POST['donate'])) {

    if(isset($_SESSION['auth'])){
    
        $user = $_SESSION['auth']; 
        
        $req = $db->prepare("SELECT * FROM users WHERE id = ?");
        $req->execute(array($user['id']));
        $current = $req->fetch();

        $amount = $_POST['amount'];
        $org_id = $_POST['org_id'];

        if(isset($amount) AND !empty($amount)) {
                            
            $sql = $db->prepare("INSERT INTO donations(user_id, organisation_id, quantity) VALUES(?,?,?)");
            $result = $sql->execute(array($current['id'], $org_id, $amount));
                                
            if($result){
                echo "<script>alert('Thank you for your donation.')</script>";
            }else{
                $error = 'There were errors while saving the data.';
            }

        } else {
            $error = "All the fields are required";
        }
    }
    else {
        $error = "Please Login or register to continue";
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
                <li class="active"><a href="wish.php">Our Wishlist</a></li>
                <li><a href="support.php">Support Us</a></li>

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
        <h3>Our Wishlist</h3>
        
        <div class="org-box" style="display:flex; flex-wrap: wrap; width: 90%; justify-content: space-between">
            <?php
                if($list->rowCount() > 0) {
                  while($org = $list->fetch()) {
            ?>
            
            <div class="org" style="width:45%; margin: 20px 0px">
                <div class="logo-org" style="display: flex; align-items: flex-start">
                    <div>
                        <img style="width:80px" src="org_logos/<?= $org['logo'] ?>"/>
                    </div>
                    
                    <div>
                        <h3><?= $org['title'] ?></h3>
                        <p><?= $org['description'] ?></p>
                        <p>Target: <?= $org['quantity'] ?></p>
                        <p>
                            Email: <?= $org['email'] ?>
                            <br>
                            Phone: <?= $org['phone_number'] ?>
                            <br>
                            Paybill Number: <?= $org['paybill_no'] ?>
                            <br>
                            Account Number: <?= $org['account_no'] ?>
                        </p>

                        <form method="post">
                            <?php if(isset($error)) {
                                ?>
                                <div class="error">
                                    <strong><?= $error ?></strong>
                                </div>
                            <?php } ?>

                            <input type="hidden" name="org_id" value="<?= $org['id'] ?>">
                            
                            <div class="email-section">
                                <label for="amount">Amount:</label>
                                <input type="number" name="amount" placeholder="Type the amount you would like to donate">
                            </div>
                            
                            <div class="btn-section">
                            <input class="login" name="donate" type="submit" value="Donate">
                            </div>
                        </form>
                        
                    </div>
                    
                </div>
                
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