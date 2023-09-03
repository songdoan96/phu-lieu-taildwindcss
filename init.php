<?php
ob_start();
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
$exp_time = 3600;
if (isset($_SESSION["user"]) && $_SESSION["user"]) {
    if (time() - $_SESSION["user"] > $exp_time) {
        header("Location: logout.php");
        exit;
    }
} else {
    header("Location:login.php");
    exit();
}

require_once "helper.php";
require_once "DB.php";
