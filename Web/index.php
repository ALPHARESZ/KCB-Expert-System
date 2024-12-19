<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expert System - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <h1>Expert System for Disease Diagnosis</h1>
        <div class="container">
            <a href="diagnosa.php"><button>Diagnosa Penyakit</button></a>
            <a href="gejala.php"><button>Ketahui Gejala Penyakit</button></a>
        </div>
    </div>
</body>
</html>