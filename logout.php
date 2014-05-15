<?php
session_start();
session_destroy();
unset($_SESSION['session_id']);
setcookie('erspu2435','');
Header("Location: login.php");
exit;
?>
