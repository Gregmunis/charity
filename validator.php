<?php 

function validate($name, $email) {

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
}

?>