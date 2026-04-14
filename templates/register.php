<?php
$error = "";
$page_title = "register";
?>

<?php require_once ROOT . "/require/header.php" ?>

<nav>
    <h2><img src="./assets/icons/login.png" alt="">Sign up page</h2>
    <div class="links">
        <div class="mode">
            <div class="mode_set"></div>
        </div>
        <div class="sun_moon">
            <img src="./assets/icons/sun.png" alt="" class="weather">
        </div>
        <a href="/login"><img src="./assets/icons/login.png" alt="">log in</a>
        <a href="#footer"><img src="./assets/icons/customer-service.png" alt="">contact us</a>
    </div>
</nav>

<h2 class="welcome">Hii👋there welcome to chebisaas school clearance site. Sign up for an account bellow ⬇️.</h2>

<header>
    <div class="ui_interface">
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="icon">

                <h2><span></span>sign me up<span></span></h2>
            </div>
            <label for="name" class="name">
                <label class="f-name">
                    <img src="./assets/icons/name.png" alt="user" loading="lazy">
                    <input type="text" placeholder="first name" name="firstname">
                </label>
                <label class="l-name">
                    <img src="./assets/icons/name.png" alt="user">
                    <input type="text" placeholder="last name" name="lastname">
                </label>
            </label>
            <label for="sirname">
                <img src="./assets/icons/user.png" alt="user">
                <input type="text" placeholder="sirname (leave empty if none)" name="sirname">
            </label>
            <label for="admission">
                <img src="./assets/icons/admission.png" alt="admission">
                <input type="text" placeholder="admission" name="admission">
            </label>
            <!-- <label for="profile">
                <img src="./assets/icons/user.png" alt="index">
                <input type="file" placeholder="profile picture (optional)" name="profile" >
            </label> -->
            <label for="index">
                <img src="./assets/icons/index.png" alt="index">
                <input type="text" placeholder="index (KCSE)" name="index">
            </label>
            <label for="year">
                <img src="./assets/icons/year.png" alt="year">
                <input type="text" placeholder="year OF COMPLETION" name="year">
            </label>
            <label for="password">
                <img src="./assets/icons/password.png" alt="password">
                <input type="password" placeholder="password (create a strong password & don't share it.)" name="password">
            </label>
            <label for="confirm password">
                <img src="./assets/icons/password.png" alt="confirm password">
                <input type="password" placeholder="confirm password" name="confirm_password">
            </label>

            <input type="submit" value="sign me up" name="sign">

            <div class="icon">
                <h3>encountering any problems? contact us for help<a href="tel:0793317819">0793317819</a></h3>
                <h3>already have an account log in <a href="/login"> log in <img src="./assets/icons/login.png" alt=""></a></h3>
            </div>
        </form>
        <img src="./assets/icons/school.jpeg" alt="school pic" class="school_pic" loading="lazy">
    </div>
</header>



<!-- alert for different passwords -->
<?php if ($error && $error != ""): ?>
    <div class="alert" style="display: <?php
                                        $display = "block";
                                        echo $display;
                                        ?>;">
        <div class="alert_title">alert</div>
        <a href="index.php?close=diff_pass" class="close">close<img src="./assets/icons/x.svg" alt=""></a>
        <div class="alert_message"><?php echo $error ?><button name="confirm">confirm</button></div>
    </div>
<?php endif; ?>



<footer id="footer">
    <h2>&copy All rights reserved by <a href="">pegpem.com</a></h2>
    <h2>Designed and developed by pegpem.com</h2>
    <a href="tel:0793317819">Telephone text: 0793317819</a>
</footer>
<?php require_once ROOT . "/require/footer.php" ?>