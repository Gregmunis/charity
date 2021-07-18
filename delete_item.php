<?php
session_start();
require_once('db.php');

if(isset($_SESSION['auth']))
{
    $user = $_SESSION['auth'];

    if ($user['is_admin']) {
        
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            
            $check_item = $db->prepare("SELECT * FROM items WHERE id = ?");
            $check_item->execute(array($id));
            if($check_item->rowCount() > 0) {
            
                $item = $db->prepare("DELETE FROM items WHERE id = ?");
                $item->execute(array($id));
                
                header("Location: items.php");
            }
            else
            {
                echo "The Item with this ID wasn't found !";
            }
        }
        else {
            header("Location: items.php");
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