<?php
require '../Service/database.php'; 

$disease = strtolower($_POST['disease']);

$queryPenyakit = "
    SELECT 
        p.id 
        AS 
            penyakit_id, 
        p.nama 
        AS 
            nama_penyakit
    FROM 
        penyakit p
    WHERE 
        LOWER(p.nama) = $1
";

$resultPenyakit = pg_query_params($dbconn, $queryPenyakit, [$disease]);
$penyakit = pg_fetch_assoc($resultPenyakit);

if ($penyakit) {
    $queryGejala = "
        SELECT 
            g.nama 
            AS 
                gejala
        FROM 
            gejala g
        JOIN 
            relasi_gejala rg 
            ON 
                g.id = rg.id_gejala
        WHERE 
            rg.id_penyakit = $1
    ";

    $resultGejala = pg_query_params($dbconn, $queryGejala, [$penyakit['penyakit_id']]);
    $gejala = [];
    while ($row = pg_fetch_assoc($resultGejala)) {
        $gejala[] = $row['gejala'];
    }

    $querySolusi = "
        SELECT 
            s.solusi
        FROM 
            solusi s
        JOIN 
            relasi_solusi rs 
            ON 
                s.id = rs.id_solusi
        WHERE 
            rs.id_penyakit = $1
    ";

    $resultSolusi = pg_query_params($dbconn, $querySolusi, [$penyakit['penyakit_id']]);
    $solusi = [];
    while ($row = pg_fetch_assoc($resultSolusi)) {
        $solusi[] = $row['solusi'];
    }
} else {
    $gejala = null;
    $solusi = null;
}
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
        <?php if ($penyakit): ?>
            <p><strong>Penyakit:</strong> <?= ucfirst($penyakit['nama_penyakit']) ?></p>
            <p><strong>Gejala:</strong> <?= implode(", ", $gejala) ?></p>
            <p><strong>Solusi:</strong> <?= implode(", ", $solusi) ?></p>
        <?php else: ?>
            <p>Penyakit tidak ditemukan dalam basis data.</p>
        <?php endif; ?>
        <a href="index.php"><button>Kembali</button></a>
    </div>
</body>
</html>