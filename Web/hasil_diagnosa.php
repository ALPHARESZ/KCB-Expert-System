<?php
require_once '../Service/database.php';

// Hitung BMI
$tinggi = (float) $_POST['height'] / 100;
$berat = (float) $_POST['weight'];
$bmi = $berat / ($tinggi * $tinggi);

$bmi_status = '';
if ($bmi < 18.5) $bmi_status = 'Kekurangan Berat Badan';
elseif ($bmi <= 25) $bmi_status = 'Normal';
else $bmi_status = 'Kelebihan Berat Badan';

// Ambil gejala dari form
$symptoms = $_POST['symptoms'] ?? [];
if (empty($symptoms)) {
    die("Anda belum memilih gejala!");
}

// Cari penyakit yang cocok berdasarkan gejala
$symptom_ids = implode(',', $symptoms);
$query = "
    SELECT p.id AS penyakit_id, p.nama AS penyakit_nama, s.solusi AS solusi
    FROM penyakit p
    JOIN relasi_gejala rg ON p.id = rg.id_penyakit
    JOIN relasi_solusi rs ON p.id = rs.id_penyakit
    JOIN solusi s ON rs.id_solusi = s.id
    WHERE rg.id_gejala IN ($symptom_ids)
    GROUP BY p.id, p.nama, s.solusi
    HAVING COUNT(rg.id_gejala) = (
        SELECT COUNT(*) FROM relasi_gejala WHERE id_penyakit = p.id AND id_gejala IN ($symptom_ids)
    )
    LIMIT 1
";
$result = pg_query($dbconn, $query);

$diagnosis = "Unknown";
$solution = "Konsultasikan ke dokter untuk diagnosis yang tepat.";

if ($row = pg_fetch_assoc($result)) {
    $diagnosis = $row['penyakit_nama'];
    $solution = $row['solusi'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Diagnosis</title>
    <link rel="stylesheet" href="style.css">
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