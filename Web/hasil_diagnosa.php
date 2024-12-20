<?php
session_start();
require_once '../Service/database.php';

// Validasi apakah data sesi tersedia
if (!isset($_SESSION['patient_weight']) || !isset($_SESSION['patient_height'])) {
    die("Data pasien tidak ditemukan. Harap mulai dari awal.");
}

// Ambil data dari sesi
$weight = $_SESSION['patient_weight'];
$height = $_SESSION['patient_height'] / 100;

// Hitung BMI
$bmi = $weight / ($height * $height);

// Tentukan gejala berdasarkan BMI
$bmi_symptom_id = null;
if ($bmi < 18.5) {
    $bmi_symptom_id = 56; // ID untuk "Kekurangan Berat Badan"
} elseif ($bmi > 25) {
    $bmi_symptom_id = 57; // ID untuk "Kelebihan Berat Badan"
}

// Ambil gejala dari formulir
$symptoms = $_POST['symptoms'] ?? [];
if (empty($symptoms) && !$bmi_symptom_id) {
    die("Anda belum memilih gejala!");
}

// Gabungkan gejala BMI dengan gejala yang dipilih pengguna
if ($bmi_symptom_id) {
    $symptoms[] = $bmi_symptom_id;
}

// Validasi dan persiapkan ID gejala untuk query
$symptom_ids = implode(',', array_map('intval', $symptoms));

// Query untuk mendapatkan semua penyakit dan menghitung poin kemungkinan
$query = "
    SELECT 
        p.id AS penyakit_id, 
        p.nama AS penyakit_nama, 
        COUNT(rg.id_gejala) AS total_gejala, 
        SUM(CASE WHEN rg.id_gejala IN ($symptom_ids) THEN 1 ELSE 0 END) AS gejala_terpenuhi
    FROM 
        penyakit p
    JOIN 
        relasi_gejala rg 
        ON 
            p.id = rg.id_penyakit
    GROUP BY 
        p.id, p.nama
";
$result = pg_query($dbconn, $query);

if (!$result) {
    die("Terjadi kesalahan saat menghitung poin kemungkinan: " . pg_last_error());
}

// Hitung poin kemungkinan untuk setiap penyakit dan simpan penyakit dengan poin >= 60
$diagnosed_diseases = [];
while ($row = pg_fetch_assoc($result)) {
    $total_gejala = (int)$row['total_gejala'];
    $gejala_terpenuhi = (int)$row['gejala_terpenuhi'];

    // Hitung poin kemungkinan
    $score = ($gejala_terpenuhi / $total_gejala) * 100;

    // Jika skor >= 60, tambahkan ke daftar penyakit yang terdiagnosis
    if ($score >= 60) {
        // Cari solusi untuk penyakit ini
        $solution_query = "
            SELECT 
                s.solusi 
            FROM 
                solusi s
            JOIN 
                relasi_solusi rs 
                ON 
                    s.id = rs.id_solusi
            WHERE 
                rs.id_penyakit = {$row['penyakit_id']}
        ";
        $solution_result = pg_query($dbconn, $solution_query);

        // Simpan semua solusi dalam array
        $solutions = [];
        while ($solution_row = pg_fetch_assoc($solution_result)) {
            $solutions[] = $solution_row['solusi'];
        }

        // Tambahkan penyakit ke daftar
        $diagnosed_diseases[] = [
            'name' => $row['penyakit_nama'],
            'solutions' => $solutions,
            'score' => $score
        ];
    }
}

// Urutkan penyakit berdasarkan poin kemungkinan (score) secara menurun
usort($diagnosed_diseases, function($a, $b) {
    return $b['score'] <=> $a['score'];
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Diagnosis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="result-container">
        <h1>Hasil Diagnosis</h1>
        <p><strong>Nama:</strong> <?= htmlspecialchars($_SESSION['patient_name']) ?></p>
        <p><strong>Tinggi Badan:</strong> <?= htmlspecialchars($_SESSION['patient_height']) ?> cm</p>
        <p><strong>Berat Badan:</strong> <?= htmlspecialchars($_SESSION['patient_weight']) ?> kg</p>
        <p><strong>BMI:</strong> <?= number_format($bmi, 2) ?> (<?= $bmi < 18.5 ? 'Kekurangan Berat Badan' : ($bmi > 25 ? 'Kelebihan Berat Badan' : 'Normal') ?>)</p>
        
        <?php if (empty($diagnosed_diseases)): ?>
            <p><strong>Diagnosis:</strong> Tidak ada penyakit yang cocok dengan gejala yang Anda pilih.</p>
            <p><strong>Solusi:</strong> Konsultasikan ke dokter untuk diagnosis yang tepat.</p>
        <?php else: ?>
            <div class="diagnosis-container">
                <h2>Penyakit yang Mungkin Terdiagnosis</h2>
                <?php foreach ($diagnosed_diseases as $disease): ?>
                    <div class="disease-box">
                        <p class="disease-title">Penyakit: <?= htmlspecialchars($disease['name']) ?></p>
                        
                        <div class="solutions-container">
                            <strong>Solusi:</strong>
                            <?php foreach ($disease['solutions'] as $solution): ?>
                                <div class="solution-box">
                                    <?= htmlspecialchars($solution) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <p class="score-box"><strong>Poin Kemungkinan:</strong> <?= number_format($disease['score'], 2) ?>%</p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <a href="index.php"><button>Kembali</button></a>
    </div>
</body>
</html>