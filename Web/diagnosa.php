<?php
session_start();
require_once '../Service/database.php';

// default step ketika mengisi form
if (!isset($_SESSION['current_step'])) {
    $_SESSION['current_step'] = 1;
}

// validasi pada saat mengisi identitas diri dan menyimpan data diri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_identity'])) {
    $_SESSION['patient_name'] = trim($_POST['name']);
    $_SESSION['patient_height'] = floatval($_POST['height']);
    $_SESSION['patient_weight'] = floatval($_POST['weight']);
    $_SESSION['current_step'] = 2;
}

// validasi pada saat memilih gejala-gejala
if ($_SESSION['current_step'] === 2) {
    // query untuk mengambil daftar gejala dari database
    $query = "
        SELECT 
            id, 
            nama 
        FROM 
            gejala 
        ORDER BY 
            nama
    ";

    $result = pg_query($dbconn, $query);

    if (!$result) {
        die("Error fetching gejala: " . pg_last_error());
    }

    // menyimpan daftar gejala dari database yang digunakan untuk menampilkan daftar gejala
    $gejala = [];
    while ($row = pg_fetch_assoc($result)) {
        $gejala[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Diagnosa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Harap Isi Data Anda</h1>

    <?php if ($_SESSION['current_step'] === 1): ?>
        <form action="" method="POST">
            <label>Nama:</label> <input type="text" name="name" required><br>
            <label>Tinggi Badan (cm):</label> <input type="number" name="height" required><br>
            <label>Berat Badan (kg):</label> <input type="number" name="weight" required><br>
            <button type="submit" name="submit_identity">Submit</button>
        </form>
    <?php else: ?>
        <div>
            <form action="hasil_diagnosa.php" method="post" class="symptoms-list">
                <strong><p>Nama : </strong> <?= htmlspecialchars($_SESSION['patient_name']) ?></p>
                <strong><p>Tinggi : </strong> <?= htmlspecialchars($_SESSION['patient_height']) ?></p>
                <strong><p>Berat : </strong> <?= htmlspecialchars($_SESSION['patient_weight']) ?></p> <br>
                <h3>Pilih Gejala</h3>
                    <div class="list-container">
                        <?php foreach ($gejala as $g): ?>
                            <div class="checbox-list">
                                <input type="checkbox" name="symptoms[]" value="<?= $g['id'] ?>"> 
                                <?= htmlspecialchars($g['nama']) ?><br>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" name="diagnose">Diagnosa</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>