<?php 
session_start();

if(array_key_exists('username', $_SESSION)) {
  session_destroy();
}

header('Location: index.php');
exit();
?>