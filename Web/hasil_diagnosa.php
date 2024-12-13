<?php
$symptoms = $_POST['symptoms'] ?? [];
$diagnosis = "Unknown";
$solution = "Consult a doctor for proper diagnosis.";

if (in_array("Fever", $symptoms) && in_array("Cough", $symptoms)) {
    $diagnosis = "Flu";
    $solution = "Rest and drink plenty of fluids.";
} elseif (in_array("Headache", $symptoms) && in_array("Fatigue", $symptoms)) {
    $diagnosis = "Migraine";
    $solution = "Avoid stress and take prescribed medication.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diagnosis Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Diagnosis Result</h1>
    <div class="container">
        <p>Name: <?= htmlspecialchars($_POST['name']) ?></p>
        <p>Diagnosis: <?= $diagnosis ?></p>
        <p>Solution: <?=  $solution ?></p>
        <a href="index.php"><button>Back to Home</button></a>
    </div>
</body>
</html>