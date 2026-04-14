<?php
if(isset($_GET['logout'])){
    session_destroy();
    header("location: ../files_src/login.php");
    exit();
}