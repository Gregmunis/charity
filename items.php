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
        
        $list = $db->prepare("SELECT * FROM items ORDER BY id DESC");
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
        <div class="box2">
            <h3 class="login-title">All Items</h3>
            <div>
                <a class="add_btn" href="new_item.php">New item</a>
            </div>
            
            <table id="items">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Date added</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  if($list->rowCount() > 0) {
                  while($item = $list->fetch()) {
                  ?>
                <tr>
                  <td><?= $item['id'] ?></td>
                  <td><?= $item['item_name'] ?></td>
                  <td><?= $item['description'] ?></td>
                  <td><?= $item['created_at'] ?></td>
                  <td>
                  <a href="edit_item.php?id=<?= $item['id'] ?>">edit</a>
                  <a href="delete_item.php?id=<?= $item['id'] ?>">delete</a>
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