<?php
session_start();
require_once('db.php');

// check if the user is logged in
if(isset($_SESSION['auth']))
{
    $user = $_SESSION['auth'];
    
    // Check if the current user is the admin
    if (!$user['is_admin']) {
        
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            
            // Check if this donation exist before updating...
            $check = $db->prepare("SELECT * FROM donations WHERE id = ?");
            $check->execute(array($id));
            if($check->rowCount() > 0) {
            
                // Since donation exist, Update
                $donation = $db->prepare("UPDATE donations SET is_received = 1 WHERE id = ?");
                $donation->execute(array($id));
                
                header("Location: org_donations.php");
            }
            else
            {
                echo "The Donation with this ID wasn't found !";
            }
        }
        else {
            header("Location: org_donations.php");
        }
    }
    else {
        echo "You are not autorized to access this page !";
    }
}
else {
    header("Location: login.php");
}
?>