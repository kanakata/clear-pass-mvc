<?php

use App\Controllers\view_controller;

$dept = $_GET['department'];
$page_title = $dept;
$student_data = view_controller::Display_student_data();
$info = $student_data['student_data'];
require_once ROOT . "/require/header.php";
?>
<nav>
    <?php if (isset($_GET['department'])): ?>
        <h2><img src="./assets/icons/dashboard.png" alt="">Pay <?php echo $_GET['department'] ?> online</h2>
    <?php else: ?>
        <h2><img src="./assets/icons/dashboard.png" alt="">request shipment</h2>
    <?php endif; ?>
    <div class="links">
        <div class="mode">
            <div class="mode_set"></div>
        </div>
        <div class="sun_moon">
            <img src="/assets/sun.png" alt="" class="weather">
        </div>
        <!-- <a href=""><img src="./assets/icons/back.png" alt="">Back</a> -->
    </div>
</nav>

<div class="actions">

    <?php if ($_GET['action'] == "pay_debt"): ?>
        <!-- online payments -->
        <h2 style="color: green; width: 100%; text-align: center;">Enter you phone number and password to Complete your transaction.</h2>
        <div class="forms">
            <form action="#" method="POST">
                <h2><img src="./assets/icons/online-payment.png" alt="">Make online payment</h2>
                <label for="username">
                    <img src="./assets/icons/user.png" alt="">
                    <input type="text" value="Username: <?php echo $info['username'] ?>" name="username" readonly>
                </label>
                <label for="admission">
                    <img src="./assets/icons/admission.png" alt="">
                    <input type="text" name="admission" value="Admission: <?php echo $info['admission number'] ?>" readonly>
                </label>
                <label for="phone">
                    <img src="./assets/icons/index.png" alt="">
                    <input type="text" name="phone" placeholder="phone (07000000)" required>
                </label>
                <label for="item">
                    <img src="./assets/icons/lost-items.png" alt="">
                    <input type="text" name="lost_item" value="Debt: <?php echo $info[$_GET['department'] . " debt"] ?>" readonly>
                </label>
                <label for="">
                    <img src="./assets/icons/value.png" alt="">
                    <input type="text" name="amount" value="Ksh: <?php echo $info[$_GET['department'] . " value"] ?>" readonly>
                </label>
                <!-- <label for="password">
                    <img src="./assets/icons/password.png" alt="">
                    <input type="password" name="password" placeholder="password" required>
                </label> -->
                <input type="submit" value="Make payment" name="online_payment">
            </form>
        </div>

    <?php elseif ($_GET['action'] == "request_shipment"): ?>

        <!-- shipment request and payment -->
        <div class="forms">
            <form action="#" method="post">
                <h2>Select your location.</h2>
                <label for="shipment_destination">
                    <img src="./assets/icons/name.png" alt="">
                    <select name="destination" id="">
                        <option value="select your destination">select your destination.</option>
                        <option value="mombasa">mombasa</option>
                        <option value="nairobi">nairobi</option>
                        <option value="elgeyo marakwet">elgeyo marakwet</option>
                        <option value="nakuru">nakuru</option>
                    </select>
                </label>
                <input type="submit" name="location">
            </form>
        </div>

        <div class="forms">
            <form action="#" method="post">
                <h2>Shipment form</h2>
                <label for="username">
                    <img src="./assets/icons/name.png" alt="">
                    <input type="text" value="<?php echo $info['username'] ?>" name="username" readonly>
                </label>
                <label for="admission">
                    <img src="./assets/icons/admission.png" alt="">
                    <input type="number" name="admission" value="<?php echo $info['admission'] ?>" readonly>
                </label>
                <label for="phone">
                    <img src="./assets/icons/index.png" alt="">
                    <input type="number" name="phone" placeholder="phone(07000000)" required>
                </label>
                <label for="location">
                    <img src="./assets/icons/x.svg" alt="">
                    <input type="text" name="location" value="<?php echo "shipment location: " . $destination_info['location'] ?>" readonly>
                </label>
                <label for="location">
                    <img src="./assets/icons/fast-delivery.png" alt="">
                    <input type="text" name="courier" value="<?php echo "courier: " . $destination_info['courier'] ?>" readonly>
                </label>
                <label for="location">
                    <img src="./assets/icons/online_payment.png" alt="">
                    <input type="text" name="price" value="<?php echo "shipment price: sh" . $destination_info['price'] ?>" readonly>
                </label>
                <!-- <label for="password">
                    <img src="./assets/icons/password.png" alt="">
                    <input type="password" name="password" placeholder="password" required>
                </label> -->
                <input type="submit" value="Complete shipment request" name="shipment">
            </form>
        </div>


    <?php endif; ?>
    <?php require_once ROOT . "/require/footer.php" ?>