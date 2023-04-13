<?php
namespace PHPMaker2019\project2;
session_start();
include_once "autoload.php";
$captcha = new Captcha("aftershock");
$_SESSION[SESSION_CAPTCHA_CODE] = $captcha->show();
?>