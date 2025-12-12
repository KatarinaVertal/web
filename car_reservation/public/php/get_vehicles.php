<?php
require_once __DIR__ . '/../../app/core/database.php';

$db = new Database();

// Vraciame LEN dostupné vozidlá
$sql = "
    SELECT id_vozidlo, spz, znacka, model, pocet_miest, typ_vozidla
    FROM vozidlo
    WHERE stav_vozidla = 'DOSTUPNE'
    ORDER BY id_vozidlo ASC
";

$result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
