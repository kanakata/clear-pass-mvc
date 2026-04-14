<?php

use App\Controllers\view_controller;

$dept = "Request for shipment";
$page_title = $dept;
$student_data = view_controller::Display_student_data();
$info = $student_data['student_data'];
$select_destination = null;
require_once ROOT . "/require/header.php";
?>
<nav>
    <?php if (isset($_GET['dept'])): ?>
        <h2><img src="./assets/icons/dashboard.png" alt="">pay <?php echo $_GET['dept'] ?> online</h2>
    <?php else: ?>
        <h2><img src="./assets/icons/dashboard.png" alt="">Request shipment</h2>
    <?php endif; ?>
    <div class="links">
        <div class="mode">
            <div class="mode_set"></div>
        </div>
        <div class="sun_moon">
            <img src="./assets/sun.png" alt="" class="weather">
        </div>
        <!-- <a href=""><img src="./assets/icons/back.png" alt="">Back</a> -->
    </div>
</nav>

<div class="actions">
    <!-- shipment request and payment -->
    <?php if (!isset($_POST['destination'])): ?>
        <div class="forms">
            <form action="#" method="post">
                <h2>Select your location.</h2>
                <label for="shipment_destination">
                    <img src="./assets/icons/fast-delivery.png" alt="">
                    <select name="location" id="" required>
                        <option value="mombasa">mombasa</option>
                        <option value="nairobi">nairobi</option>
                        <option value="elgeyo marakwet">elgeyo marakwet</option>
                        <option value="nakuru">nakuru</option>
                    </select>
                </label>
                <input type="submit" name="destination" value="submit">
            </form>
        </div>
    <?php else: ?>
        <?php
        $destination_data = view_controller::Display_available_destinations();
        $destination_info = $destination_data['destinstion_info'];
        ?>
        <div class="forms">
            <form action="#" method="post">
                <h2>Shipment form</h2>
                <label for="username">
                    <img src="./assets/icons/name.png" alt="">
                    <input type="text" value="Username: <?php echo $info['username'] ?>" name="username" readonly>
                </label>
                <label for="admission">
                    <img src="./assets/icons/admission.png" alt="">
                    <input type="text" name="admission" value="Admission: <?php echo $info['admission number'] ?>" readonly>
                </label>
                <label for="phone">
                    <img src="./assets/icons/index.png" alt="">
                    <input type="text" name="phone" placeholder="phone(07000000)" required>
                </label>
                <label for="location">
                    <img src="./assets/icons/fast-delivery.png" alt="">
                    <input type="text" name="location" value="<?php echo "shipment location: " . $destination_info['location'] ?>" readonly>
                </label>
                <label for="location">
                    <img src="./assets/icons/fast-delivery.png" alt="">
                    <input type="text" name="courier" value="<?php echo "courier: " . $destination_info['courier'] ?>" readonly>
                </label>
                <label for="location">
                    <img src="./assets/icons/fast-delivery.png" alt="">
                    <input type="text" name="courier" value="<?php echo "Collection point: " . $destination_info['courier'] ?>" readonly>
                </label>
                <label for="location">
                    <img src="./assets/icons/online_payment.png" alt="">
                    <input type="text" name="price" value="<?php echo "shipment cost: sh" . $destination_info['price'] ?>" readonly>
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