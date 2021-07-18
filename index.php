<?php 
session_start();
?>
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
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="wish.php">Our Wishlist</a></li>
                <li><a href="support.php">Support Us</a></li>

                <?php
                if(isset($_SESSION['auth'])){ 
                $user = $_SESSION['auth']; 
                ?>
                <li><?php if($user['full_name']) { echo $user['full_name']; } if($user['name']) { echo $user['name']; } if($user['username']) { echo $user['username']; } ?> <a class="login" href="logout.php">Logout</a></li>
                <?php } else { ?>
                <li><a class="login" href="login.php">Login</a></li>
                <?php } ?>
            </ul>
        </div>
    </header>
    <section>
        <div class="land">
            <div class="intro">
                <h1>Start Making difference today</h1>
            </div>
            <a class="support-link" href="support.php">Support us</a>
        </div>
        <div class="mission">
            <div class="goal">
                <h2 class="m-title">Our Mission</h2>
                <p class="p-title">Our Goal, Vision & commitment</p>
            </div>
            <div class="events">
                <h2 class="m-title">Our Events</h2>
                <p class="p-title">Register & Help Make Change</p>
            </div>
            
            <div class="involve">
                <h2 class="m-title">Get Involved</h2>
                <p class="p-title">Volunteer, Participate, or Donate</p>
            </div>
        </div>

        <div class="quote">
            <h2>“Transforming the world with love”</h2>
            <p>Renford Jeffery, R.N</p>
        </div>

        <div class="support">
            <div class="join">
                <h1 class="join-title">Join Us</h1>
                <p class="p-title">Change a life today by supporting us</p>
                <a class="support-link" href="support.php">Support us</a>
            </div>

            <div class="about-img">
                <!-- <img  src="images/kids.jpg" alt="Kids"> -->
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2021. Charity, All rights reserved.</p>
    </footer>
</body>
</html>