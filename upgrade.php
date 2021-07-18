<?php
session_start();
require_once('db.php');

if(isset($_SESSION['auth']))
{
    $user = $_SESSION['auth'];

    if ($user['is_admin']) {
        
        if(isset($_GET['id'])){
            $id = $_GET['id'];

            $check = $db->prepare("SELECT * FROM users WHERE id = ?");
            $check->execute(array($id));
            if($check->rowCount() > 0) {

                $user = $db->prepare("UPDATE users SET is_admin = 1 WHERE id = ?");
                $user->execute(array($id));
                
                header("Location: users.php");
            }
            else
            {
                echo "The organisation with this ID wasn't found !";
            }
        }
        else {
            header("Location: users.php");
        }
    }
    else {
        echo "Only the admin is autorized to access this page !";
    }
}
else {
    header("Location: login.php");
}
?>