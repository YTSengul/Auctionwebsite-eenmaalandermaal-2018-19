<?php
session_start();
session_destroy();
header('location:index.php');
?>

<body>

<?php include_once 'components/header.php'; ?>

