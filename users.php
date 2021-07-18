<?php
require_once('db.php');

session_start();
if(isset($_SESSION['auth'])){
    
    $user = $_SESSION['auth']; 
    $req = $db->prepare("SELECT * FROM users WHERE id = ?");
    $req->execute(array($user['id']));
    $current = $req->fetch();
    if ($current['is_admin']) {
        
        $list = $db->prepare("SELECT * FROM users ORDER BY id DESC");
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
                <li class="active"><a href="users.php">Users</a></li>
                <li><a href="organisations.php">Organisations</a></li>
                <li><a href="wishlists.php">Wishlists</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><?= $current['full_name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        <div class="box2">
            <h3 class="login-title">All Users</h3>
            <div>
                <a class="add_btn" href="new_user.php">New User</a>
            </div>
            
            <table id="items">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Date joined</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  if($list->rowCount() > 0) {
                  while($user = $list->fetch()) {
                  ?>
                <tr>
                  <td><?= $user['id'] ?></td>
                  <td><?= $user['full_name'] ?></td>
                  <td><?= $user['email'] ?></td>
                  <td><?= $user['phone_number'] ?></td>
                  <td><?= $user['joined_date'] ?></td>
                  <td>
                        <?php 
                        if ($user['is_admin']) { ?>
                            <span>Admin</span>
                        <?php } else { ?>
                            <a href="upgrade.php?id=<?= $user['id'] ?>">Change to admin</a>
                        <?php } ?>
                        <a href="edit_user.php?id=<?= $user['id'] ?>">edit</a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>">delete</a>
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