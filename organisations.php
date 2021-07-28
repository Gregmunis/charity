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
        
        $list = $db->prepare("SELECT * FROM organisations ORDER BY id DESC");
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
                <li class="active"><a href="organisations.php">Organisations</a></li>
                <li><a href="wishlists.php">Wishlists</a></li>
                <li><a href="donations.php">Donations</a></li>
                <li><?= $current['full_name'] ?> <a class="login" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <section class="homeSection">
        <div class="box2">
            <h3 class="login-title">All Organisations</h3>
            <div>
                <a class="add_btn" href="new_org.php">New Organisation</a>
            </div>
            
            <table id="items">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Address</th>
                  <th>Description</th>
                  <th>Paybill</th>
                  <th>Account No</th>
                  <th>Evidence</th>
                  <th>Date joined</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  if($list->rowCount() > 0) {
                  while($org = $list->fetch()) {
                  ?>
                <tr>
                  <td><?= $org['id'] ?></td>
                  <td><?= $org['name'] ?></td>
                  <td><?= $org['email'] ?></td>
                  <td><?= $org['phone_number'] ?></td>
                  <td><?= $org['address'] ?></td>
                  <td><?= $org['description'] ?></td>
                  <td><?= $org['paybill_no'] ?></td>
                  <td><?= $org['account_no'] ?></td>
                  <td> 
                    <a href="org_certificates/<?= $org['evidence'] ?>" target="_blank"><img width="50" src="org_certificates/<?= $org['evidence'] ?>"/> </a> 
                  </td>
                  <td><?= $org['joined_date'] ?></td>
                  <td>
                    <?php 
                    if ($org['is_active']) { ?>
                        <span>Active</span>
                        <a href="block.php?id=<?= $org['id'] ?>">block</a>
                        <a href="edit_org.php?id=<?= $org['id'] ?>">edit</a>
                    <?php } else { ?>
                        <a href="approve.php?id=<?= $org['id'] ?>">approve</a>
                    <?php } ?>
                    <a href="delete_org.php?id=<?= $org['id'] ?>">delete</a>
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