<?php
$gejala = ["Fever", "Cough", "Headache", "Fatigue", "Nausea"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diagnosis Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Input Your Information</h1>
    <form action="hasil_diagnosa.php" method="POST">
        <label>Name:</label> <input type="text" name="name" required><br>
        <label>Age:</label> <input type="number" name="age" required><br>
        <label>Height (cm):</label> <input type="number" name="height" required><br>
        <label>Weight (kg):</label> <input type="number" name="weight" required><br>
        <label>Gender:</label>
        <select name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br>
        <h3>Select Symptoms</h3>
        <?php
            foreach ($gejala as $g) {
                echo "<input type='checkbox' name='symptoms[]' value='$g'> $g<br>";
            }
            echo '<input type="submit" value="Diagnose">';
        ?>
    </form>
</body>
</html>