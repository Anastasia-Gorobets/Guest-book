<?php
session_start();
$a = $_SESSION['captcha']['code'];
$a = strtolower($a);
$a=trim($a);
$b = $_POST['captcha'];
$b = strtolower($b);
$b = trim($b);
if ($a == $b) { $valid="true"; }
else $valid="false";
echo $valid;



