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
        
        $list = $db->prepare("SELECT 
        organisations.name, 
        wishlists.id, wishlists.title, 
        wishlists.description, 
        wishlists.quantity, 
        wishlists.created_at 
        FROM 
        wishlists 
        JOIN organisations ON wishlists.organisation_id = organisations.id
        ORDER BY id DESC");
        $list->execute();
    

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
                <li><a href="items.php">Items</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="organisations.php">Organisations</a></li>
                <li class="active"><a href="wishlists.php">Wishlists</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><?= $current['full_name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    
    <section class="homeSection">
        
         <div class="box2">
            <h3 class="login-title">All Wishlists</h3>
            
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
                      <a href="edit_wishlist.php?id=<?= $wish['id'] ?>">edit</a>
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