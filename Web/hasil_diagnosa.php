<?php
$symptoms = $_POST['symptoms'] ?? [];
$diagnosis = "Unknown";
$solution = "Konsultasikan ke dokter untuk diagnosis yang tepat.";

if (in_array("Demam", $symptoms) && in_array("Batuk", $symptoms)) {
    $diagnosis = "Flu";
    $solution = "Istirahat dan minum banyak air putih.";
} elseif (in_array("Sakit Kepala", $symptoms) && in_array("Kelelahan", $symptoms)) {
    $diagnosis = "Migrain";
    $solution = "Hindari stres dan minum obat yang diresepkan.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Diagnosis</title>
    <link rel="stylesheet" href="hasil_diagnosa.css">
</head>
<body>
    <div class="main-container">
            <h1>Hasil Diagnosis</h1>
            <p><strong>Nama:</strong> <?= htmlspecialchars($_POST['name']) ?></p>
            <p><strong>Diagnosis:</strong> <?= $diagnosis ?></p>
            <p><strong>Solusi:</strong> <?= $solution ?></p>
            <a href="index.php"><button>Kembali</button></a>
    </div>
</body>
</html>