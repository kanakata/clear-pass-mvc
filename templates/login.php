<?php

use App\Controllers\Api_controller;

$action_status = "";
$error = "";
$page_title = "login";

//controller
if (isset($_POST['login'])) {
    $action_status = Api_controller::Login()['message'];
}

require_once ROOT . "/require/header.php";
?>

<nav>
    <h2><img src="./assets/icons/login.png" alt="">Log in page</h2>
    <div class="links">
        <div class="mode">
            <div class="mode_set"></div>
        </div>
        <div class="sun_moon">
            <img src="" alt="" class="weather">
        </div>
        <a href="/register"><img src="./assets/icons/login.png" alt="">Sign up</a>
        <a href="#footer"><img src="./assets/icons/customer-service.png" alt="">contact us</a>
    </div>
</nav>

<h2 class="welcome">Hii👋there welcome to chebisaas school clearance site. log into your account bellow ⬇️.</h2>

<header>
    <div class="ui_interface">
        <form action="#" method="post" enctype="application/x-www-form-urlencoded">
            <div class="icon">

                <h2><span></span>log me in<span></span></h2>
            </div>
            <label for="name" class="name">
                <label class="f-name">
                    <img src="./assets/icons/user.png" alt="user">
                    <input type="text" placeholder="first name" name="firstname">
                </label>
                <label class="l-name">
                    <img src="./assets/icons/user.png" alt="user">
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
                <input type="password" placeholder="password" name="password">
            </label>
            <input type="submit" value="log me in" name="login">
            <div class="icon">
                <h3>encountering any problems? contact us for help<a href="tel:0793317819">0793317819</a></h3>
                <h3>dont heve an account sign up <a href="/register"> sign up <img src="./assets/icons/login.png" alt=""></a></h3>
            </div>
        </form>
        <img src="./assets/icons/school.jpeg" alt="school pic" class="school_pic" loading="lazy">
    </div>
</header>

<?php if (!empty($action_status)): ?>
    <div class="alert" style="display: <?php echo "block" ?>;">
        <div class="alert_title">alert</div>
        <div class="close">close<img src="./assets/icons/x.svg" alt=""></div>
        <div class="alert_message"><?= $action_status ?></div>
    </div>
<?php endif; ?>

<footer id="footer">
    <h2>&copy All rights reserved by <a href="">pegpem.com</a></h2>
    <h2>Designed and developed by pegpem.com</h2>
    <a href="tel:0793317819">Telephone text: 0793317819</a>
</footer>

<?php require_once ROOT . "/require/footer.php" ?>