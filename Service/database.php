<?php
    function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception("File .env tidak ditemukan.");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
            }
        }
    }

    loadEnv(__DIR__ . '/../.env');

    $host =  $_ENV['DB_HOST'];
    $port =  $_ENV['DB_PORT'];
    $dbname =  $_ENV['DB_NAME'];
    $user =  $_ENV['DB_USER'];
    $pass =  $_ENV['DB_PASS'];

    try {
        $dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");
        if (!$dbconn) {
            throw new Exception("Koneksi gagal: " . pg_last_error());
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
?>