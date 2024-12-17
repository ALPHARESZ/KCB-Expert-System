<?php
$gejala = ["Demam", "Batuk", "Sakit Kepala", "Kelelahan", "Mual"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Diagnosa</title>
    <link rel="stylesheet" href="diagnosa.css">
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
        <?php
            foreach ($gejala as $g) {
                echo "<input type='checkbox' name='symptoms[]' value='$g'> $g<br>";
            }
            echo '<input type="submit" value="Diagnose">';
        ?>
    </form>
</body>
</html>