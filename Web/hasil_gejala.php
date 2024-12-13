<?php
$disease = strtolower($_POST['disease']);
$details = [
    "flu" => ["symptoms" => ["Demam", "Batuk"], "solution" => "Istirahat dan minum banyak cairan."],
    "migrain" => ["symptoms" => ["Sakit Kepala", "Kelelahan"], "solution" => "Hindari stres dan konsumsi obat sesuai resep."]
];

$result = $details[$disease] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Penyakit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <h1>Detail Penyakit</h1>
        <?php
        if ($result) {
            echo '<p><strong>Penyakit:</strong> ' . ucfirst($disease) . '</p>';
            echo '<p><strong>Gejala:</strong> ' . implode(", ", $result["symptoms"]) . '</p>';
            echo '<p><strong>Solusi:</strong> ' . $result["solution"] . '</p>';
        } else {
            echo '<p>Penyakit tidak ditemukan dalam basis data.</p>';
        }
        ?>
        <a href="index.php"><button>Kembali</button></a>
    </div>
</body>
</html>
