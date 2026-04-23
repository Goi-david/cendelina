<?php
session_start();
session_destroy();

// Clear remember me cookie
setcookie('user_email', '', time() - 3600, '/');

header('Location: admin-login.php');
exit();
?>