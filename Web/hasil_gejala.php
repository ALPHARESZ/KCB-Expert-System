<?php
$disease = strtolower($_POST['disease']);
$details = [
    "flu" => ["symptoms" => ["Fever", "Cough"], "solution" => "Rest and drink plenty of fluids."],
    "migraine" => ["symptoms" => ["Headache", "Fatigue"], "solution" => "Avoid stress and take prescribed medication."]
];

$result = $details[$disease] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Disease Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Disease Details</h1>
    <?php
        '<div class="container">';
        if ($result) {
            echo '<p>Disease: ' . ucfirst($disease) . '</p>
            <p>Symptoms: ' . implode(", ", $result["symptoms"]) . '</p>
            <p>Solution: ' . $result["solution"] . '</p>';
        } else {
            echo '<p>Disease not found in the database.</p>';
        }
        echo '<a href="index.php"><button>Back to Home</button></a>';
        '</div>'
    ?>
</body>
</html>