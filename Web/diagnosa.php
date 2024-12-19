<?php
require_once '../Service/database.php';

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

$gejala = [];
while ($row = pg_fetch_assoc($result)) {
    $gejala[] = $row;
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
    <form action="hasil_diagnosa.php" method="POST">
        <label>Nama:</label> <input type="text" name="name" required><br>
        <label>Usia:</label> <input type="number" name="age" required><br>
        <label>Tinggi Badan (cm):</label> <input type="number" name="height" required><br>
        <label>Berat Badan (kg):</label> <input type="number" name="weight" required><br>
        <label>Jenis Kelamin:</label>
        <select name="gender">
            <option value="Male">Pria</option>
            <option value="Female">Wanita</option>
        </select><br>
        <h3>Pilih Gejala</h3>
        <div class="list-container">
            <?php foreach ($gejala as $g): ?>
                <input type="checkbox" name="symptoms[]" value="<?= $g['id'] ?>"> <?= htmlspecialchars($g['nama']) ?><br>
            <?php endforeach; ?>
        </div>
        <input type="submit" value="Diagnose">
    </form>
</body>
</html>