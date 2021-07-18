<?php
session_start();
require_once('db.php');

// check if the user is logged in
if(isset($_SESSION['auth']))
{
    $user = $_SESSION['auth'];
    
    // Check if the current user is the admin
    if ($user['is_admin']) {
        
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            
            // Check if this organisation exist before updating...
            $check = $db->prepare("SELECT * FROM organisations WHERE id = ?");
            $check->execute(array($id));
            if($check->rowCount() > 0) {
            
                // Since organisation exist, Update
                $organisation = $db->prepare("UPDATE organisations SET is_active = 1 WHERE id = ?");
                $organisation->execute(array($id));
                
                header("Location: organisations.php");
            }
            else
            {
                echo "The organisation with this ID wasn't found !";
            }
        }
        else {
            header("Location: organisations.php");
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