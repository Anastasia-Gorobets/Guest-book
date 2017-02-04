<?php
session_start();
include("vendor/abeautifulsite/simple-php-captcha/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
echo $_SESSION['captcha']['image_src'];