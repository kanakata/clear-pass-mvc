<?php

use App\Controllers\view_controller;
use App\Models\Date\Date;

$page_title = "dashboard";
$student_data = view_controller::Display_student_data();
$clearance_progress = view_controller::Display_clearance_progress();
$total_percentage = $clearance_progress['total_percentage'];
if($total_percentage == 100){
    view_controller::Display_report_date();
}
$status = $clearance_progress['status'];
$info = $student_data['student_data'];
require_once ROOT . "/require/header.php";
?>
<!-- same old navigation bar. -->
<nav>
    <h2><img src="./assets/icons/dashboard.png" alt="">Student dashboard</h2>
    <div class="links">
        <div class="mode">
            <div class="mode_set"></div>
        </div>
        <div class="sun_moon">
            <img src="/assets/sun.png" alt="" class="weather">
        </div>
        <a href="/login"><img src="./assets/icons/logout.png" alt="">log out</a>
        <a href="#footer"><img src="./assets/icons/customer-service.png" alt="">contact us</a>
    </div>
</nav>

<!-- welcome message customise it as per the school needs. -->
<h2 class="welcome">Hii👋there <?php echo $info['username'] ?> congratulations for completing your four year course. Welcome to your dash board.</h2>



<!-- student details. -->
<div class="details">
    <div><img src="./assets/icons/dashboard.png" alt="">Student Dashboard</div>
    <div><img src="./assets/icons/name.png" alt="">name: <?php echo $info['username'] ?></div>
    <div><img src="./assets/icons/admission.png" alt="">admission: <?php echo $info['admission number'] ?></div>
    <div><img src="./assets/icons/index.png" alt="">index: <?php echo $info['index number'] ?></div>
</div>

<!-- a short tutorial of how the site works to allow ease of use by the student. -->
<div class="t-holder">
    <div class="tutorial">
        <h1>We're glad you're here to begin your clearance process.</h1>
        <h2>This system is designed to make your final steps with us quick, clear, and efficient.</h2>
        <h3>What You Can Do Here :</h3>
        <ol>
            <li>View your status : See exactly which departments (e.g., Library, Finance & laboratory) still require your clearance.</li>
            <li>Resolve holds : Find instructions and contact information for any outstanding obligations you may have.</li>
            <li>Complete debts : Submit any necessary debts online through safaricom m-pesa through the school's paybill.</li>
        </ol>
        <h1>please note :</h1>
        <h2 class="red">1. Your final clearance status will be issued only after all departments have confirmed that you have met all your obligations.</h2>
        <h2 class="red">2. You should not forget your allocated clearance date !!!! . In case you do, you can always log in to confirm the date and if you cant make it make sure you communicate to the relevant authorities to be allocated another day.</h2>
        <h3>Ready to get started? proceed to the departments bellow.</h3>
    </div>
</div>

<!-- date logic goes here. -->
<div class="date">

    <!--checks if the student is cleared then displays the report day. NOTE:COOKIES HAVE TO ENABLED BUT AN ALT DATE IS STORED IN THE DATABASE JUST IN CASE THE STUDENT HAS THE COOKIE SETTINGS DISABLING THE COOKIES. -->
    <?php if (!isset($_COOKIE['report_day'])): ?>
        <h2><img src="./assets/icons/year.png" alt="">Your pic up date will be displayed here once your clearance is complete.</h2>
    <?php endif; ?>
    <!-- displays the report day only if the student is cleared. -->
    <?php if (isset($_COOKIE['report_day'])): ?>
        <h2><img src="./assets/icons/year.png" alt="">Your pic up date is on: <?php echo $_COOKIE['report_day'] ?></h2>
    <?php endif; ?>

    <!-- shipment request and pick up location. NOTE: THIS ONLY APPEARS WHEN THE STUDENT HAS FULLY CLEARED WITH ALL PAYMENT MADE ONLINE WON'T ACCEPT IF THE STUDENT HAS ANY DEBT TO BE SETTLED PHYSICALLY. -->
    <?php if ($total_percentage == 100 && !in_array("pending_physical_clearance", $status)): ?>
        <h2><img src="./assets/icons/fast-delivery.png" alt="shipment.png"><a href="/pay_shipment">Request shipment of my documents to my location.</a></h2>
        <?php if (true): ?>
            <h2><img src="./assets/icons/year.png" alt="">Your pic up location, if you request shipment, will be displayed here.</h2>
        <?php endif; ?>
    <?php else: ?>
        <h2 style="color: green;"><img src="./assets/icons/fast-delivery.png" alt="shipment.png">Complete clearance to request shipment for your documents.</h2>
    <?php endif; ?>

    <!-- WILL ONLY APPEAR IF THE STUDENT HAS A NOTIFICATION I.E SHIPMENT PIC-UP LOCATION. -->
    <!-- <h2>🔔 <a href="">You have a notification(s), view it.👉</a></h2> -->

    <!-- displays the clearance progress of the student in percentage. -->
    <h2><img src="./assets/icons/pending.png" alt="cleared.png">Your clearance progress is: <span><?php echo $total_percentage ?>%</span></h2>

</div>

<!-- displays a graph showing the performance of the class. -->

<div class="res-holder">
    <h2>Proceed as you wish, the order doesn't realy matter. Happy clearance 😊.</h2>
</div>


<div class="sdash">

    <!-- finance department -->
    <a href="/department?department=finance" class="dept <?php
                                                            if ($info['finance status'] == "cleared" || $info['finance status'] == "online" || $info['finance status'] == "pending_physical_payment") {
                                                                echo "complete";
                                                            } else {
                                                                echo "";
                                                            }
                                                            ?>">
        <div class="percentage"><?php
                                if ($info['finance status'] == "cleared" || $info['finance status'] == "online" || $info['finance status'] == "pending_physical_payment") {
                                    echo "100% cleared";
                                } else {
                                    echo "0% cleared";
                                }
                                ?></div>
        <div class="stat"><?php echo $info['finance status'] ?> </div>
        <div class="department"><img src="./assets/icons/finance.png" alt="">finance</div>
    </a>

    <!-- boarding department -->
    <a href="/department?department=boarding" class="dept <?php
                                                            if ($info['boarding status'] == "cleared" || $info['boarding status'] == "online" || $info['boarding status'] == "pending_physical_payment") {
                                                                echo "complete";
                                                            } else {
                                                                echo "";
                                                            }
                                                            ?>">
        <div class="percentage"><?php
                                if ($info['boarding status'] == "cleared" || $info['boarding status'] == "online" || $info['boarding status'] == "pending_physical_payment") {
                                    echo "100% cleared";
                                } else {
                                    echo "0% cleared";
                                }
                                ?></div>
        <div class="stat"><?php echo $info['boarding status'] ?> </div>
        <div class="department"><img src="./assets/icons/boarding.png" alt="">boarding</div>
    </a>

    <!-- accessories department. -->
    <a href="/department?department=accessories" class="dept <?php
                                                                if ($info['accessories status'] == "cleared" || $info['accessories status'] == "online" || $info['accessories status'] == "pending_physical_payment") {
                                                                    echo "complete";
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>">
        <div class="percentage"><?php
                                if ($info['accessories status'] == "cleared" || $info['accessories status'] == "online" || $info['accessories status'] == "pending_physical_payment") {
                                    echo "100% cleared";
                                } else {
                                    echo "0% cleared";
                                }
                                ?></div>
        <div class="stat"><?php echo $info['accessories status'] ?> </div>
        <div class="department"><img src="./assets/icons/accessories.png" alt="">accessories</div>
    </a>

    <!-- library department. -->
    <a href="/department?department=library" class="dept <?php
                                                            if ($info['library status'] == "cleared" || $info['library status'] == "online" || $info['library status'] == "pending_physical_payment") {
                                                                echo "complete";
                                                            } else {
                                                                echo "";
                                                            }
                                                            ?>">
        <div class="percentage"><?php
                                if ($info['library status'] == "cleared" || $info['library status'] == "online" || $info['library status'] == "pending_physical_payment") {
                                    echo "100% cleared";
                                } else {
                                    echo "0% cleared";
                                }
                                ?></div>
        <div class="stat"><?php echo $info['library status'] ?> </div>
        <div class="department"><img src="./assets/icons/library.png" alt="">library</div>
    </a>

    <!-- games department. -->
    <a href="/department?department=games" class="dept <?php
                                                        if ($info['games status'] == "cleared" || $info['games status'] == "online" || $info['games status'] == "pending_physical_payment") {
                                                            echo "complete";
                                                        } else {
                                                            echo "";
                                                        }
                                                        ?>">
        <div class="percentage"><?php
                                if ($info['games status'] == "cleared" || $info['games status'] == "online" || $info['games status'] == "pending_physical_payment") {
                                    echo "100% cleared";
                                } else {
                                    echo "0% cleared";
                                }
                                ?></div>
        <div class="stat"><?php echo $info['games status'] ?> </div>
        <div class="department"><img src="./assets/icons/games.png" alt="">games</div>
    </a>

    <!-- laboratory department. -->
    <a href="/department?department=laboratory" class="dept <?php
                                                            if ($info['laboratory status'] == "cleared" || $info['laboratory status'] == "online" || $info['laboratory status'] == "pending_physical_payment") {
                                                                echo "complete";
                                                            } else {
                                                                echo "";
                                                            }
                                                            ?>">
        <div class="percentage"><?php
                                if ($info['laboratory status'] == "cleared" || $info['laboratory status'] == "online" || $info['laboratory status'] == "pending_physical_payment") {
                                    echo "100% cleared";
                                } else {
                                    echo "0% cleared";
                                }
                                ?></div>
        <div class="stat"><?php echo $info['laboratory status'] ?> </div>
        <div class="department"><img src="./assets/icons/laboratory.png" alt="">laborartory</div>
    </a>
    <!-- NOTE: ANY MORE DEPARTMENT WILL BE ADDED AS PER THE SCHOOLS NEEDS. -->

</div>

<!-- alert message for a successful login. -->
<?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
    <div class="alert" style="display: block;">
        <div class="alert_title">welcome user</div>
        <a href="/dashboard" class="close">close<img src="./assets/icons/x.svg" alt=""></a>
        <div class="alert_message"><?php echo $_SESSION['login_success']; ?>🥳</div>
    </div>
    <?php unset($_SESSION['login_success']); ?>
<?php endif; ?>


<footer id="footer">
    <h2>&copy All rights reserved by <a href="">pegpem.com</a></h2>
    <h2>Designed and developed by gAKi.CoM</h2>
    <a href="tel:0793317819">Click here to contact Us if you are encountering any problem. Telephone number: 0793317819</a>
</footer>

<?php require_once ROOT . "/require/footer.php"; ?>