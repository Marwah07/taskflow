<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$query = "DELETE FROM tasks WHERE id='$id'";

mysqli_query($conn, $query);

header("Location: dashboard.php");
exit();
?>