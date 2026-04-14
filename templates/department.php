<?php

use App\Controllers\Api_controller;
use App\Controllers\view_controller;


Api_controller::No_debt();

if (isset($_GET['proceed'])) {
    Api_controller::Pay_physically();
}

$dept = $_GET['department'];
$page_title = $dept;
$student_data = view_controller::Display_student_data();
$info = $student_data['student_data'];

$complete_physical_alert = "";
require_once ROOT . "/require/header.php";
?>
<!-- navigation bar  -->
<nav>
    <h2><img src="./assets/icons/<?php echo $dept ?>.png" alt=""><?php echo $dept ?></h2>
    <div class="links">
        <div class="mode">
            <div class="mode_set"></div>
        </div>
        <div class="sun_moon">
            <img src="./assets/icons/sun.png" alt="" class="weather">
        </div>
        <a href="/dashboard"><img src="./assets/icons/back.png" alt="">back</a>
    </div>
</nav>

<!-- case: if the dept is the laboratory department the a custom message informing the students about the compulsory payment will be shown.-->
<?php if ($dept == "laboratory"): ?>
    <h2 class="welcome">Each student is required to pay a mandatory sh200 for laboratory damages.</h2>
    <h2 style="width: 100%; text-align: center; font-size: 18px; color: red;">NOTE: If you damaged any laboratory equipment it's value will be inclusive in the value section.</h2>
<?php endif; ?>

<!-- student details -->
<div class="details">
    <div class="profile"><img src="./assets/icons/dashboard.png" alt="">Student Dashboard - <?= $dept ?> department</div>
    <div><img src="./assets/icons/name.png" alt="">Name: <?php echo $info['username'] ?></div>
    <div><img src="./assets/icons/admission.png" alt="">Admission: <?php echo $info['admission number'] ?></div>
    <div><img src="./assets/icons/index.png" alt="">Index: <?php echo $info['index number'] ?></div>
</div>


<div class="dept_details">
    <!-- debt -->
    <div class="lost">
        <h2><img src="./assets/icons/lost-items.png" alt=""><?php echo $dept ?> debt</h2>
        <ol>
            <li><?php echo $info[$dept . " " . "debt"] ?></li>
        </ol>
    </div>

    <!-- debt value -->
    <div class="lost value">
        <h2><img src="./assets/icons/value.png" alt=""><?php echo $dept . "'s" ?> value</h2>
        <ol>
            <li>Ksh: <?php echo $info[$dept . " " . "value"] ?></li>
        </ol>
    </div>

    <!-- instances of status -->
    <?php if ($info[$dept . " status"] == "uncleared"): ?>


        <!-- if the student has debt two options are provided: to clear physically or online-payment -->
        <?php if ($info[$dept . " " . "value"] > 0 && $info[$dept . " " . "status"] == "uncleared" && $info[$dept . " " . "status"] != NULL && $info[$dept . " " . "debt"] != NULL): ?>

            <!-- option to pay online -->
            <h2 style="color: red; font-size: 18px;">NOTE: Once done, this action can't be undone.</h2>
            <div class="payment">
                <a href="?action=pay_online&department=<?php echo $_GET['department'] ?>"><img src="./assets/icons/online-payment.png" alt="">Pay online</a>
            </div>


            <!-- option to pay physically -->
            <h2 style="color: red; font-size: 18px;">NOTE: Once done, this action can't be undone.</h2>
            <div class="payment">
                <a href="?action=pay_physically&department=<?php echo $dept ?>"><img src="./assets/icons/pay_physically.png" alt="">Pay debt physically</a>
            </div>

            <!-- if the student has no debt a button to clear himself/herself is provided. -->
        <?php else: ?>
            <h2 style="color: green; font-size: 18px;">Looks like you were a very responsible student 💯👍.</h2>
            <div class="payment">
                <a href="?action=no_debt&department=<?php echo $dept ?>"><img src="./assets/icons/debt-free.png" alt="">Looks like you have no outstanding debts click here to clear.</a>
            </div>
        <?php endif; ?>

    <?php endif; ?>




    <!-- prompt will show up once the student has cleared via payment online -->
    <?php if ($info[$dept . " " . "status"] == "online"): ?>
        <div class="status dept_status">
            <h2><img src="./assets/icons/pending.png" alt="">Fully cleared, payment made via online phone number: </h2>
        </div>
    <?php endif; ?>

    <!-- checks if the student is now fully cleared. -->
    <?php if ($info[$dept . " " . "status"] == "cleared"): ?>
        <div class="status dept_status">
            <h2><img src="./assets/icons/pending.png" alt="">Fully cleared, no debt cease to exists 💯.</h2>
        </div>
    <?php endif; ?>

    <!-- prompt will show up if the student chooses the physical payment option, and a list of items to be replaced will be displayed. -->
    <?php if ($info[$dept . " " . "status"] == "pending_physical_payment"): ?>
        <div class="status dept_status">
            <h2><img src="./assets/icons/pending.png" alt="">Partially cleared, pending physical clearance.</h2>
        </div>
        <div class="status dept_status">
            <h2>*** You are partially cleared and required to bring <?php if ($info[$dept . " " . "debt"] != "none") {
                                                                        echo $info[$dept . " " . "debt"] . ", Market value sh: " . $info[$dept . " " . "value"];
                                                                    } else {
                                                                        echo "sh " . $info[$dept . " " . "value"];
                                                                    }
                                                                    ?> on your allocated pic up date. ***</h2>
        </div>
    <?php endif; ?>

    <!-- availability for departments for which availability are known by the school. -->
    <?php if ($dept == "games" || $dept == "library" || $dept == "boarding" || $dept == "accessories"):  ?>
        <div class="status availability">
            <h2><img src="./assets/icons/availability.png" alt="">Availability: <?php echo $info[$dept . " " . "availability"] ?></h2>
        </div>
    <?php endif; ?>

    <!-- final status. displays the status as per the database. -->
    <div class="status status">
        <h2><img src="./assets/icons/cleared.png" alt=""><?php echo $dept ?> clearance status: <?php echo $info[$dept . " " . "status"] ?>
            <?php if ($info[$dept . " " . "status"] == "uncleared" || $info[$dept . " " . "status"] == "pending_physical_payment"): ?>
                ❎
            <?php else: ?>
                ✅
            <?php endif; ?>
        </h2>
    </div>

    <!-- alert in case of error goes here. -->

    <!-- physical payment prompt  -->
    <?php if (isset($_GET['action']) && $_GET['action'] == "pay_physically"): ?>

        <div class="alert" style="display: <?php echo "block" ?>;">
            <div class="alert_title">confirm action <?= $_GET['action'] ?></div>
            <div class="alert_message" style="color: red;">

                <a href="?proceed&department=<?php echo $dept ?>" style="text-decoration: none; background: green; height: 40px; width: 150px; display: flex; align-items: center; justify-content: center; font-size: 18px; border-radius: 5px; color: white;">proceed</a>

                <a href="?cancel&department=<?php echo $dept ?>" style="text-decoration: none; background: red; height: 40px; width: 150px; display: flex; align-items: center; justify-content: center; font-size: 18px; border-radius: 5px; color: white;">cancel</a>

            </div>
        </div>
        <script>
            document.querySelector("body").style.overflow = "hidden";
        </script>

    <?php elseif (isset($_GET['action']) && $_GET['action'] == "pay_online"): ?>

        <div class="alert" style="display: <?php echo "block" ?>;">
            <div class="alert_title">confirm action <?= $_GET['action'] ?></div>
            <div class="alert_message" style="color: red;">

                <a href="/pay_ship?action=pay_debt&department=<?php echo $_GET['department'] ?>" style="text-decoration: none; background: green; height: 40px; width: 150px; display: flex; align-items: center; justify-content: center; font-size: 18px; border-radius: 5px; color: white;">proceed</a>

                <a href="?cancel&department=<?php echo $dept ?>" style="text-decoration: none; background: red; height: 40px; width: 150px; display: flex; align-items: center; justify-content: center; font-size: 18px; border-radius: 5px; color: white;">cancel</a>

            </div>
        </div>
        <script>
            document.querySelector("body").style.overflow = "hidden";
        </script>

    <?php endif; ?>

    <!-- end of code logic -->

</div>

<?php require_once ROOT . "/require/footer.php"; ?>