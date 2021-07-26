<?php 

function validate($name, $email, $phone, $password, $password_confirm) {
    require('db.php');

    if(isset($name) AND empty($name)) {
        echo "<script> alert('name should not be empty') </script>";
    }

    if(isset($email) AND !empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script> alert('email not valid') </script>";
        }
    }
    else {
        echo "<script> alert('email should not be empty') </script>";
    }

    if(isset($phone) AND !empty($phone)) {
        $check_phone = $db->prepare("SELECT * FROM users WHERE phone_number = ?");
        $check_phone->execute(array($phone));
        if($check_phone->rowCount() > 0) {
            echo "<script> alert('This phone number already exist') </script>";
        }
    }
    else {
        echo "<script> alert('phone should not be empty') </script>";
    }

    if(isset($password) AND !empty($password)) {
        if (!preg_match('~[0-9]+~', $password)) {
            echo "<script> alert('The password should contain a number') </script>";
        }
    }
    else {
        echo "<script> alert('password should not be empty') </script>";
    }

    if(isset($$password_confirm) AND !empty($$password_confirm)) {
        if ( $password != $password_confirm) {
            echo "<script> alert('The password doesn't match') </script>";
        }
    }
    else {
        echo "<script> alert('Repeat password should not be empty') </script>";
    }
}

?>