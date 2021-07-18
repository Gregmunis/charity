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
        
        $list = $db->prepare("SELECT 
        organisations.name, 
        wishlists.id, wishlists.title, 
        wishlists.description, 
        wishlists.quantity, 
        wishlists.created_at 
        FROM 
        wishlists 
        JOIN organisations ON wishlists.organisation_id = organisations.id WHERE organisation_id = ?
        ORDER BY id DESC");
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
        
        <div class="box2">
            <h3 class="login-title">All Wishlists</h3>
            <div>
                <a class="add_btn" href="new_wish.php">Add to wishlist</a>
            </div>
            
            <table id="items">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Organisation</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>Date Published</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  if($list->rowCount() > 0) {
                  while($wish = $list->fetch()) {
                  ?>
                <tr>
                  <td><?= $wish['id'] ?></td>
                  <td><?= $wish['name'] ?></td>
                  <td><?= $wish['title'] ?></td>
                  <td><?= $wish['description'] ?></td>
                  <td><?= $wish['quantity'] ?></td>
                  <td><?= $wish['created_at'] ?></td>
                  <td>
                      <a href="edit_wish.php?id=<?= $wish['id'] ?>">edit</a>
                      <a href="delete_wish.php?id=<?= $wish['id'] ?>">delete</a>
                    </td>
                </tr>
                <?php } } else { ?>
                <tr>
                    No item found!
                </tr>
                <?php } ?>
              </tbody>
            </table>
            
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