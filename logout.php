<?php
session_start();
$_SESSION = []; // ini supaya yakin, sessionnya ilang
session_unset(); // untuk memastikan bahwa sessionnya hilang
session_destroy();

setcookie('id', '', time() - 3600);
setcookie('key', '', time() - 3600);

header("Location: login.php");
