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
            
            if(isset($_GET['id']) AND !empty($_GET['id'])){
                $id = $_GET['id'];
                
                // Check if this wishlist exist and belong to this organisation before deleting...
                $check = $db->prepare("SELECT * FROM wishlists WHERE id = ? AND organisation_id = ?");
                $check->execute(array($id, $current['id']));
                if($check->rowCount() > 0) {
                
                    // Since wishlist exist, Delete
                    $wish = $db->prepare("DELETE FROM wishlists WHERE id = ?");
                    $wish->execute(array($id));
                    
                    header("Location: wishlist.php");
                }
                else
                {
                    echo "The Wishlist with this ID wasn't found or you have no permission to delete this wishlist !";
                }
            }
            else {
                header("Location: wishlist.php");
            }
        }
        else {
            header("Location: logout.php");
        }
    }
    else {
        if(isset($_GET['id']) AND !empty($_GET['id'])){
                $id = $_GET['id'];
                
                // Check if this wishlist exist before deleting...
                $check = $db->prepare("SELECT * FROM wishlists WHERE id = ?");
                $check->execute(array($id));
                if($check->rowCount() > 0) {
                
                    // Since wishlist exist, Delete
                    $wish = $db->prepare("DELETE FROM wishlists WHERE id = ?");
                    $wish->execute(array($id));
                    
                    header("Location: wishlists.php");
                }
                else
                {
                    echo "The Wishlist with this ID wasn't found !";
                }
        }
        else {
            header("Location: wishlist.php");
        }
    }
}
else {
    header("Location: login.php");
}
?>