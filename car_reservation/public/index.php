<?php
// public/index.php

require_once __DIR__ . '/../app/core/database.php';

// ziskame pripojenie k DB
$db = Database::getConnection();

// skúšobný dotaz – nech vieme, že DB žije
$stmt = $db->query('SELECT version() AS db_version');
$row = $stmt->fetch();
$dbVersion = $row['db_version'] ?? 'neznáma verzia';
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Rezervácia vozidiel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
// tu vložíme obsah stránky (tvoje pôvodné content.html)
include __DIR__ . '/php/home.php';
?>

<!-- JS -->
<script src="js/script.js"></script>
</body>
</html>
