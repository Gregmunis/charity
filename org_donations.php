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
        
        $list = $db->prepare("SELECT users.full_name, organisations.name, items.item_name, quantity, donations.id, donations.is_received, donations.created_at FROM donations JOIN users ON donations.user_id = users.id JOIN organisations ON donations.organisation_id = organisations.id JOIN items ON donations.item_id = items.id WHERE organisation_id = ?");
        $list->execute(array($current['id']));
    

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
                <li class="active"><a href="org_donations.php">Donations</a></li>
                <li><?= $current['name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    
    <section class="homeSection">
        <div class="box2">
            <h3 class="login-title">All Donations</h3>
            
            <table id="items">
              <thead>
                <tr>
                  <th>Doner</th>
                  <th>Item</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  if($list->rowCount() > 0) {
                  while($donnation = $list->fetch()) {
                  ?>
                <tr>
                  <td><?= $donnation['full_name'] ?></td>
                  <td><?= $donnation['item_name'] ?></td>
                  <td><?= $donnation['quantity'] ?></td>
                  <td><?= $donnation['created_at'] ?></td>
                  <td>
                    <?php 
                    if ($donnation['is_received']) { ?>
                        <span>Received</span>
                    <?php } else { ?>
                        <a href="acknowledge.php?id=<?= $donnation['id'] ?>" class="login">acknowledge</a>
                    <?php } ?>
                  </td>
                </tr>
                <?php } } else { ?>
                <tr>
                    <td>No item found!</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            
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